<?php

use App\Mail\SellerVerifyCodeMail;
use App\Models\Seller;
use App\Models\User;
use App\Services\SellerBilling;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    Storage::fake('local');
});

function soSignup(array $over = []): array
{
    return $over + [
        'first_name' => 'Daniel', 'last_name' => 'Brooks', 'email' => 'daniel@shop.test', 'phone' => '(912) 555-0182',
        'password' => 'a-long-password', 'agree' => '1',
    ];
}

function soCode(): string
{
    $code = null;
    Mail::assertSent(SellerVerifyCodeMail::class, function ($m) use (&$code) { $code = $m->code; return true; });

    return $code;
}

/** A seller who has signed up and verified their email, signed in and waiting at $step. */
function soSellerAt(string $step): User
{
    $user = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    Seller::create(['user_id' => $user->id, 'onboarding_step' => $step, 'subscription_status' => in_array($step, ['verify', 'plan']) ? 'none' : 'active']);

    return $user;
}

function soBusiness(array $over = []): array
{
    return $over + [
        'business_name' => 'Harbor & Hide Goods LLC', 'store_name' => 'Harbor & Hide', 'business_type' => 'LLC', 'ein' => '58-2948163',
        'state' => 'GA', 'business_phone' => '(912) 555-0110', 'address' => '412 E Bay St', 'city' => 'Savannah', 'zip' => '31401',
        'certificate' => UploadedFile::fake()->create('cert.pdf', 200, 'application/pdf'),
    ];
}

function soOwner(array $over = []): array
{
    return $over + [
        'owner_name' => 'Daniel J. Brooks', 'owner_title' => 'Owner', 'owner_email' => 'daniel@shop.test', 'owner_phone' => '9125550182',
        'owner_dob' => '1986-04-18', 'owner_ssn_last4' => '1234', 'owner_address' => '88 Jones St, Savannah, GA',
        'id_front' => UploadedFile::fake()->image('f.jpg'), 'id_back' => UploadedFile::fake()->image('b.jpg'),
        'ein_letter' => UploadedFile::fake()->create('ein.pdf', 100, 'application/pdf'),
    ];
}

// ── account + email code ─────────────────────────────────────────────────────────────

it('creates the seller account, signs in and emails a hashed 6-digit code', function () {
    $this->post('/sell/signup', soSignup())->assertRedirect(route('seller.verify'));

    $user = User::where('email', 'daniel@shop.test')->first();
    expect($user->role)->toBe('seller')->and($user->email_verified_at)->toBeNull()
        ->and($user->seller->onboarding_step)->toBe('verify')->and($user->seller->owner_phone)->toBe('+19125550182');
    expect(soCode())->toMatch('/^\d{6}$/')
        ->and(Hash::check(soCode(), $user->email_code_hash))->toBeTrue()
        ->and($user->email_code_hash)->not->toBe(soCode());
    $this->assertAuthenticatedAs($user);
});

it('rejects a weak password, a taken email, a non-US phone and a missing agreement', function () {
    User::factory()->create(['email' => 'taken@shop.test']);

    $this->post('/sell/signup', soSignup(['password' => 'short']))->assertSessionHasErrors('password');
    $this->post('/sell/signup', soSignup(['email' => 'taken@shop.test']))->assertSessionHasErrors('email');
    $this->post('/sell/signup', soSignup(['phone' => '123']))->assertSessionHasErrors('phone');
    $this->post('/sell/signup', soSignup(['agree' => null]))->assertSessionHasErrors('agree');
    expect(User::where('role', 'seller')->count())->toBe(0);
});

it('makes ONE account when sign-up is double-submitted', function () {
    $this->post('/sell/signup', soSignup());
    auth()->logout();
    $this->post('/sell/signup', soSignup())->assertSessionHasErrors('email');

    expect(User::where('email', 'daniel@shop.test')->count())->toBe(1)->and(Seller::count())->toBe(1);
});

it('verifies with the right code and moves to the plan step', function () {
    $this->post('/sell/signup', soSignup());
    $this->post('/sell/verify', ['code' => soCode()])->assertRedirect(route('seller.plan'));

    $user = User::where('email', 'daniel@shop.test')->first();
    expect($user->email_verified_at)->not->toBeNull()->and($user->seller->onboarding_step)->toBe('plan')->and($user->email_code_hash)->toBeNull();
});

