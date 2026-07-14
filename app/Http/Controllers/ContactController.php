<?php

namespace App\Http\Controllers;

use App\Mail\Contactus;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('contact');
    }

    public function newsletter(Request $request)
    {
        $data = $request->validate(['email' => 'required|email']);

        // Persist the subscription (idempotent — re-subscribing clears any prior opt-out).
        NewsletterSubscriber::updateOrCreate(
            ['email' => $data['email']],
            ['unsubscribed_at' => null]
        );

        return back()->with('newsletter_success', 'Thank you for subscribing! You\'ll hear from us soon.');
    }

    public function send(Request $request)
    {
        // Validate OUTSIDE the try: a ValidationException is an Exception, so
        // catching it here would turn "your email is invalid" into a 500 and the
        // customer would never learn which field was wrong.
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            // A phone number is not a number: '+1 555 000 0000' is not `numeric`.
            'number'  => ['required', 'string', 'max:30'],
            'subject' => ['required', 'string', 'max:255'],
            'Message' => ['required', 'string', 'max:5000'],
        ]);

        try {
            $recipient = config('mail.contact_to') ?: config('mail.from.address');
            Mail::to($recipient)->send(new Contactus($data));
        } catch (\Exception $e) {
            Log::error('Contact form send failed: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'We could not send your message just now. Please try again, or email us directly.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Thanks, {$data['name']} — your message is on its way. We'll reply within 24 hours.",
        ]);
    }
}
