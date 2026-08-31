<?php

namespace App\Http\Controllers;

use App\Models\NewsletterSubscriber;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * Opt-out landing for the unsubscribe link in promotional email.
 *
 * The route is signed, so the link proves it came from us without needing a
 * per-recipient token column. Both audiences are stamped: an address can be a
 * newsletter subscriber and an account holder at once, and unsubscribing should
 * mean unsubscribing from both.
 *
 * Transactional mail (orders, password resets) is unaffected.
 */
class MarketingController extends Controller
{
    /**
     * GET is what a mail client follows; POST is the one-click form Gmail and
     * Outlook submit for the List-Unsubscribe-Post header.
     */
    public function unsubscribe(Request $request)
    {
        $email = (string) $request->query('email');

        User::where('email', $email)->whereNull('marketing_opt_out_at')->update(['marketing_opt_out_at' => now()]);
        NewsletterSubscriber::where('email', $email)->whereNull('unsubscribed_at')->update(['unsubscribed_at' => now()]);

        if ($request->isMethod('post')) {
            return response()->noContent();
        }

        return view('unsubscribed', ['email' => $email]);
    }
}
