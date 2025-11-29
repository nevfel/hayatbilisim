# Kullanım Rehberi - Ürün ve Sipariş İşlemleri

## 🔐 Giriş Yapma

Öncelikle sisteme giriş yapmanız gerekmektedir:

1. Ana sayfadan **"Giriş"** butonuna tıklayın
2. E-posta ve şifrenizle giriş yapın
3. E-posta doğrulaması yapmanız gerekebilir

## 🛍️ Ürün Sayfasına Erişim

### Yöntem 1: Navbar'dan
- Giriş yaptıktan sonra üst menüde **"Ürünler"** linkine tıklayın

### Yöntem 2: Direkt URL
- Tarayıcınıza şu adresi yazın: `http://localhost/products` (veya domain adresiniz)

## 📦 Ürünleri Görüntüleme ve Sepete Ekleme

1. **Ürün Listesi** (`/products`)
   - Tüm aktif ürünleri görüntüleyin
   - Ürün adı, açıklama, fiyat ve stok bilgilerini görün
   - "Detay" butonuyla ürün detay sayfasına gidin
   - "Sepete Ekle" butonuyla direkt sepete ekleyin

2. **Ürün Detay** (`/products/{id}`)
   - Ürün hakkında detaylı bilgi görüntüleyin
   - Adet seçin
   - "Sepete Ekle" butonuna tıklayın

## 🛒 Sepet İşlemleri

### Sepete Erişim
- Navbar'dan **"Sepet"** linkine tıklayın
- Veya direkt: `/cart`

### Sepet İşlemleri
- Ürün miktarını artırın/azaltın (+ ve - butonları)
- Ürünü sepetten çıkarın (çöp kutusu ikonu)
- Toplam tutarı görüntüleyin
- "Siparişi Tamamla" butonuna tıklayarak sipariş oluşturma sayfasına gidin

## 📝 Sipariş Oluşturma

### Adım 1: Sepeti Kontrol Edin
- Sepetinizde ürünler olduğundan emin olun
- "Siparişi Tamamla" butonuna tıklayın

### Adım 2: Fatura Bilgilerini Doldurun (`/orders/create`)
**Zorunlu Alanlar:**
- ✅ Ad Soyad
- ✅ E-posta

**Opsiyonel Alanlar:**
- Telefon
- Adres
- Şehir
- Posta Kodu

**Teslimat Bilgileri (Opsiyonel):**
- Farklı bir teslimat adresi belirtmek isterseniz doldurun
- Boş bırakılırsa fatura adresi kullanılır

### Adım 3: Sözleşmeleri Onaylayın
- ✅ Kullanım Şartları ve KVKK Aydınlatma Metni'ni okudum ve kabul ediyorum
- Checkbox'ı işaretleyin

### Adım 4: Siparişi Gönderin
- "Ödemeye Geç" butonuna tıklayın
- Sistem otomatik olarak ödeme sayfasına yönlendirecektir

## 💳 Ödeme İşlemi

### Ödeme Modelleri

Sistem 3 farklı ödeme modeli destekler:

1. **3D Secure** (`payment_model=3d_secure`)
   - Kredi kartı bilgileri sitede alınır
   - 3D Secure doğrulaması yapılır
   - Önerilen model

2. **3D Pay** (`payment_model=3d_pay`)
   - Kullanıcı banka sayfasına yönlendirilir
   - Ödeme banka sayfasında yapılır

3. **3D Host** (`payment_model=3d_host`)
   - Ödeme tamamen banka sayfasında gerçekleşir

### Ödeme Adımları

1. **Ödeme Sayfasına Yönlendirme**
   - Sipariş oluşturduktan sonra otomatik yönlendirilirsiniz
   - Veya manuel: `/payment/{order_id}/initiate?payment_model=3d_secure`

2. **Kart Bilgilerini Girin** (3D Secure için)
   - Kart Numarası
   - Ay/Yıl (Son kullanma tarihi)
   - CVV
   - Kart Üzerindeki İsim

3. **Ödemeyi Tamamlayın**
   - "Ödemeyi Tamamla" butonuna tıklayın
   - 3D Secure doğrulaması için banka sayfasına yönlendirileceksiniz
   - Doğrulama kodunu girin

4. **Sonuç**
   - Başarılı: `/payment/{order_id}/success` sayfasına yönlendirilirsiniz
   - Başarısız: `/payment/{order_id}/failed` sayfasına yönlendirilirsiniz

## 📋 Siparişlerimi Görüntüleme

### Sipariş Listesi
- Navbar'dan **"Siparişlerim"** linkine tıklayın
- Veya direkt: `/orders`
- Tüm siparişlerinizi görüntüleyin
- Sipariş durumunu kontrol edin:
  - 🟡 Beklemede (pending)
  - 🔵 İşleniyor (processing)
  - 🟢 Tamamlandı (completed)
  - 🔴 İptal Edildi (cancelled)

### Sipariş Detayı
- Sipariş listesinden bir siparişe tıklayın
- Veya direkt: `/orders/{order_id}`
- Sipariş kalemlerini görüntüleyin
- Fatura bilgilerini kontrol edin
- Ödeme durumunu görüntüleyin

## 🔗 Hızlı Erişim Linkleri

| İşlem | URL |
|-------|-----|
| Ürünler | `/products` |
| Sepet | `/cart` |
| Sipariş Oluştur | `/orders/create` |
| Siparişlerim | `/orders` |
| Ödeme Başlat | `/payment/{order_id}/initiate?payment_model=3d_secure` |

## ⚠️ Önemli Notlar

1. **Giriş Gereksinimi**: Tüm ürün ve sipariş işlemleri için giriş yapmış olmanız gerekir
2. **Stok Kontrolü**: Stokta olmayan ürünler sepete eklenemez
3. **Sipariş İptali**: Sipariş oluşturulduktan sonra iptal edilemez (sadece durum değişikliği yapılabilir)
4. **Ödeme**: Ödeme işlemi tamamlanmadan sipariş "beklemede" durumunda kalır
5. **Test Kartları**: Kuveyt POS test ortamı için test kartı bilgileri bankadan alınmalıdır

## 🐛 Sorun Giderme

### Ürünler görünmüyor
- Veritabanı seed işlemini çalıştırın: `php artisan db:seed`
- Ürünlerin `is_active=true` olduğundan emin olun

### Sepet boş görünüyor
- Giriş yaptığınızdan emin olun
- Ürünleri sepete eklediğinizi kontrol edin

### Ödeme sayfası açılmıyor
- `.env` dosyasındaki Kuveyt POS bilgilerini kontrol edin
- Route'ların doğru tanımlandığından emin olun

### Callback çalışmıyor
- Callback URL'inin erişilebilir olduğundan emin olun
- SSL sertifikası gerekli olabilir (production'da)

