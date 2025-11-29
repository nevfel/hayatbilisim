# Kuveyt POS Entegrasyonu Kurulum Rehberi

Bu dokümantasyon, Kuveyt POS 3D ödeme entegrasyonunun kurulumu ve test edilmesi için gerekli adımları içermektedir.

## Ödeme Modeli

Sistem **Kuveyt 3D (MODEL_3D_PAY)** ödeme modelini kullanmaktadır:
- Kullanıcı kart bilgilerini sitemizde girer
- Banka 3D doğrulama sayfasına yönlendirilir
- Doğrulama sonrası callback ile sisteme dönüş yapılır
- Güvenli ve yaygın kullanılan bir yöntemdir

## Gereksinimler

- MEWS POS paketi kurulu (zaten kurulu)
- Kuveyt Türk POS hesabı ve test bilgileri
- Laravel 11.x
- PHP 8.2+
- SOAP extension (ext-soap)

## .env Dosyası Ayarları

`.env` dosyanıza aşağıdaki değişkenleri ekleyin:

```env
# Kuveyt POS Ayarları
KUVEYT_POS_TEST_MODE=true
KUVEYT_POS_MERCHANT_ID=your_merchant_id
KUVEYT_POS_USERNAME=your_username
KUVEYT_POS_CUSTOMER_ID=your_customer_id
KUVEYT_POS_STORE_KEY=your_store_key

# Kuveyt POS Endpoint'leri (Test ortamı için varsayılan değerler)
KUVEYT_POS_PAYMENT_API=https://boatest.kuveytturk.com.tr/boa.virtualpos.services/Home
KUVEYT_POS_GATEWAY_3D=https://boatest.kuveytturk.com.tr/boa.virtualpos.services/Home/ThreeDModelPayGate
KUVEYT_POS_QUERY_API=https://boatest.kuveytturk.com.tr/BOA.Integration.WCFService/BOA.Integration.VirtualPos/VirtualPosService.svc?wsdl
```

## Test Bilgileri

Kuveyt Türk POS test ortamı için test kartları ve bilgileri bankadan alınmalıdır. Genellikle:
- Test kart numaraları
- Test CVV kodları
- Test son kullanma tarihleri

sağlanmaktadır.

## Veritabanı Kurulumu

Migration'ları çalıştırın:

```bash
php artisan migrate
```

Test verilerini yükleyin:

```bash
php artisan db:seed
```

## Kullanım

### 1. Ürün Sayfası
- `/erp-cozumu` - ERP çözümünü görüntüleme ve sepete ekleme

### 2. Sepet
- `/cart` - Sepeti görüntüleme ve düzenleme

### 3. Sipariş Oluşturma
- `/orders/create` - Fatura bilgileri ile sipariş oluşturma

### 4. Ödeme Akışı

#### 4.1. Ödeme Başlatma
```
GET /payment/{order_id}/initiate
```
- Kart bilgileri formu gösterilir
- Kullanıcı kart numarası, son kullanma tarihi, CVV ve kart üzerindeki isim girer

#### 4.2. 3D Doğrulama
```
POST /payment/process-3d
```
- Kart bilgileri Kuveyt POS'a gönderilir
- Kullanıcı banka 3D doğrulama sayfasına yönlendirilir
- SMS ile gelen kodu girerek doğrulama yapar

#### 4.3. Callback
```
POST /payment/callback
```
- Banka doğrulama sonucunu callback URL'ine gönderir
- Sistem ödeme sonucunu kontrol eder ve kaydeder
- Kullanıcı başarı veya hata sayfasına yönlendirilir

#### 4.4. Sonuç Sayfaları
```
GET /payment/{order_id}/success - Başarılı ödeme
GET /payment/{order_id}/failed  - Başarısız ödeme
```

## Sözleşme Sayfaları

- `/kvkk` - KVKK Aydınlatma Metni
- `/terms` - Kullanım Şartları
- `/privacy` - Gizlilik Politikası

## Test Senaryosu

### Tam Ödeme Akışı Testi

1. **Ürün Sayfasını Ziyaret Edin**
   - `/erp-cozumu` adresine gidin
   - ERP paketini inceleyin ve hizmetleri seçin

