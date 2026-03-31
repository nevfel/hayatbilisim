<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ödeme</title>
    <style>
        body { margin:0; font-family: system-ui, -apple-system, Segoe UI, Roboto, sans-serif; background:#f5f5f5; }
        .container { max-width: 920px; margin: 24px auto; padding: 0 16px; }
        .card { background:#fff; border-radius: 12px; box-shadow: 0 8px 24px rgba(0,0,0,.08); overflow:hidden; }
        .header { padding: 16px 20px; border-bottom: 1px solid #eee; }
        .header h1 { font-size: 16px; margin:0; }
        .body { padding: 0; }
        iframe { width: 100%; border: 0; min-height: 720px; }
        .note { padding: 12px 20px; color:#666; font-size: 13px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="header">
            <h1>PayTR ile Güvenli Ödeme</h1>
        </div>
        <div class="body">
            <iframe id="paytriframe" src="{{ rtrim(config('paytr.iframe_base_url'), '/') }}/{{ $token }}" scrolling="no"></iframe>
        </div>
        <div class="note">
            Ödemeniz başarıyla tamamlandığında sistemimize PayTR bildirim gönderecek ve siparişiniz kesinleşecektir.
        </div>
    </div>
</div>

<script>
    // PayTR iframe yüksekliği mesaj ile güncellenir
    window.addEventListener("message", function (e) {
        if (typeof e.data === "string" && e.data.indexOf("paytriframe") > -1) {
            var iframe = document.getElementById("paytriframe");
            if (!iframe) return;
            var parts = e.data.split(":");
            if (parts.length === 2) {
                iframe.style.height = parts[1] + "px";
            }
        }
    }, false);
</script>
</body>
</html>

