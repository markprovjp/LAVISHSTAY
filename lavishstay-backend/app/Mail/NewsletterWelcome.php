<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterWelcome extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $couponCode;
    public $email;
    public $unsubscribeUrl;
    public $bookingUrl;

    public function __construct($couponCode, $email, $unsubscribeUrl, $bookingUrl)
    {
        $this->couponCode = $couponCode;
        $this->email = $email;
        $this->unsubscribeUrl = $unsubscribeUrl;
        $this->bookingUrl = $bookingUrl;
    }

    public function build()
    {
        return $this->subject('Chào mừng đến với LavishStay — Mã giảm 20% cho bạn')
                    ->view('emails.newsletter-welcome')
                    ->with([
                        'couponCode' => $this->couponCode,
                        'email' => $this->email,
                        'unsubscribeUrl' => $this->unsubscribeUrl,
                        'bookingUrl' => $this->bookingUrl,
                        'expiryDate' => now()->addDays(90)->format('d/m/Y'),
                    ])
                    ->withSwiftMessage(function ($message) {
                        $message->getHeaders()->addTextHeader('List-Unsubscribe', '<' . $this->unsubscribeUrl . '>');
                    });
    }
}
