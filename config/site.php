<?php
return [
    "callcenter" => env("SITE_CALLCENTER", ""),
    "whatsapp" => env("SITE_WHATSAPP", ""),
    "company"   => env("SITE_COMPANY", "Hayat Bilisim"),
    "email" => env("SITE_EMAIL", "info@hayatbilisim.com"),
    "name" => "Hayat Bilisim",
    "firma" => env("SITE_FIRMA", "Hayat Bilisim"),
    "adres" => env("SITE_ADRES", ""),
    "gtm_id" => env("GTM_ID"),
    "google_tracking_id" => env("GOOGLE_TRACKING_ID"),
    // kuveytpos | paytr
    "payment_provider" => env("PAYMENT_PROVIDER", "kuveytpos"),

    // Quick payment link creation API key (optional but recommended)
    "quick_payment_api_key" => env("QUICK_PAYMENT_API_KEY", ""),
];
