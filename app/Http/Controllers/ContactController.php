<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function sendMessage(Request $request)
    {
        // Form validasyonu
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Lütfen tüm alanları doğru şekilde doldurun.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // E-posta gönderimi
            $contactEmail = config('mail.contact.address');
            
            Mail::raw($request->message, function ($message) use ($request, $contactEmail) {
                $message->to($contactEmail)
                    ->subject('Web Sitesinden Yeni Mesaj - ' . $request->name)
                    ->replyTo($request->email, $request->name);
            });

            return response()->json([
                'success' => true,
                'message' => 'Mesajınız başarıyla gönderildi!'
            ]);

        } catch (\Exception $e) {
            Log::error('E-posta gönderim hatası: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Mesaj gönderilirken bir hata oluştu. Lütfen tekrar deneyin.'
            ], 500);
        }
    }
}
