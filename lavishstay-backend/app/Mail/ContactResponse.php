<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactResponse extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $contactData;

    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    public function build()
    {
        return $this->subject('Cảm ơn bạn đã liên hệ với LavishStay')
                    ->view('emails.contact-response')
                    ->with([
                        'name' => $this->contactData['name'],
                        'email' => $this->contactData['email'],
                        'subject' => $this->contactData['subject'],
                        'messageContent' => $this->contactData['message'],
                        'phone' => $this->contactData['phone'],
                    ]);
    }
}