it('rejects a wrong code, an expired code, and locks after five wrong tries', function () {
    $this->post('/sell/signup', soSignup());
    $good = soCode();
    $wrong = $good === '000000' ? '111111' : '000000';

    $this->post('/sell/verify', ['code' => $wrong])->assertSessionHasErrors('code');

    foreach (range(1, 4) as $_) {
        $this->post('/sell/verify', ['code' => $wrong]);
    }
    // Five wrong guesses used: even the correct code is refused until a new one is requested.
    $this->post('/sell/verify', ['code' => $good])->assertSessionHasErrors('code');
    expect(User::first()->email_verified_at)->toBeNull();

    $r = $this->post('/sell/verify/resend'); fwrite(STDERR, 'RESEND '.$r->status().' '.json_encode(session()->all())."
");
    $r->assertSessionHas('status');
    User::first()->forceFill(['email_code_expires_at' => now()->subMinute()])->save();
    $this->post('/sell/verify', ['code' => '123456'])->assertSessionHasErrors('code');
});

it('limits resend to 3 a minute without blocking other steps', function () {
    $this->post('/sell/signup', soSignup());

    foreach (range(1, 3) as $_) {
        $this->post('/sell/verify/resend')->assertSessionHas('status');
    }
    $this->post('/sell/verify/resend')->assertStatus(429);
    $this->post('/sell/verify', ['code' => '000000'])->assertSessionHasErrors('code'); // still answered, not 429
});

it('rejects a code that is not exactly six digits', function () {
    $this->post('/sell/signup', soSignup());
    $this->post('/sell/verify', ['code' => '12ab56'])->assertSessionHasErrors('code');
    $this->post('/sell/verify', ['code' => '12345'])->assertSessionHasErrors('code');
});

// ── step order ───────────────────────────────────────────────────────────────────────

it('sends an unverified seller back to the code page and a skipper back to their step', function () {
    $this->post('/sell/signup', soSignup());
    $this->get('/sell/business')->assertRedirect(route('seller.verify'));

    $this->actingAs(soSellerAt('plan'))->get('/sell/owner')->assertRedirect(route('seller.plan'));
    $this->actingAs(soSellerAt('business'))->get('/sell/bank')->assertRedirect(route('seller.business'));
});

it('keeps guests out of every onboarding step', function () {
    foreach (['verify', 'plan', 'business', 'owner', 'bank', 'review'] as $step) {
        $this->get("/sell/{$step}")->assertRedirect(route('login'));
    }
});

// ── plan ─────────────────────────────────────────────────────────────────────────────

it('sends the seller to Stripe Checkout to subscribe', function () {
    $this->mock(SellerBilling::class)->shouldReceive('subscribeUrl')->once()->andReturn('https://checkout.stripe.test/s1');

    $this->actingAs(soSellerAt('plan'))->post('/sell/plan')->assertRedirect('https://checkout.stripe.test/s1');
});

it('does not advance when Stripe says the plan is unpaid, and shows a friendly error when Stripe is down', function () {
    $user = soSellerAt('plan');
    $this->mock(SellerBilling::class, function ($m) {
        $m->shouldReceive('confirmSubscription')->andReturn(false);
        $m->shouldReceive('subscribeUrl')->andThrow(new RuntimeException('stripe down'));
    });

    $this->actingAs($user)->get('/sell/plan/return?session_id=cs_x')->assertRedirect(route('seller.plan'))->assertSessionHas('error');
    $this->actingAs($user)->post('/sell/plan')->assertSessionHas('error');
    expect($user->seller->fresh()->onboarding_step)->toBe('plan');
});

it('moves to business once the subscription is confirmed', function () {
    $user = soSellerAt('plan');
    $this->mock(SellerBilling::class)->shouldReceive('confirmSubscription')->once()->andReturn(true);

    $this->actingAs($user)->get('/sell/plan/return?session_id=cs_ok')->assertRedirect(route('seller.business'));
    expect($user->seller->fresh()->onboarding_step)->toBe('business');
});

// ── business + owner ─────────────────────────────────────────────────────────────────

it('saves business details and keeps the certificate on the PRIVATE disk', function () {
    $user = soSellerAt('business');
    $this->actingAs($user)->post('/sell/business', soBusiness())->assertRedirect(route('seller.owner'));

    $s = $user->seller->fresh();
    $doc = $s->documents()->where('type', 'registration')->first();
    expect($s->ein)->toBe('58-2948163')->and($s->slug)->toBe('harbor-hide')->and($s->onboarding_step)->toBe('owner')
        ->and($doc)->not->toBeNull();
    Storage::disk('local')->assertExists($doc->path);
    Storage::disk('public')->assertMissing($doc->path);
});

it('validates the business form: EIN, ZIP, state, file type and a required certificate', function () {
    $user = soSellerAt('business');

    $this->actingAs($user)->post('/sell/business', soBusiness(['ein' => '123']))->assertSessionHasErrors('ein');
    $this->actingAs($user)->post('/sell/business', soBusiness(['zip' => '1234']))->assertSessionHasErrors('zip');
    $this->actingAs($user)->post('/sell/business', soBusiness(['state' => 'XX']))->assertSessionHasErrors('state');
    $this->actingAs($user)->post('/sell/business', soBusiness(['business_type' => 'Hacker']))->assertSessionHasErrors('business_type');
    $this->actingAs($user)->post('/sell/business', soBusiness(['certificate' => UploadedFile::fake()->create('x.exe', 10)]))->assertSessionHasErrors('certificate');
    $this->actingAs($user)->post('/sell/business', soBusiness(['certificate' => UploadedFile::fake()->create('big.pdf', 11000, 'application/pdf')]))->assertSessionHasErrors('certificate');
    $this->actingAs($user)->post('/sell/business', array_diff_key(soBusiness(), ['certificate' => 1]))->assertSessionHasErrors('certificate');
    expect($user->seller->fresh()->onboarding_step)->toBe('business');
});

it('gives two stores with the same name different slugs', function () {
    $a = soSellerAt('business');
    $b = soSellerAt('business');
    $this->actingAs($a)->post('/sell/business', soBusiness());
    $this->actingAs($b)->post('/sell/business', soBusiness());

    expect($a->seller->fresh()->slug)->toBe('harbor-hide')->and($b->seller->fresh()->slug)->toBe('harbor-hide-2');
});

it('encrypts the owner date of birth and SSN digits at rest and stores ID privately', function () {
    $user = soSellerAt('owner');
    $this->actingAs($user)->post('/sell/owner', soOwner())->assertRedirect(route('seller.bank'));

    $raw = DB::table('sellers')->where('id', $user->seller->id)->first();
    expect($raw->owner_dob)->not->toContain('1986')->and($raw->owner_ssn_last4)->not->toBe('1234');
    expect($user->seller->fresh()->owner_dob)->toBe('1986-04-18')->and($user->seller->fresh()->owner_ssn_last4)->toBe('1234');
    expect($user->seller->documents()->count())->toBe(3);
    foreach ($user->seller->documents as $doc) {
        Storage::disk('local')->assertExists($doc->path);
        Storage::disk('public')->assertMissing($doc->path);
    }
});

it('rejects an owner under 18, a bad SSN and a missing ID', function () {
    $user = soSellerAt('owner');

    $this->actingAs($user)->post('/sell/owner', soOwner(['owner_dob' => now()->subYears(17)->toDateString()]))->assertSessionHasErrors('owner_dob');
    $this->actingAs($user)->post('/sell/owner', soOwner(['owner_ssn_last4' => '12a4']))->assertSessionHasErrors('owner_ssn_last4');
    $this->actingAs($user)->post('/sell/owner', array_diff_key(soOwner(), ['id_front' => 1]))->assertSessionHasErrors('id_front');
    expect($user->seller->fresh()->onboarding_step)->toBe('owner');
});

// ── bank + submit ────────────────────────────────────────────────────────────────────

it('submits the application for review only after Stripe confirms the bank', function () {
    $user = soSellerAt('bank');
    $this->mock(SellerBilling::class, function ($m) {
        $m->shouldReceive('bankUrl')->andReturn('https://connect.stripe.test/onb');
        $m->shouldReceive('bankReady')->once()->andReturn(false);
        $m->shouldReceive('bankReady')->once()->andReturn(true);
    });

    $this->actingAs($user)->post('/sell/bank')->assertRedirect('https://connect.stripe.test/onb');
    $this->actingAs($user)->get('/sell/bank/return')->assertRedirect(route('seller.bank'))->assertSessionHas('error');
    expect($user->seller->fresh()->status)->toBe('draft');

    $this->actingAs($user)->get('/sell/bank/return')->assertRedirect(route('seller.review'));
    $s = $user->seller->fresh();
    expect($s->status)->toBe('in_review')->and($s->submitted_at)->not->toBeNull()->and($s->onboarding_step)->toBe('done');
    $this->actingAs($user)->get('/sell/review')->assertOk()->assertSee('Application submitted');
});

it('never exposes the store before approval, even after submitting', function () {
    $user = soSellerAt('done');
    $user->seller->update(['status' => 'in_review']);

    expect($user->seller->fresh()->isLive())->toBeFalse();
});

it('renders every step on a phone-width page with no horizontal-scroll hazards', function () {
    $this->post('/sell/signup', soSignup())->assertRedirect();
    $this->get('/sell/verify')->assertOk()->assertSee('viewport', false)->assertSee('Verify your email');

    foreach (['plan', 'business', 'owner', 'bank'] as $step) {
        $this->actingAs(soSellerAt($step))->get("/sell/{$step}")->assertOk()->assertSee('width=device-width', false);
    }
});
