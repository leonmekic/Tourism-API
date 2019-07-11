<?php

namespace App\Jobs;

use App\Mail\BookingEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendEmailBookingAcceptedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($booking)
    {
        $this->booking = $booking;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data['note'] = 'Booking for room number ' . $this->booking['room'] . ' has been accepted. Booking period from ' .$this->booking['time_from']. ' to ' . $this->booking['time_to'];

        Mail::to($this->booking['user_email'])->send(new BookingEmail($data));
    }
}
