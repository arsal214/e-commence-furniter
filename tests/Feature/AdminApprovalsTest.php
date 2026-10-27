<?php

use App\Mail\SellerNoticeMail;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use App\Models\User;
use Database\Seeders\MarketplaceCategorySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

beforeEach(function () {
    Mail::fake();
    Storage::fake('local');
    Storage::fake('public');
    $this->seed(MarketplaceCategorySeeder::class);
});

function aaAdmin(): User
{
    return User::factory()->create(['role' => 'admin']);
}

/** A seller waiting in the queue with everything an approval needs. */
function aaApplicant(array $over = [], bool $complete = true): Seller
{
    static $n = 0;
    $n++;
    $user = User::factory()->create(['role' => 'seller', 'email_verified_at' => now()]);
    $seller = Seller::create($over + [
        'user_id' => $user->id, 'store_name' => "Store {$n}", 'slug' => "store-{$n}", 'business_name' => "Store {$n} LLC", 'business_type' => 'LLC',
        'ein' => sprintf('58-%07d', 1000 + $n), 'state' => 'GA', 'address' => '1 Main', 'city' => 'Savannah', 'zip' => '31401',
        'owner_name' => 'Dan', 'owner_title' => 'Owner', 'owner_email' => "dan{$n}@acme.test", 'owner_phone' => '+19125550182',
        'onboarding_step' => 'done', 'status' => 'in_review', 'submitted_at' => now(),
        'subscription_status' => $complete ? 'active' : 'none', 'stripe_account_id' => $complete ? 'acct_1' : null,
    ]);
    if ($complete) {
        foreach (['registration', 'id_front', 'id_back', 'ein_letter'] as $type) {
            Storage::disk('local')->put("seller-documents/{$seller->id}/{$type}.pdf", 'PDFDATA');
            $seller->documents()->create(['type' => $type, 'path' => "seller-documents/{$seller->id}/{$type}.pdf"]);
        }
    }

    return $seller;
}

function aaProduct(Seller $s, array $over = []): Product
{
    $sub = Category::where('name', 'Bags')->whereNotNull('parent_id')->first();

    return Product::create($over + ['seller_id' => $s->id, 'category_id' => $sub->id, 'name' => 'Tote ' . uniqid(), 'slug' => 'tote-' . uniqid(),
        'price' => 40, 'stock' => 5, 'is_active' => true, 'moderation_status' => 'pending']);
}

// ── who may enter ────────────────────────────────────────────────────────────────────

it('keeps guests, shoppers and sellers out of every approval screen', function () {
    $s = aaApplicant();
    $p = aaProduct($s);
    $urls = ['get' => ['/admin/sellers', "/admin/sellers/{$s->id}", "/admin/sellers/{$s->id}/documents/registration", '/admin/moderation', "/admin/moderation/{$p->id}"],
             'post' => ["/admin/sellers/{$s->id}/decision", "/admin/moderation/{$p->id}"]];

    foreach ([null, User::factory()->create(), $s->user] as $who) {
        foreach ($urls['get'] as $u) { ($who ? $this->actingAs($who) : $this)->get($u)->assertRedirect(route('login')); }
        foreach ($urls['post'] as $u) { ($who ? $this->actingAs($who) : $this)->post($u, ['action' => 'approve'])->assertRedirect(route('login')); }
    }
    expect($s->fresh()->status)->toBe('in_review')->and($p->fresh()->moderation_status)->toBe('pending');
});

// ── seller applications ──────────────────────────────────────────────────────────────

it('lists applications that need review and shows the automated checks', function () {
    $ok = aaApplicant();
    $bad = aaApplicant(['ein' => '00-0000000', 'owner_email' => 'dan@gmail.com'], complete: false);

    $this->actingAs(aaAdmin())->get('/admin/sellers')->assertOk()->assertSee($ok->store_name)->assertSee($bad->store_name);
    $this->get("/admin/sellers/{$bad->id}")->assertOk()->assertSee('EIN format is valid')->assertSee('free webmail')->assertSee('Cannot approve yet');
});

it('hides draft sign-ups that never finished from the queue', function () {
    $draft = aaApplicant(['onboarding_step' => 'owner', 'status' => 'draft', 'store_name' => 'Unfinished Co']);

    $this->actingAs(aaAdmin())->get('/admin/sellers')->assertDontSee('Unfinished Co');
});

it('approves a complete seller, stamps the date and emails them', function () {
    $s = aaApplicant();

    $this->actingAs(aaAdmin())->post("/admin/sellers/{$s->id}/decision", ['action' => 'approve'])->assertRedirect(route('admin.sellers.index'));

    expect($s->fresh()->status)->toBe('approved')->and($s->fresh()->approved_at)->not->toBeNull()->and($s->fresh()->isLive())->toBeTrue();
    Mail::assertSent(SellerNoticeMail::class, fn ($m) => $m->hasTo($s->user->email) && str_contains($m->subjectLine, 'approved'));
});

