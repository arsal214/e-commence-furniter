<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\User;
use App\Services\EmailCodeService;
use App\Services\SellerBilling;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class OnboardingController extends Controller
{
    public function __construct(protected EmailCodeService $codes, protected SellerBilling $billing) {}

    // ── 1. account ────────────────────────────────────────────────────────────────

    public function signupForm()
    {
        return view('seller.onboarding.signup');
    }

    public function signup(Request $request)
    {
        $data = $request->validate([
            'first_name' => 'required|string|max:60',
            'last_name'  => 'required|string|max:60',
            'email'      => 'required|email:rfc|max:255|unique:users,email',
            'phone'      => ['required', 'regex:/^\+?1?[\s().-]*\d{3}[\s().-]*\d{3}[\s.-]*\d{4}$/'],
            'password'   => ['required', Password::min(10)],
            'agree'      => 'accepted',
        ], [
            'phone.regex'  => 'Enter a US mobile number, e.g. (929) 469-9864.',
            'agree.accepted' => 'Please accept the Seller Agreement, Prohibited Items and Privacy Policy.',
        ]);

        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => trim($data['first_name'] . ' ' . $data['last_name']),
                'email' => $data['email'],
                'password' => $data['password'],
                'role' => 'seller',
            ]);
            $referrer = app(\App\Services\ReferralService::class)->referrerFor(request()->cookie(\App\Services\ReferralService::COOKIE));
            Seller::create(['user_id' => $user->id, 'owner_name' => $user->name, 'owner_phone' => $this->phone($data['phone']), 'referred_by' => $referrer?->id]);

            return $user;
        });

        Auth::login($user);
        $request->session()->regenerate();
        $this->codes->issue($user);

        return redirect()->route('seller.verify');
    }

    // ── 2. email code ─────────────────────────────────────────────────────────────

    public function verifyForm(Request $request)
    {
        if ($request->user()->email_verified_at) {
            return $this->resume($request->user()->seller);
        }

        return view('seller.onboarding.verify', ['email' => $request->user()->email]);
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => 'required|digits:6']);
        $user = $request->user();

        $result = $this->codes->check($user, $request->string('code'));
        if ($result !== 'ok') {
            return back()->withErrors(['code' => match ($result) {
                'wrong'   => 'That code is not right. Check the email and try again.',
                'locked'  => 'Too many wrong tries. Request a new code.',
                default   => 'That code has expired. Request a new one.',
            }]);
        }

        $user->seller->advanceTo('plan');

        return redirect()->route('seller.plan');
    }

    public function resend(Request $request)
    {
        $this->codes->issue($request->user());

        return back()->with('status', 'A new code is on its way.');
    }

    // ── 3. plan ($19.99/month) ────────────────────────────────────────────────────

    public function plan(Request $request)
    {
        if ($redirect = $this->gate($request, 'plan')) {
            return $redirect;
        }

        return view('seller.onboarding.plan', ['seller' => $request->user()->seller]);
    }

    public function subscribe(Request $request)
    {
        if ($redirect = $this->gate($request, 'plan')) {
            return $redirect;
        }

        $seller = $request->user()->seller;
        if ($seller->subscription_status === 'active') {
            $seller->advanceTo('business');

            return redirect()->route('seller.business');
        }

        try {
            return redirect()->away($this->billing->subscribeUrl($seller));
        } catch (\Throwable $e) {
            Log::error('Seller subscribe failed: ' . $e->getMessage());

            return back()->with('error', 'We could not open the payment page. Please try again in a moment.');
        }
    }

    public function planReturn(Request $request)
    {
        if ($redirect = $this->gate($request, 'plan')) {
            return $redirect;
        }

        $seller = $request->user()->seller;
        $paid = $seller->subscription_status === 'active';

        if (! $paid && $request->filled('session_id')) {
            try {
                $paid = $this->billing->confirmSubscription($seller, $request->string('session_id'));
            } catch (\Throwable $e) {
                Log::warning('Seller plan confirm failed: ' . $e->getMessage());
            }
        }

        if (! $paid) {
            return redirect()->route('seller.plan')->with('error', 'We could not confirm your payment yet. If you were charged, refresh in a minute.');
        }

        $seller->advanceTo('business');

        return redirect()->route('seller.business');
    }

    // ── 4. business ───────────────────────────────────────────────────────────────

    public function businessForm(Request $request)
    {
        if ($redirect = $this->gate($request, 'business')) {
            return $redirect;
        }

        return view('seller.onboarding.business', ['seller' => $request->user()->seller, 'states' => $this->states()]);
    }

    public function business(Request $request)
    {
        if ($redirect = $this->gate($request, 'business')) {
            return $redirect;
        }
        $seller = $request->user()->seller;

        $data = $request->validate([
            'business_name'  => 'required|string|max:160',
            'store_name'     => 'required|string|max:80',
            'business_type'  => ['required', Rule::in(['LLC', 'Corporation', 'Sole proprietorship', 'Partnership'])],
            'ein'            => ['required', 'regex:/^\d{2}-?\d{7}$/'],
            'state'          => ['required', Rule::in(array_keys($this->states()))],
            'business_phone' => ['required', 'regex:/^\+?1?[\s().-]*\d{3}[\s().-]*\d{3}[\s.-]*\d{4}$/'],
            'address'        => 'required|string|max:255',
            'city'           => 'required|string|max:80',
            'zip'            => ['required', 'regex:/^\d{5}(-\d{4})?$/'],
            'certificate'    => [$seller->documents()->where('type', 'registration')->exists() ? 'nullable' : 'required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ], [
            'ein.regex' => 'EIN must look like 12-3456789.',
            'zip.regex' => 'Enter a 5-digit US ZIP code.',
        ]);

        $seller->update([
            'business_name' => $data['business_name'],
            'store_name' => $data['store_name'],
            'slug' => $this->uniqueSlug($data['store_name'], $seller->id),
            'business_type' => $data['business_type'],
            'ein' => $this->formatEin($data['ein']),
            'state' => $data['state'],
            'business_phone' => $this->phone($data['business_phone']),
            'address' => $data['address'],
            'city' => $data['city'],
            'zip' => $data['zip'],
        ]);
        $this->storeDocument($seller, 'registration', $request->file('certificate'));
        $seller->advanceTo('owner');

        return redirect()->route('seller.owner');
    }

    // ── 5. owner ──────────────────────────────────────────────────────────────────

    public function ownerForm(Request $request)
    {
        if ($redirect = $this->gate($request, 'owner')) {
            return $redirect;
        }

        return view('seller.onboarding.owner', ['seller' => $request->user()->seller]);
    }

    public function owner(Request $request)
    {
        if ($redirect = $this->gate($request, 'owner')) {
            return $redirect;
        }
        $seller = $request->user()->seller;
        $need = fn (string $type) => $seller->documents()->where('type', $type)->exists() ? 'nullable' : 'required';
        $file = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'];

        $data = $request->validate([
            'owner_name'      => 'required|string|max:160',
            'owner_title'     => 'required|string|max:80',
            'owner_email'     => 'required|email:rfc|max:255',
            'owner_phone'     => ['required', 'regex:/^\+?1?[\s().-]*\d{3}[\s().-]*\d{3}[\s.-]*\d{4}$/'],
            'owner_dob'       => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->toDateString(), 'after:1900-01-01'],
            'owner_ssn_last4' => 'required|digits:4',
            'owner_address'   => 'required|string|max:255',
            'id_front'        => array_merge([$need('id_front')], $file),
            'id_back'         => array_merge([$need('id_back')], $file),
            'ein_letter'      => array_merge([$need('ein_letter')], $file),
        ], [
            'owner_dob.before_or_equal' => 'The business owner must be at least 18 years old.',
        ]);

        $seller->update([
            'owner_name' => $data['owner_name'],
            'owner_title' => $data['owner_title'],
            'owner_email' => $data['owner_email'],
            'owner_phone' => $this->phone($data['owner_phone']),
            'owner_dob' => $data['owner_dob'],
            'owner_ssn_last4' => $data['owner_ssn_last4'],
            'owner_address' => $data['owner_address'],
        ]);
        foreach (['id_front', 'id_back', 'ein_letter'] as $type) {
            $this->storeDocument($seller, $type, $request->file($type));
        }
        $seller->advanceTo('bank');

        return redirect()->route('seller.bank');
    }

    // ── 6. bank (Stripe Connect Express) ──────────────────────────────────────────

    public function bank(Request $request)
    {
        if ($redirect = $this->gate($request, 'bank')) {
            return $redirect;
        }

        return view('seller.onboarding.bank');
    }

    public function bankStart(Request $request)
    {
        if ($redirect = $this->gate($request, 'bank')) {
            return $redirect;
        }

        try {
            return redirect()->away($this->billing->bankUrl($request->user()->seller));
        } catch (\Throwable $e) {
            Log::error('Seller bank link failed: ' . $e->getMessage());

            return back()->with('error', 'We could not open the bank setup page. Please try again in a moment.');
        }
    }

    public function bankReturn(Request $request)
    {
        if ($redirect = $this->gate($request, 'bank')) {
            return $redirect;
        }
        $seller = $request->user()->seller;

        try {
            $ready = $this->billing->bankReady($seller);
        } catch (\Throwable $e) {
            Log::warning('Seller bank check failed: ' . $e->getMessage());
            $ready = false;
        }

        if (! $ready) {
            return redirect()->route('seller.bank')->with('error', 'Your bank details are not finished yet. Please complete the Stripe form.');
        }

        // Submitting for review: from here the team checks documents by hand.
        $seller->update(['status' => 'in_review', 'submitted_at' => now()]);
        $seller->advanceTo('done');

        return redirect()->route('seller.review');
    }

    // ── 7. submitted ──────────────────────────────────────────────────────────────

    public function review(Request $request)
    {
        if ($redirect = $this->gate($request, 'done')) {
            return $redirect;
        }

        return view('seller.onboarding.review', ['seller' => $request->user()->seller]);
    }

    // ── helpers ───────────────────────────────────────────────────────────────────

    /** Sends the applicant back to the step they have actually reached if they jump ahead. */
    protected function gate(Request $request, string $step)
    {
        $seller = $request->user()->seller;
        if (! $seller) {
            return redirect()->route('seller.signup');
        }
        if (! $request->user()->email_verified_at) {
            return redirect()->route('seller.verify');
        }

        return $seller->stepIndex() < array_search($step, Seller::STEPS, true) ? $this->resume($seller) : null;
    }

    protected function resume(Seller $seller)
    {
        return redirect()->route('seller.' . match ($seller->onboarding_step) {
            'done' => 'review',
            default => $seller->onboarding_step,
        });
    }

    protected function states(): array
    {
        return config('checkout.countries.US.subdivisions', []);
    }

    protected function phone(string $raw): string
    {
        $digits = preg_replace('/\D/', '', $raw);

        return '+1' . substr($digits, -10);
    }

    protected function formatEin(string $ein): string
    {
        $digits = preg_replace('/\D/', '', $ein);

        return substr($digits, 0, 2) . '-' . substr($digits, 2);
    }

    protected function uniqueSlug(string $name, int $sellerId): string
    {
        $base = \Illuminate\Support\Str::slug($name) ?: 'store';
        $slug = $base;
        for ($i = 2; Seller::where('slug', $slug)->where('id', '!=', $sellerId)->exists(); $i++) {
            $slug = $base . '-' . $i;
        }

        return $slug;
    }

    /** Documents hold ID and tax paperwork, so they go on the private disk, never public. */
    protected function storeDocument(Seller $seller, string $type, ?\Illuminate\Http\UploadedFile $file): void
    {
        if (! $file) {
            return;
        }

        $old = $seller->documents()->where('type', $type)->first();
        $path = $file->store("seller-documents/{$seller->id}", 'local');
        $seller->documents()->updateOrCreate(['type' => $type], ['path' => $path]);
        if ($old) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($old->path);
        }
    }
}
