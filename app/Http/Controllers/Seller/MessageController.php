<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Services\MessageService;
use Illuminate\Http\Request;

/** The store's side of shopper messages. */
class MessageController extends Controller
{
    public function index(Request $request, MessageService $messages)
    {
        $seller = $request->user()->seller;
        $threads = Conversation::with('buyer:id,name', 'lastMessage')->where('seller_id', $seller->id)->whereHas('messages')->orderByDesc('last_message_at')->limit(50)->get();
        $open = $request->query('c') ? $threads->firstWhere('id', (int) $request->query('c')) : $threads->first();

        if ($open) {
            $messages->markRead($open, 'seller');
            $open->setRelation('messages', $open->messages()->orderBy('id')->limit(200)->get());
        }

        return view('seller.center.messages', ['seller' => $seller, 'threads' => $threads, 'open' => $open,
            'unread' => $threads->mapWithKeys(fn ($t) => [$t->id => $t->unreadFor('seller')->count()])]);
    }

    public function reply(Request $request, MessageService $messages, int $conversation)
    {
        $conversation = Conversation::where('seller_id', $request->user()->seller->id)->findOrFail($conversation);
        $data = $request->validate(['body' => ['required', 'string', 'max:' . MessageService::MAX_LENGTH]]);
        $result = $messages->send($conversation, 'seller', $request->user(), $data['body']);

        return redirect('/seller/messages?c=' . $conversation->id)->with($result === true ? 'status' : 'error', $result === true ? 'Reply sent.' : $result);
    }
}
