<x-mail::message>
# {{ $card->recipient_email ? ($from_name ? $from_name . ' sent you a gift card' : 'You have a gift card') : 'Your gift card is ready' }}

**Value:** ${{ number_format((float) $card->amount, 2) }}

**Code:** {{ $card->code }}

@if($card->message)
> {{ $card->message }}
@endif

To use it, sign in, open **Account › Gift cards**, enter the code, and the balance is applied automatically at checkout. It never expires.

<x-mail::button :url="url('/my-account/giftcards')">
Redeem my gift card
</x-mail::button>

Peyton Ghalib
</x-mail::message>
