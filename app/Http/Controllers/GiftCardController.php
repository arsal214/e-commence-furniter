<?php

namespace App\Http\Controllers;

use App\Services\GiftCardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/** Buying and redeeming gift cards from the shopper's account. */
class GiftCardController extends Controller
{
    public function buy(Request $request, GiftCardService $cards)
    {
        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:' . GiftCardService::MIN, 'max:' . GiftCardService::MAX],
            'recipient_email' => ['nullable', 'email:rfc', 'max:255'],
            'message' => ['nullable', 'string', 'max:300'],
        ], ['amount.min' => 'Gift cards start at $' . GiftCardService::MIN . '.', 'amount.max' => 'Gift cards go up to $' . GiftCardService::MAX . '.']);

        try {
            return redirect()->away($cards->startPurchase($request->user(), round((float) $data['amount'], 2), $data['recipient_email'] ?? null, $data['message'] ?? null));
        } catch (\Throwable $e) {
            Log::error('Gift card checkout failed: ' . $e->getMessage());

            return back()->with('error', 'We could not start the payment. Please try again in a moment.')->withInput();
        }
    }

    public function returned(Request $request, GiftCardService $cards)
    {
        $id = (string) $request->query('session_id');
        try {
            $card = $id !== '' ? $cards->confirmSession($request->user(), $id) : null;
        } catch (\Throwable $e) {
            Log::warning('Gift card confirm failed: ' . $e->getMessage());
            $card = null;
        }

        return redirect('/my-account/giftcards')->with($card ? 'success' : 'error', $card
            ? 'Your gift card is paid for. We emailed the code to ' . ($card->recipient_email ?: 'you') . '.'
            : 'We could not confirm that payment yet. If you were charged, your card will appear here within a few minutes.');
    }

    public function redeem(Request $request, GiftCardService $cards)
    {
        $data = $request->validate(['code' => ['required', 'string', 'max:40']]);

        return $cards->redeem($request->user(), $data['code'])
            ? redirect('/my-account/giftcards')->with('success', 'Gift card added. Your balance applies automatically at checkout.')
            : back()->with('error', 'That code is not valid, or it has already been used.');
    }
}
