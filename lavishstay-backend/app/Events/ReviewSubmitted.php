<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ReviewSubmitted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $review;
    public $customer;
    public $booking;
    public $rating;

    public function __construct($review, $customer, $booking, $rating)
    {
        $this->review = $review;
        $this->customer = $customer;
        $this->booking = $booking;
        $this->rating = $rating;
    }
}

class NegativeReviewReceived
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $review;
    public $customer;
    public $booking;
    public $rating;
    public $comment;

    public function __construct($review, $customer, $booking, $rating, $comment = null)
    {
        $this->review = $review;
        $this->customer = $customer;
        $this->booking = $booking;
        $this->rating = $rating;
        $this->comment = $comment;
    }
}