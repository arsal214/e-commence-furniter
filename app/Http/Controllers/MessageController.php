<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Seller;
use App\Services\MessageService;
use Illuminate\Http\Request;

/** The shopper's side of store messages. */
class MessageController extends Controller
{
    /** Start (or continue) a conversation with a live store. */
    public function start(Request $request, MessageService $messages)
    {
        $data = $request->validate(['to' => ['required', 'string', 'max:120'], 'body' => ['required', 'string', 'max:' . MessageService::MAX_LENGTH]]);
        $seller = Seller::live()->where('slug', $data['to'])->firstOrFail();
        $conversation = $messages->open($request->user(), $seller);
        $result = $messages->send($conversation, 'buyer', $request->user(), $data['body']);

        return $result === true
            ? redirect('/my-account/messages?c=' . $conversation->id)->with('success', 'Message sent.')
            : redirect('/my-account/messages?to=' . $seller->slug)->with('error', $result)->withInput();
    }

    public function reply(Request $request, MessageService $messages, int $conversation)
    {
        $conversation = Conversation::where('buyer_id', $request->user()->id)->findOrFail($conversation);
        $data = $request->validate(['body' => ['required', 'string', 'max:' . MessageService::MAX_LENGTH]]);
        $result = $messages->send($conversation, 'buyer', $request->user(), $data['body']);

        return redirect('/my-account/messages?c=' . $conversation->id)->with($result === true ? 'success' : 'error', $result === true ? 'Message sent.' : $result);
    }
}
