<x-mail::message>
# New Contact Form Enquiry

You have received a new message from the contact form.

---

**Name:** {{ $data['name'] }}

**Email:** {{ $data['email'] }}

**Phone:** {{ $data['number'] }}

**Subject:** {{ $data['subject'] }}

**Message:**

{{ $data['Message'] }}

---

Thanks,
{{ config('app.name') }}
</x-mail::message>
