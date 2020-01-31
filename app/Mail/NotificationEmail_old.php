<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $email;
    public function __construct($email)
    {
        $this->email = $email;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('FaktoryMRP@faktory.com')
        ->subject('New Purchase Order Alert :: ' . $this->purchaseOrder->po_number)
        ->markdown('mail/SendPurchaseOrder')->with([
            'purchaseOrder' => $this->purchaseOrder,
            'purchaseListings' => $this->purchaseListings,
            'supplier' => $this->supplier,
            'created_user' => $this->created_user,
            'warehouse' => $this->warehouse,
        ]);
        return $this->view('view.name');
    }
}
