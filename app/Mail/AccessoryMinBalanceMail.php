<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Setting;

class AccessoryMinBalanceMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($accessory)
    {
        $this->data = $accessory;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $business = Setting::find(1);
        $accessory = $this->data;
        return $this->subject('Accessory Balance Minimum Level Warning!')
                ->from('no-reply@gobyfabrifest.com', env('APP_NAME'))
                ->to($business->email)
                ->view('pages.emails.accessory-min-balance', compact('accessory'));
    }
}
