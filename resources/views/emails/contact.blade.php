<x-mail::message>
# New Contact Form Enquiry

You have received a new message from the contact form.

---

**Name:** {{ $data['name'] }}

**Email:** {{ $data['email'] }}

@if(! empty($data['number']))
**Phone:** {{ $data['number'] }}

@endif
**Subject:** {{ $data['subject'] }}

**Message:**

{{ $data['Message'] }}

---

Thanks,
{{ config('app.name') }}
</x-mail::message>
