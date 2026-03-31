<?php

return [
    'merchant_id' => env('PAYTR_MERCHANT_ID', ''),
    'merchant_key' => env('PAYTR_MERCHANT_KEY', ''),
    'merchant_salt' => env('PAYTR_MERCHANT_SALT', ''),

    // Kullanıcının döneceği sayfalar (opsiyonel). Boşsa route() ile üretilir.
    'success_url' => env('PAYTR_SUCCESS_URL', ''),
    'failure_url' => env('PAYTR_FAILURE_URL', ''),

    // PayTR tarafında mağaza canlı olsa bile test işlem açmak için 1
    'test_mode' => (int) env('PAYTR_TEST_MODE', 0),
    'debug_on' => (int) env('PAYTR_DEBUG_ON', 0),

    // 1 = taksit kapalı, 0 = taksit açık
    'no_installment' => (int) env('PAYTR_NO_INSTALLMENT', 0),
    // 0 = PayTR default, 1-12 arası sınır
    'max_installment' => (int) env('PAYTR_MAX_INSTALLMENT', 0),

    // TL | USD | EUR | GBP | RUB
    'currency' => env('PAYTR_CURRENCY', 'TL'),

    // API endpoint
    'token_url' => env('PAYTR_TOKEN_URL', 'https://www.paytr.com/odeme/api/get-token'),
    'iframe_base_url' => env('PAYTR_IFRAME_BASE_URL', 'https://www.paytr.com/odeme/guvenli/'),
];

