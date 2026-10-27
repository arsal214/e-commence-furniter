<x-mail::message>
# {{ $message->from === 'buyer' ? ($conversation->buyer->name . ' wrote to your store') : ($conversation->seller->store_name . ' replied') }}

> {{ \Illuminate\Support\Str::limit($message->body, 400) }}

<x-mail::button :url="$message->from === 'buyer' ? url('/seller/messages?c=' . $conversation->id) : url('/my-account/messages?c=' . $conversation->id)">
Open the conversation
</x-mail::button>

Reply from your account so everything stays in one place. Peyton Ghalib will never ask you to pay or share card details by message.
</x-mail::message>
