<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Order;
use App\Bank;

class SiparisAlindiHavale extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
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
        $adsoyad = $this->order->gon_adsoyad;
        $order_number = $this->order->order_number;
        $price_total = $this->order->total + $this->order->shipping;
        $hesap = "";
        $bank = Bank::find($this->order->bank_id);
        if($bank){
            $hesap = $bank->name." IBAN: ".$bank->iban;
        }
        return $this
            ->subject("Banka Havalesi / EFT Siparişiniz Alınmıştır")
            ->markdown('emails.siparis.havale',compact("adsoyad","order_number","price_total","hesap"));
    }
}
