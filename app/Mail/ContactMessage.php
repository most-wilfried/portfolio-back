<?php

namespace App\Mail;

use App\Models\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;

    public Message $contact;

    public function __construct(Message $contact)
    {
        $this->contact = $contact;
    }

    public function build()
    {
        return $this->subject('Nouveau message de portfolio')
            ->view('emails.contact-message')
            ->with([
                'contact' => $this->contact,
            ]);
    }
}
