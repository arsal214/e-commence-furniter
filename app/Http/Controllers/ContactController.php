<?php

namespace App\Http\Controllers;

use App\Mail\Contactus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function show()
    {
        return view('emails.contact');
    }

    public function send(Request $request)
    {
        try {
            // Validation logic
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'number' => 'required|numeric',
                'subject' => 'required|string|max:255',
                'Message' => 'required|string',
            ]);

            // Prepare email data
            $data = $request->only(['name', 'email', 'number', 'subject', 'Message']);
            
            // Send email
            Mail::raw('Name: ' . $data['name'] . "\n" . 
                    'Email: ' . $data['email'] . "\n" . 
                    'Number: ' . $data['number'] . "\n" . 
                    'Subject: ' . $data['subject'] . "\n" . 
                    'Message: ' . $data['Message'], 
                function ($message) use ($data) {
                    $message->to('your-email@gmail.com') // Replace with your email
                            ->subject($data['subject']);
                }
            );

            // Return raw HTML for success
            $successMessage = "
                <fieldset class='border border-green-300 p-6 mb-4 rounded-md bg-green-50'>
                    <div>
                        <h3 class='text-green-700 text-lg font-semibold mb-2'>Email Sent Successfully.</h3>
                        <p class='text-green-600'>Thank you <strong class='font-medium text-green-800'>{$data['name']}</strong>, your message has been submitted to us.</p>
                    </div>
                </fieldset>
            ";

            return response()->json([
                'message' => $successMessage,
            ]);
            

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again later.',
            ], 500);
        }
    }
}