2. **Sepete Ekleyin**
   - Seçtiğiniz hizmetlerle ürünü sepete ekleyin
   - `/cart` sayfasında sepeti kontrol edin

3. **Sipariş Oluşturun**
   - `/orders/create` adresine yönlendirin
   - Fatura bilgilerini doldurun (ad, email, telefon, adres vb.)
   - Siparişi oluşturun

4. **Ödeme Bilgilerini Girin**
   - Otomatik olarak `/payment/{order_id}/initiate` sayfasına yönlendirileceksiniz
   - Test kart bilgilerini girin:
     - Kart numarası: Test kartı numarası (Kuveyt'ten alınmalı)
     - Son kullanma: Gelecek bir tarih (örn: 12/25)
     - CVV: 123
     - Kart sahibi: TEST USER

5. **3D Doğrulama**
   - "Ödemeyi Tamamla" butonuna tıklayın
   - Kuveyt banka 3D doğrulama sayfasına yönlendirileceksiniz
   - Test SMS kodunu girin (test ortamında genellikle sabit bir kod)

6. **Sonuç Kontrolü**
   - Başarılı ödeme: `/payment/{order_id}/success` sayfasına yönlendirilir
   - Başarısız ödeme: `/payment/{order_id}/failed` sayfasına yönlendirilir
   - Sipariş ve ödeme kayıtları veritabanında kontrol edilebilir

## Önemli Notlar

### Güvenlik
- **Production ortamında SSL sertifikası zorunludur** (HTTPS)
- Test ortamında HTTP kullanılabilir
- Kart bilgileri asla veritabanında saklanmaz
- Tüm ödeme işlemleri loglanır (`payments` tablosu)

### Teknik Gereksinimler
- PHP SOAP extension aktif olmalıdır (`ext-soap`)
- Callback URL'i internetten erişilebilir olmalıdır
- Test ortamında ngrok gibi tunnel servisleri kullanılabilir

### Durum Kodları
**Sipariş Durumları:**
- `pending` - Ödeme bekleniyor
- `processing` - Ödeme alındı, işleniyor
- `completed` - Tamamlandı
- `cancelled` - İptal edildi

**Ödeme Durumları:**
- `pending` - Ödeme bekleniyor
- `success` - Başarılı
- `failed` - Başarısız
- `cancelled` - İptal edildi
- `refunded` - İade edildi

### Misafir Kullanıcılar
- Giriş yapmadan sipariş verilebilir
- Başarı/hata sayfaları misafir kullanıcılar için optimize edilmiştir
- Sipariş detayları email ile gönderilir

## Sorun Giderme

### SOAP Extension Hatası
Eğer SOAP hatası alıyorsanız, PHP'de SOAP extension'ının aktif olduğundan emin olun:
```bash
php -m | grep soap
```

Eğer SOAP yüklü değilse:
```bash
# Ubuntu/Debian
sudo apt-get install php-soap

# macOS (Homebrew)
brew install php@8.2
```

### Environment Variables Hatası
`.env` dosyanızda Kuveyt POS bilgilerinin doğru yapılandırıldığından emin olun:
```bash
php artisan config:clear
php artisan config:cache
```

### Callback Erişim Hatası
Callback URL'i internetten erişilebilir olmalıdır. Yerel geliştirme için:

**Ngrok Kullanarak:**
```bash
ngrok http 8000
# Ngrok URL'ini .env'de APP_URL olarak ayarlayın
```

**Veya Laravel Sail ile:**
```bash
./vendor/bin/sail share
```

### "Kart Bilgileri Geçersiz" Hatası
- Kart numarasının doğru formatta olduğundan emin olun
- Yıl 4 haneli (YYYY) ve ay 2 haneli (MM) olmalı
- CVV 3-4 haneli olmalı
- Test ortamı için test kart bilgilerini kullandığınızdan emin olun

### Veritabanı Kontrolleri
Ödeme kayıtlarını kontrol etmek için:
```bash
php artisan tinker
>>> \App\Models\Payment::latest()->first()
>>> \App\Models\Order::latest()->first()
```

### Log Kontrolleri
Hata ayıklama için Laravel loglarını kontrol edin:
```bash
tail -f storage/logs/laravel.log
```

