<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Mail;
use App\Mail\SiparisAlindiHavale;
use App\Mail\SiparisAlindiKredi;
use App\Mail\SiparisAlindiKapida;
use App\Jobs\SendSmsHavale;
use App\Jobs\SendSmsKredi;
use App\Jobs\SendSmsKapida;
use App\Jobs\Irsaliyelestir;
use App\Coupon;
use Cart;

class TamamlaRepository
{
    public function tamamla($order)
    {
        if(!$order){
            return redirect("/")->with("warning","Siparişinizde hata olduğunu düşünüyorsanız lütfen çağrı merkezimizi arayınız..");
        }

        if(session()->has("coupon_data"))
        {
            Coupon::where("code",session("coupon_data")['coupon_code'])->first()->increment("num_uses",1);
        }

        if($order->bilgilendirme_sms)
        {
            switch($order->payment){
                case "paytr":
                case "stripe":
                    dispatch(new SendSmsKredi($order));
                    break;
                case "havale":
                    dispatch(new SendSmsHavale($order));
                    break;
                case "kapida":
                    dispatch(new SendSmsKapida($order));
                    break;
            }
        }
        if($order->bilgilendirme_email)
        {
            switch($order->payment){
                case "paytr":
                case "kredi":
                case "stripe":
                    Mail::to($order->gon_email,$order->gon_adsoyad)
                        ->bcc(config("site.email"))
                        ->queue(new SiparisAlindiKredi($order));
                    break;
                case "havale":
                    Mail::to($order->gon_email,$order->gon_adsoyad)
                        ->bcc(config("site.email"))
                        ->queue(new SiparisAlindiHavale($order));
                    break;
                case "kapida":
                    Mail::to($order->gon_email,$order->gon_adsoyad)
                        ->bcc(config("site.email"))
                        ->queue(new SiparisAlindiKapida($order));
                    break;
            }
        }

        if($order->payment_ok == 1)
        {
            dispatch(new Irsaliyelestir($order));
        }
        Cart::destroy();
        session()->forget("order_id");
        session()->forget("coupon_data");
        session()->put("siparis_tamam",1);
        session()->save();
        return 1;
    }
}