it('refuses to approve an unpaid seller, a seller with no bank, or one with a missing document', function () {
    $admin = aaAdmin();
    foreach ([['subscription_status' => 'none'], ['stripe_account_id' => null]] as $over) {
        $s = aaApplicant($over);
        $this->actingAs($admin)->post("/admin/sellers/{$s->id}/decision", ['action' => 'approve'])->assertSessionHas('error');
        expect($s->fresh()->status)->toBe('in_review');
    }

    $s = aaApplicant();
    $s->documents()->where('type', 'id_back')->delete();
    $this->actingAs($admin)->post("/admin/sellers/{$s->id}/decision", ['action' => 'approve'])->assertSessionHas('error');
    expect($s->fresh()->status)->toBe('in_review');
    Mail::assertNothingSent();
});

it('requires a note to reject or ask for more information and sends it to the seller', function () {
    $s = aaApplicant();
    $admin = aaAdmin();

    $this->actingAs($admin)->post("/admin/sellers/{$s->id}/decision", ['action' => 'reject'])->assertSessionHasErrors('note');
    $this->actingAs($admin)->post("/admin/sellers/{$s->id}/decision", ['action' => 'request_info', 'note' => 'abc'])->assertSessionHasErrors('note');
    expect($s->fresh()->status)->toBe('in_review');

    $this->actingAs($admin)->post("/admin/sellers/{$s->id}/decision", ['action' => 'request_info', 'note' => 'Please upload a clearer ID photo.']);
    expect($s->fresh()->status)->toBe('info_requested')->and($s->fresh()->admin_note)->toContain('clearer ID');
    Mail::assertSent(SellerNoticeMail::class, fn ($m) => $m->note === 'Please upload a clearer ID photo.');
});

it('lets the seller fix and resubmit after more information was requested', function () {
    $s = aaApplicant(['status' => 'info_requested', 'admin_note' => 'Need a clearer ID']);

    $this->actingAs($s->user)->get('/seller')->assertOk()->assertSee('We need more information')->assertSee('Need a clearer ID');
    $this->post('/seller/resubmit')->assertRedirect(route('seller.dashboard'));

    expect($s->fresh()->status)->toBe('in_review');
    $this->actingAs(aaAdmin())->get('/admin/sellers')->assertSee($s->store_name);
});

it('rejects an application, tells the seller why and keeps the store hidden', function () {
    $s = aaApplicant();
    aaProduct($s, ['moderation_status' => 'approved']);

    $this->actingAs(aaAdmin())->post("/admin/sellers/{$s->id}/decision", ['action' => 'reject', 'note' => 'EIN does not match the business name.']);

    expect($s->fresh()->status)->toBe('rejected')->and($s->fresh()->isLive())->toBeFalse()->and(Product::visible()->count())->toBe(0);
    $this->actingAs($s->user)->get('/seller')->assertSee('not approved')->assertSee('EIN does not match');
});

it('records only the first of two decisions made at the same time and emails once', function () {
    $s = aaApplicant();
    $admin = aaAdmin();

    $this->actingAs($admin)->post("/admin/sellers/{$s->id}/decision", ['action' => 'approve']);
    $this->actingAs($admin)->post("/admin/sellers/{$s->id}/decision", ['action' => 'reject', 'note' => 'Changed my mind'])->assertSessionHas('error');

    expect($s->fresh()->status)->toBe('approved');
    Mail::assertSent(SellerNoticeMail::class, 1);
});

it('flags a second seller using the same EIN', function () {
    $a = aaApplicant(['ein' => '58-1111111']);
    $b = aaApplicant(['ein' => '58-1111111']);

    $this->actingAs(aaAdmin())->get("/admin/sellers/{$b->id}")->assertSee('Another seller already uses this EIN');
});

// ── private documents ────────────────────────────────────────────────────────────────

it('serves documents to an admin only, inline, never cached, and 404s for anything unknown', function () {
    $s = aaApplicant();

    $this->actingAs(aaAdmin())->get("/admin/sellers/{$s->id}/documents/registration")->assertOk()
        ->assertHeader('X-Content-Type-Options', 'nosniff')->assertHeader('Cache-Control', 'no-store, private');
    $this->get("/admin/sellers/{$s->id}/documents/passwd")->assertNotFound();
    $this->get("/admin/sellers/{$s->id}/documents/..%2F..%2F.env")->assertNotFound();

    $this->actingAs($s->user)->get("/admin/sellers/{$s->id}/documents/registration")->assertRedirect(route('login'));
    expect(Storage::disk('public')->allFiles())->toBe([]);
});

// ── listing moderation ───────────────────────────────────────────────────────────────

