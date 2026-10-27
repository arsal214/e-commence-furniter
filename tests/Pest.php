<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

pest()->extend(Tests\TestCase::class)
 // ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    // Card and PayPal are the only ways to pay. No test may reach the PayPal API, so the
    // service is faked for every Feature test: order creation answers instantly and a capture succeeds.
    ->beforeEach(function () {
        $this->mock(\App\Services\PayPalService::class, function ($mock) {
            $mock->shouldReceive('createOrder')->andReturn(['id' => 'PP-' . uniqid()]);
            $mock->shouldReceive('livemode')->andReturn(false);
            $mock->shouldReceive('captureOrder')->andReturn(['status' => 'COMPLETED']);
        });
    })
    ->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function something()
{
    // ..
}

/** Complete payment for the newest order the way the PayPal button does (capture -> settlement). */
function payLatestOrder($test): \App\Models\Order
{
    $order = \App\Models\Order::latest('id')->firstOrFail();
    $test->postJson(route('checkout.paypal-capture', $order))->assertOk();

    return $order->fresh();
}
