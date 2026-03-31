<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ödeme Başlatılamadı</title>
    <style>
        body { margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#f5f5f5; }
        .container { max-width: 720px; margin: 40px auto; padding: 0 16px; }
        .card { background:#fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.08); overflow:hidden; }
        .header { padding: 16px 20px; border-bottom: 1px solid #eee; }
        .header h1 { font-size: 16px; margin:0; }
        .body { padding: 18px 20px; }
        .msg { padding: 12px 14px; border-radius: 10px; background:#FEF2F2; border:1px solid #FECACA; color:#991B1B; }
        .meta { margin-top: 14px; font-size: 13px; color:#666; }
        a { color:#2563EB; text-decoration:none; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <h1>Ödeme Başlatılamadı</h1>
        </div>
        <div class="body">
            <div class="msg">
                {{ $message ?? 'Ödeme başlatılamadı. Lütfen daha sonra tekrar deneyiniz.' }}
            </div>

            @if(!empty($paymentNumber))
                <div class="meta">
                    Ödeme No: <strong>{{ $paymentNumber }}</strong>
                </div>
            @endif

            <div class="meta" style="margin-top:10px;">
                Tekrar denemek için sayfayı yenileyebilirsiniz.
            </div>
        </div>
    </div>
</div>
</body>
</html>

