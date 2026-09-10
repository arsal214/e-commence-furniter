<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

/**
 * Receives images dropped, pasted, or picked in the email editors.
 *
 * Email clients cannot render a base64 `data:` image — Gmail and Outlook drop
 * them outright — and they cannot resolve a relative `/storage/...` path,
 * because the message is read on a mail server, not on this site. So an image
 * in an email has to be a file at a public absolute URL, uploaded before the
 * message is composed rather than embedded in the body.
 *
 * TinyMCE posts here through `images_upload_handler` and expects `{location}`
 * back; anything else it treats as a failed upload.
 *
 * @see \App\Http\Controllers\Admin\CampaignController
 * @see \App\Http\Controllers\Admin\EmailTemplateController
 */
class EmailImageController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                // Animated GIF and SVG are left out on purpose: Outlook shows only
                // the first GIF frame, and most clients block SVG entirely.
                'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp|max:2048',
            ]);
        } catch (ValidationException $e) {
            // TinyMCE surfaces `message` to the author in its own error notice.
            return response()->json([
                'message' => $e->validator->errors()->first('file'),
            ], 422);
        }

        $path = $request->file('file')->store('email-images', 'public');

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }
}