it('screens new listings: a prohibited word flags it, a normal one waits as pending', function () {
    $this->seed(MarketplaceCategorySeeder::class);
    $u = aaApplicant()->user;
    $form = fn ($name, $desc = 'Nice') => ['name' => $name, 'category_id' => Category::where('name', 'Bags')->whereNotNull('parent_id')->first()->id,
        'description' => $desc, 'price' => '30', 'stock' => 3, 'is_active' => '1', 'image' => UploadedFile::fake()->image('a.jpg', 1200, 1200)];

    $this->actingAs($u)->post('/seller/products', $form('Leather Tote'));
    $this->actingAs($u)->post('/seller/products', $form('CBD Relief Balm 1000mg'));
    $this->actingAs($u)->post('/seller/products', $form('Gunmetal Bracket', 'A sturdy bracket'));
    $this->actingAs($u)->post('/seller/products', $form('Vintage Rifle Sling'));

    $by = Product::pluck('moderation_status', 'name');
    expect($by['Leather Tote'])->toBe('pending')->and($by['CBD Relief Balm 1000mg'])->toBe('flagged')
        ->and($by['Gunmetal Bracket'])->toBe('pending')   // "gun" inside "Gunmetal" is not a match
        ->and($by['Vintage Rifle Sling'])->toBe('flagged');
    expect(Product::where('name', 'CBD Relief Balm 1000mg')->value('moderation_note'))->toContain('cbd');
});

it('does not tell the seller which word tripped the check', function () {
    $s = aaApplicant();
    aaProduct($s, ['name' => 'Odd One', 'moderation_status' => 'flagged', 'moderation_note' => 'Auto-check: possible prohibited item ("cbd")']);

    $this->actingAs($s->user)->get('/seller/products')->assertOk()->assertSee('In review')->assertDontSee('cbd');
});

it('shows the queue by tab with the flagged reason, to admins only', function () {
    $s = aaApplicant();
    aaProduct($s, ['name' => 'Waiting Tote']);
    aaProduct($s, ['name' => 'Flagged Balm', 'moderation_status' => 'flagged', 'moderation_note' => 'Auto-check: possible prohibited item ("cbd")']);

    $this->actingAs(aaAdmin())->get('/admin/moderation')->assertSee('Waiting Tote')->assertDontSee('Flagged Balm');
    $this->get('/admin/moderation?tab=flagged')->assertSee('Flagged Balm')->assertSee('cbd');
    $this->get('/admin/moderation?tab=nonsense')->assertOk()->assertSee('Waiting Tote'); // unknown tab falls back
});

it('approves a listing and it goes live only when the store is live too', function () {
    $live = aaApplicant(['status' => 'approved', 'subscription_status' => 'active']);
    $waiting = aaApplicant(['status' => 'in_review']);
    $a = aaProduct($live);
    $b = aaProduct($waiting);
    $admin = aaAdmin();

    $this->actingAs($admin)->post("/admin/moderation/{$a->id}", ['action' => 'approve']);
    $this->actingAs($admin)->post("/admin/moderation/{$b->id}", ['action' => 'approve']);

    expect(Product::visible()->pluck('id')->all())->toBe([$a->id]); // b is approved but its store is still in review
});

it('removes a listing with a reason, emails the seller once, and lets the seller fix it back into review', function () {
    $s = aaApplicant();
    $p = aaProduct($s, ['moderation_status' => 'approved', 'name' => 'Old Name', 'description' => 'Old']);
    $admin = aaAdmin();

    $this->actingAs($admin)->post("/admin/moderation/{$p->id}", ['action' => 'remove'])->assertSessionHasErrors('note');
    $this->actingAs($admin)->post("/admin/moderation/{$p->id}", ['action' => 'remove', 'note' => 'Counterfeit logo in photo.']);
    $this->actingAs($admin)->post("/admin/moderation/{$p->id}", ['action' => 'remove', 'note' => 'Counterfeit logo in photo.'])->assertSessionHas('error');

    expect($p->fresh()->moderation_status)->toBe('removed')->and(Product::visible()->count())->toBe(0);
    Mail::assertSent(SellerNoticeMail::class, 1);
    $this->actingAs($s->user)->get('/seller/products?status=rejected')->assertSee('Counterfeit logo in photo.');

    // Unchanged resubmission stays removed; changed content goes back to a reviewer.
    $sub = Category::where('name', 'Bags')->whereNotNull('parent_id')->first()->id;
    $base = ['name' => 'Old Name', 'category_id' => $sub, 'description' => 'Old', 'price' => '40', 'stock' => 5];
    $this->actingAs($s->user)->put("/seller/products/{$p->id}", $base);
    expect($p->fresh()->moderation_status)->toBe('removed');
    $this->actingAs($s->user)->put("/seller/products/{$p->id}", ['name' => 'New Clean Name'] + $base);
    expect($p->fresh()->moderation_status)->toBe('pending')->and($p->fresh()->moderation_note)->toBeNull();
});

it('does not moderate the house catalogue', function () {
    $sub = Category::where('name', 'Bags')->whereNotNull('parent_id')->first();
    $house = Product::create(['category_id' => $sub->id, 'name' => 'House Lamp', 'price' => 10, 'is_active' => true]);

    $this->actingAs(aaAdmin())->get("/admin/moderation/{$house->id}")->assertNotFound();
    $this->post("/admin/moderation/{$house->id}", ['action' => 'remove', 'note' => 'nope nope'])->assertNotFound();
    expect($house->fresh()->moderation_status)->toBe('approved');
});

it('shows approval counts as nav badges in the admin', function () {
    aaApplicant();
    aaProduct(aaApplicant());

    $this->actingAs(aaAdmin())->get('/admin')->assertOk()->assertSee('Seller approvals')->assertSee('Product moderation');
});
