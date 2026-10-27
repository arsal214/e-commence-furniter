<?php

namespace App\Http\Controllers;

use App\Support\AccountViews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountController extends Controller
{
    public function show(Request $request, string $tab = 'overview')
    {
        abort_unless(array_key_exists($tab, AccountViews::TABS), 404);

        return view('account.page', AccountViews::build($request->user(), $tab));
    }

    public function profile(Request $request)
    {
        $user = $request->user();
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email:rfc', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        // A changed email address is no longer a verified one.
        $user->forceFill($data + ($data['email'] !== $user->email ? ['email_verified_at' => null] : []))->save();

        return redirect('/my-account/settings')->with('success', 'Your details were saved.');
    }

    public function password(Request $request)
    {
        $user = $request->user();
        $data = $request->validate(['current_password' => ['required', 'string'], 'password' => ['required', 'confirmed', Password::min(8)]]);

        if (! Hash::check($data['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Your current password is not right.']);
        }
        $user->forceFill(['password' => $data['password'], 'must_reset_password' => false])->save();

        return redirect('/my-account/settings')->with('success', 'Your password was changed.');
    }

    public function notifications(Request $request)
    {
        $request->user()->forceFill(['marketing_opt_out_at' => $request->boolean('marketing') ? null : now()])->save();

        return redirect()->back(302, [], '/my-account/settings')->with('success', 'Your email choices were saved.');
    }

    private const MAX_ADDRESSES = 10;

    public function addAddress(Request $request)
    {
        $us = config('checkout.countries.US');
        $data = $request->validate([
            'label' => ['required', 'string', 'max:30'], 'name' => ['required', 'string', 'max:120'], 'phone' => ['nullable', 'string', 'max:30'],
            'address' => ['required', 'string', 'max:160'], 'address2' => ['nullable', 'string', 'max:80'], 'city' => ['required', 'string', 'max:80'],
            'state' => ['required', 'string', \Illuminate\Validation\Rule::in(array_keys($us['subdivisions']))],
            'zip' => ['required', 'string', 'regex:' . $us['postal_regex']], 'is_default' => ['nullable', 'boolean'],
        ], ['state.in' => 'Choose a valid US state.', 'zip.regex' => 'Enter a valid US ZIP code.']);
        $user = $request->user();

        if ($user->addresses()->count() >= self::MAX_ADDRESSES) {
            return back()->with('error', 'You can save up to ' . self::MAX_ADDRESSES . ' addresses. Remove one first.')->withInput();
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $data, $request) {
            $default = $request->boolean('is_default') || ! $user->addresses()->exists();
            $default && $user->addresses()->update(['is_default' => false]);
            $user->addresses()->create(['is_default' => $default] + $data);
        });

        return redirect('/my-account/wallet')->with('success', 'Address saved.');
    }

    public function defaultAddress(Request $request, int $address)
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($address);
        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $address) {
            $user->addresses()->update(['is_default' => false]);
            $address->update(['is_default' => true]);
        });

        return redirect('/my-account/wallet')->with('success', 'Default address updated.');
    }

    public function deleteAddress(Request $request, int $address)
    {
        $user = $request->user();
        $address = $user->addresses()->findOrFail($address);
        $wasDefault = $address->is_default;
        $address->delete();
        $wasDefault && $user->addresses()->latest('id')->first()?->update(['is_default' => true]);

        return redirect('/my-account/wallet')->with('success', 'Address removed.');
    }

    /** Follow or unfollow a live store. */
    public function follow(Request $request, string $slug)
    {
        $seller = \App\Models\Seller::live()->where('slug', $slug)->firstOrFail();
        $existing = \App\Models\StoreFollow::where('user_id', $request->user()->id)->where('seller_id', $seller->id);

        if ($existing->exists()) {
            $existing->delete();
            $msg = "You unfollowed {$seller->store_name}.";
        } else {
            \App\Models\StoreFollow::firstOrCreate(['user_id' => $request->user()->id, 'seller_id' => $seller->id]);
            $msg = "You are following {$seller->store_name}.";
        }

        return back()->with('success', $msg);
    }
}
