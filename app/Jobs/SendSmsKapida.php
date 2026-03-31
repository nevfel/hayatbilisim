<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Order;

class SendSmsKapida implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $order;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mesaj = "Sayın ".$this->order->gon_adsoyad.", ".$this->order->order_number." nolu siparişiniz alınmıştır. Lütfen ".config("site.callcenter")." ile görüşerek siparişinizi onaylayınız.";
        return app("App\Repositories\Sms\Izbil")->sms_gonder($this->order->gon_cep,$mesaj,$this->order->order_number);
    }
}
