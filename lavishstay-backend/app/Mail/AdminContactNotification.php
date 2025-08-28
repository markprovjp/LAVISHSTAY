<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminContactNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $contactData;

    public function __construct($contactData)
    {
        $this->contactData = $contactData;
    }

    public function build()
    {
        return $this->subject('Yêu cầu liên hệ mới từ ' . $this->contactData['name'])
                    ->replyTo($this->contactData['email'], $this->contactData['name'])
                    ->view('emails.admin-contact-notification')
                    ->with([
                        'name' => $this->contactData['name'],
                        'email' => $this->contactData['email'],
                        'phone' => $this->contactData['phone'],
                        'subject' => $this->contactData['subject'],
                        'messageContent' => $this->contactData['message'],
                    ]);
    }
}
