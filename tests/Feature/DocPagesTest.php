<?php

use App\Support\Docs;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('serves every company, help and legal page with the real company details filled in', function () {
    foreach (Docs::PATHS as $slug => $path) {
        $html = $this->get($path)->assertOk()->getContent();
        expect($html)->toContain(e(Docs::get($slug)['title']))->not->toContain('[support email]')->not->toContain('[Registered business address]')->not->toContain('[privacy email]');
    }
    $this->get('/refund-policy')->assertOk()->assertSee('Return &amp; Refund Policy', false);
});

it('states the seller terms the business actually runs on', function () {
    $this->get('/seller-agreement')->assertOk()->assertSee('$19.99')->assertSee('0% commission')->assertSee('Stripe')->assertDontSee('[Confirm');
});

it('shows the help center with its questions and the sitemap with every category and page', function () {
    $this->get('/faq')->assertOk()->assertSee('Where is my order?')->assertSee('data-faq-search', false);
    $this->get('/sitemap')->assertOk()->assertSee('All categories')->assertSee('Privacy Policy')->assertSee('Do Not Sell or Share');
});

it('takes a contact message without a phone number', function () {
    $this->postJson('/contactus', ['name' => 'Pat', 'email' => 'pat@example.com', 'subject' => 'An order', 'Message' => 'Where is it?'])->assertOk()->assertJson(['success' => true]);
    $this->postJson('/contactus', ['name' => 'Pat', 'email' => 'nope', 'subject' => 'An order', 'Message' => 'x'])->assertStatus(422);
});

it('drops the advertising tags once a visitor opts out of sale and sharing', function () {
    $this->get('/')->assertSee('G-X57MYCJ0B8', false);
    $this->withUnencryptedCookie('pg_optout', '1')->get('/')->assertDontSee('G-X57MYCJ0B8', false);
});
