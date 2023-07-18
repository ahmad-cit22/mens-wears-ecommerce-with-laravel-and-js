<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;
use Auth;

class OrderNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $order = $this->order;
        $pdf = PDF::loadView('admin.invoice.generate', compact('order'));
        return $this->subject('Congratulations! You have a new order.')
                ->from('no-reply@gobyfabrifest.com', env('APP_NAME'))
                ->to('gobyfabrifest@gmail.com', $order->name)
                ->view('pages.emails.order-confirm', compact('order'))
                ->attachData($pdf->output(), 'invoice.pdf', [
                    'mime' => 'application/pdf',
                ]);
    }
}
