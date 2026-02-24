<x-mail::message>
# New contact message notification

Hello,

The system has just received a new contact message from a customer via the website.

## Message details:
**Sender:** {{ $contactMessage->name }}  
**Email:** {{ $contactMessage->email }}  
**Subject:** {{ $contactMessage->subject }}  

### Content:
{{ $contactMessage->message }}

<x-mail::button :url="route('admin.contact-messages.show', $contactMessage->id)">
View details in the admin panel
</x-mail::button>

Best regards,<br>
{{ config('app.name') }}
</x-mail::message>
