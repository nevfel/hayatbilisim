Kuveyt Türk Katılım Bankası 2024
Sanal Pos
Entegrasyon
Dokümanı
3D Secure ModelKuveyt Türk Katılım Bankası 2024 Sayfa 1
İçindekiler Tablosu
Kuveyt Türk Sanal Pos (Freepos) Hakkında ................................................................................................... 2
1. Güvenli Ödeme Nedir? .............................................................................................................................. 2
2. Güvenli Ödeme Nasıl Çalışır?..................................................................................................................... 2
3. Güncellemeler ........................................................................................................................................... 3
3.1. 3D Secure 2.0 Geçişi .................................................................................................................. 3
3.1.1. Satış İşlemi Mesaj Örneği – Kart Doğrulanması (Request 1) ............................................. 3
3.1.2. Satış İşlemi Onay Mesaj Örneği – Ödeme Alınması (Request 2) ....................................... 4
3.2. URL Güncellemesi...................................................................................................................... 5
3.3. iFrame Yapısının Engellenmesi .................................................................................................. 5
4. İşlemde Kullanılan Zorunlu Parametreler Ve Açıklamaları.................................................................... 5
4.1. Banka Tarafından Sağlanacak Bilgiler................................................................................................. 7
4.2. Entegrasyon İçin Kullanılan Diğer Bilgiler ........................................................................................... 7
4.3. Banka Tarafında Dönen Cevap Bilgileri .............................................................................................. 9
5. Entegrasyon............................................................................................................................................. 10
5.1. Kullanılan Mesaj Yapıları .................................................................................................................. 11
5.1.1. Satış İşlemi Mesaj Örneği – Kart Doğrulaması (Request 1) ....................................................... 11
5.1.2.Satış İşlemi Mesaj Örneği Banka Cevabı (Response 1) ............................................................... 12
5.1.3. Satış İşlemi Onay Mesaj Örneği – Ödeme Alınması (Request 2) ............................................... 13
5.1.4.Ödeme İşlem Sonucu Mesaj Yapısı (Response 2)....................................................................... 13
5.2. Tarım İşyeri ....................................................................................................................................... 14
5.2.1. Satış İşlemi Mesaj Örneği – Kart Doğrulaması (Request 1) – Tarım İşyeri................................. 14
5.2.2.Satış İşlemi Mesaj Örneği Banka Cevabı (Response 1) - Tarım İşyeri ......................................... 15
5.2.3. Satış İşlemi Onay Mesaj Örneği – Ödeme Alınması (Request 2) - Tarım İşyeri ......................... 16
5.2.4.Ödeme İşlem Sonucu Mesaj Yapısı (Response 2) - Tarım İşyeri................................................. 17
6. HashData Hesaplamaları ......................................................................................................................... 18
7. Örnek Kod Parçaları (PHP).....................................................................Hata! Yer işareti tanımlanmamış.
7.1. Ödeme İşlemi Yapılması (Kart Doğrulaması) ..................................Hata! Yer işareti tanımlanmamış.
7.2. İşlem Sonucunun Alınması .............................................................Hata! Yer işareti tanımlanmamış.
7.3. Ödeme İşleminin Onaylanması.......................................................Hata! Yer işareti tanımlanmamış.
8. Sanal Pos Güvenli Ödeme Noktası Adresleri (Güncel)............................................................................. 19
9. Test Ortam Bilgileri.................................................................................................................................. 19Kuveyt Türk Katılım Bankası 2024 Sayfa 2
Kuveyt Türk Sanal Pos (Freepos) Hakkında
E-ticaret sitelerinde yapılan alışverişlerin gerçekleşmesinde kullanılan POS sistemidir. VPOS (Virtual POS)
olarak da isimlendirilen bu sistem kredi kartı vasıtasıyla internet üzerinden alışveriş yapma imkânı sağlar.
Siteniz vasıtasıyla alışveriş yapan müşteriniz; kredi kartı bilgilerinin sitedeki ödeme ekranlarına girer, bu
bilgiler ilgili bankaya ulaşır ve işlemin provizyonu (Onay) alınır. Provizyonu alınmış işlemlerin tutarı ilgili
müşterinin kredi kartı limitinden düşerek çalıştığınız banka hesabına geçirilir. Web site üzerinden çalışan
on-line bir POS’tur.
1. Güvenli Ödeme Nedir?
TROY, VISA ve MasterCard’ın dahil olduğu internet üzerinden gerçekleştirilen ödeme işlemlerinde daha
güvenilir bir ortam sağlayan sistemdir.
3D Secure ve GO sistemleri kullanıcı, üye işyeri ve banka arasındaki sorumlulukları da düzenlemektedir.
3D Secure sistemi uygulanan ödeme işlemlerinde kullanıcıdan kart bilgileri alındıktan sonra, kullanıcı
bankanın 3D Secure veya GO ödeme sayfasına yönlendirilmektedir. Burada kullanıcı banka tarafından
kendisine gönderilen kısa mesajda yer alan şifreyi girer, böylece kart doğrulanmış ve yapılan işlem
kullanıcı tarafından imzalanmış olmaktadır.
Kuveyt Türk Katılım Bankası tarafından sunulan Sanal Pos servisi 3D Secure ve GO ödeme işlemlerini
desteklemektedir.
2. Güvenli Ödeme Nasıl Çalışır?
Üye işyerlerinin 3D Secure sistemini uygulayabilecekleri birçok yöntem bulunmaktadır. 3D Model bu
yöntemlerden birisidir. Bu yöntem 3D Secure sisteminin faydaların tümünü sağlamaktadır. Bu yöntem
ayrıca; üye işyerine kart doğrulama, kart doğrulaması sonrasında işlemi tekrar kontrol edebilme ve uygun
olması durumunda provizyon işlemine onay verebilme imkanı sağlamaktadır. 3D Model uygulanmış tipik
bir ödeme işlemi aşağıdaki temel adımlarla gerçekleşmektedir.
• Kullanıcının karta ait bilgileri girmesi,
• Kart sahibi banka tarafından kartın doğrulanması (kullanıcının gelen sms şifre ile işlemi onaylaması),
• Doğrulama sonucunun üye işyeri tarafından kontrol edilmesi,
• Ödemenin gerçekleştirilmesi.
Bu adımların uygulanmasına dair teknik bilgiler daha sonraki bölümlerde yer almaktadır.Kuveyt Türk Katılım Bankası 2024 Sayfa 3
3. Güncellemeler
3.1. 3D Secure 2.X Sonrası Güncellemeler
3D Secure doğrulama altyapısı değiştiği için ödeme şemaları kuralları gereği, işyerlerinin 3D Secure 2.X
geçişi ile birlikte işlem isteklerindeki yeni alanların göndermesi beklenmektedir. İşlem isteklerinde
gönderilmesi gereken yeni bilgilerin listesi aşağıda verilmiştir. Bu bilgilere ait detaylı açıklamalar 4.
İşlemlerde Kullanılan Zorunlu Parametreler ve Açıklamaları başlığında yer almaktadır.
▪ CardHolderName
▪ DeviceChannel
▪ ClientIP
▪ BillAddrCity
▪ BillAddrCountry
▪ BillAddrLine1
▪ BillAddrPostCode
▪ BillAddrState
▪ Email
▪ Cc
▪ Subscriber
3.1.1. Satış İşlemi Mesaj Örneği – Kart Doğrulanması (Request 1)
Post URL: https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelPayGate
<KuveytTurkVPosMessage xmlns:xsi=http://www.w3.org/2001/XMLSchemainstance
xmlns:xsd="http://www.w3.org/2001/XMLSchema">
<APIVersion>TDV2.0.0</APIVersion>
<OkUrl>http://localhost/php//ThreeDModetest/Approval.php</OkUrl>
<FailUrl>http://localhost/php//ThreeDModetest/Fail.php</FailUrl>
<HashData>TJcp1k5UUT/TSa5X2m0+82E9I/o=</HashData>
<MerchantId>496</MerchantId>
<CustomerId>400235</CustomerId>
<DeviceData>
<DeviceChannel> 02</DeviceChannel>
<ClientIP>12.22.156.43</ClientIP>
</DeviceData>
<CardHolderData>
<BillAddrCity>İstanbul</BillAddrCity>
<BillAddrCountry>792</BillAddrCountry>
<BillAddrLine1>XXX Mahallesi XXX Caddesi No 55 Daire 1</BillAddrLine1>
<BillAddrPostCode>34000</BillAddrPostCode>
<BillAddrState>40</BillAddrState>Kuveyt Türk Katılım Bankası 2024 Sayfa 4
<Email>xxxxx@gmail.com</Email>
<MobilePhone>
<Cc>90</Cc>
<Subscriber>1234567899</Subscriber>
</MobilePhone>
</CardHolderData>
<UserName>apitest</UserName>
<CardNumber>****************</CardNumber>
<CardExpireDateYear>**</CardExpireDateYear>
<CardExpireDateMonth>**</CardExpireDateMonth>
<CardCVV2>***</CardCVV2>
<CardHolderName>Test </CardHolderName>
<CardType>Troy</CardType>
<TransactionType>Sale</TransactionType>
<InstallmentCount>0</InstallmentCount>
<Amount>500</Amount>
<DisplayAmount>500</DisplayAmount>
<CurrencyCode>0949</CurrencyCode>
<MerchantOrderId>20201221</MerchantOrderId>
<TransactionSecurity>3</TransactionSecurity>
</KuveytTurkVPosMessage>
3.1.2. Satış İşlemi Onay Mesaj Örneği – Ödeme Alınması (Request 2)
Post URL: https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelProvisionGate
<KuveytTurkVPosMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema">
<APIVersion>TDV2.0.0</APIVersion>
<HashData>ANcybxW/c1G39+RMstZ3ROYakO8=</HashData>
<MerchantId>496</MerchantId>
<CustomerId>400235</CustomerId>
<UserName>apitest</UserName>
<TransactionType>Sale</TransactionType>
<InstallmentCount>0</InstallmentCount>
<Amount>100</Amount>
<MerchantOrderId>20201221</MerchantOrderId>
<TransactionSecurity>3</TransactionSecurity>
<KuveytTurkVPosAdditionalData>
<AdditionalData>
<Key>MD</Key>
<Data>BUdzLdTOnnrFP1/q1C/EgfrVxHvdvDtx2MuEusn+lB9My5kmogvb4JreoUYD1++6</Data>
</AdditionalData>Kuveyt Türk Katılım Bankası 2024 Sayfa 5
</KuveytTurkVPosAdditionalData>
</KuveytTurkVPosMessage>
3.2. URL Güncellemesi
3D Secure ödeme akışında ödemeler, kart doğrulama ve ödeme alma olarak iki adımda
tamamlanmaktadır. Mevcut adreslerin kullanımı 2024 yılı içerisinde kapatılacaktır.
Mevcut Adresler
1. Sanal Pos Güvenli Ödeme Noktası – Kart Doğrulanması (Production)
https://boa.kuveytturk.com.tr/sanalposservice/Home/ThreeDModelPayGate
2. Sanal Pos Güvenli Ödeme Onaylama – Ödeme Alınması (Production)
https://boa.kuveytturk.com.tr/sanalposservice/Home/ThreeDModelProvisionGate
Yeni Adresler
1. Sanal Pos Güvenli Ödeme Noktası – Kart Doğrulanması (Production):
https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelPayGate
2. Sanal Pos Güvenli Ödeme Onaylama - Ödeme Alınması (Production):
https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelProvisionGate
3.3. iFrame Yapısının Engellenmesi
Yeni url adreslerimizin kullanımı ile birlikte kart doğrulamasında iframe yapısı güvenlik gerekçesi ile
31.12.2022 tarihinde sonlandırılmıştır. Iframe kullanılması durumunda hata alınacaktır.
4. İşlemde Kullanılan Zorunlu Parametreler Ve Açıklamaları
Bu bölümde üye işyeri ve Sanal Pos servisi arasında gerçekleştirilecek entegrasyon için bilgiler yer
almaktadır. Üye işyerinin 3D Model ödeme uygulayabilmesi için aşağıdaki bilgilere sahip olması
gerekmektedir.
Alan Adı
Z: Zorunlu,
O: Opsiyonel,
K: Kullanılmıyor
Tip
KuveytTurkVPosMessage(Başlangıç Tag)
APIVersion O
OkUrl Z Text
FailUrl Z Text
HashData Z
MerchantId Z Tamsayı
CustomerId Z Tamsayı
UserName Z TextKuveyt Türk Katılım Bankası 2024 Sayfa 6
CardNumber Z Text
CardExpireDateYear Z Text
CardExpireDateMonth Z Text
CardCVV2 Z Text
CardHolderName Z Text
CardType O Text
BatchID O Tamsayı
TransactionType Z Text
InstallmentCount Z Tamsayı
DeferringCount O Tamsayı
InstallmentMaturityCommisionFlag O Tamsayı
Amount Z Tamsayı
DisplayAmount Z Tamsayı
FECAmount O Tamsayı
CurrencyCode Z Text
MerchantOrderId O Text
QryId K Tamsayı
DebtId K Tamsayı
SurchargeAmount K Tamsayı
SGKDebtAmount K Tamsayı
TransactionSecurity Z Tamsayı
TransactionSide O Text
PaymentId O Tamsayı
OrderPOSTransactionId O Tamsayı
TranDate O Tarih
EntryGateMethod O Text
DeviceChannel Z Text
ClientIP Z Text
BillAddrCity Z Text
BillAddrCountry Z Text
BillAddrLine1 Z Text
BillAddrPostCode Z Text
BillAddrState Z Text
Email Z Text
Cc Z Text
Subscriber Z TextKuveyt Türk Katılım Bankası 2024 Sayfa 7
4.1. Banka Tarafından Sağlanacak Bilgiler
CustomerId (Müşteri Numarası): Üye işyerinin Kuveyt Türk'teki Sanal Pos’un bağlı hesaba ait müşteri
numarasıdır.
MerchantId (Mağaza Numarası): Üye işyerinin Kuveyt Türk Sanal Pos servisinde kayıtlı özel mağaza
numarasıdır. Başvuru onay mailinde üye işyerine gönderilmektedir.
PostURL (Ödeme Noktası Adresi): Sanal Pos'un sunduğu ödeme yöntemine ait adreslerdir. Ödeme
işlemine ait bilgiler bu adrese gönderilecektir.
• Sanal Pos Güvenli Ödeme Noktası (Kart Doğrulaması):
https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelPayGate
• Sanal Pos Güvenli Ödeme Onaylama (Ödeme Alınması):
https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelProvisionGate
4.2. Entegrasyon İçin Kullanılan Diğer Bilgiler
APIVersion (Uygulama Versiyonu): Uygulama versiyonunu ifade etmektedir. Eylül 2022’den itibaren 3D
Secure 2.0 geçişi ile TDV2.0.0 gönderilmelidir.
OkUrl (Başarılı İşlem Adresi): Güvenli Ödeme işlemlerinde kartın doğrulanması aşamasında, kullanıcı bir
başka sayfaya yönlendirilmektedir (ACS veya TroyGo). Bu aşamada işlemler arasında bağlantı
bulunmamaktadır. Bu nedenle işlemlere ait sonuçlar üye işyerinin belirleyeceği bir sayfaya
gönderilmektedir. 3D Secure ve GO ile kart doğrulaması başarılı gerçekleştiğinde “Kart Doğrulandı.”
sonucunun gönderileceği adrestir. (& içeremez. &amp; şeklinde tanımlanmalıdır.)
FailUrl (Başarısız İşlem Adresi): Kart doğrulaması başarısız olan işlemlerde veya parametrelere bağlı
olarak alınabilecek hatalarda sonucun gönderileceği adrestir. Üye işyerinin belirleyeceği bir sayfaya
gönderilmektedir. (& içeremez. &amp; şeklinde tanımlanmalıdır.)
HashData (Doğrulama Anahtarı): Ödeme işlemine ait bilgilerin doğruluğunun kontrol edilmesi için kullanılan
veridir. Daha sonra sağlanacak algoritma ile üretilecek bu veri, Sanal Pos tarafında da üretilerek bu veriler
karşılaştırılmaktadır. Bu kontrolden geçemeyen ödemeler gerçekleştirilmemektedir.
CardNumber (Kart Numarası): Ödemeyi yapacak kişiye ait kartın üzerindeki 16 haneli numaradır, 16 karakter
olacak ve aralarında boşluk/sembol olmayacak şekilde gönderilmelidir.
CardExpireDateYear (Kart Son Kullanım Yılı): Kartın ön yüzünde bulunan son kullanma tarihine ait yıl bilgisi,
2 karakter olarak gönderilmelidir. (Örneğin; 2025 için 25)
CardExpireDateMonth (Kart Son Kullanım Ayı): Kartın ön yüzünde bulunan son kullanma tarihine ait ay
bilgisi, 2 karakter olarak gönderilmelidir. (Örneğin; Ocak ayı için 01)
CardCVV2: Kartın arka yüzündeki 3 haneli güvenlik kodudur. 3 karakter olarak gönderilmelidir.Kuveyt Türk Katılım Bankası 2024 Sayfa 8
CardHolderName (Kart Sahibinin Adı Soyadı) : Kartın ön yüzünde yer alan adı ve soyadı bilgisi
gönderilmelidir.
CardType (Kart Türü) : TROY, VISA veya MasterCard olarak gönderilmelidir. Default bir değer kullanılabilir.
(Örneğin; VISA)
BatchID (Günsonu Numarası): Satış işleminde sabit değer 0 gönderilmelidir.
TransactionType (İşlem Türü) : Sabit değer olarak “Sale” gönderilmelidir.
InstallmentCount (Taksit Sayısı): Taksit sayısı bu alana girilmelidir, taksit yapma yetkiniz bulunmuyorsa 0
olarak gönderilmelidir. Tarım işyeri olanlar harcama öteleme yapacağı zaman DeferringCount (Öteleme
Ay Sayısı) parametresini kullanmalıdır. Gerekli taksit tanımlamaları için şube ile görüşülmelidir.
DeferringCount (Öteleme Ay Sayısı): Sanal POS üzerinden gönderilen ay sayısı kadar işlemlerin yazılan ay
kadar ötelenmesini sağlayan bilgidir. Tarım işyerlerinden Tohum Kart (547564 ile başlayan) ile harcama
öteleme için kullanılmaktadır. Sadece tarım işyerleri için zorunlu tutulmaktadır. Tarım işyeri eğer
harcama öteleme değil taksit yapmak istiyorsa sadece InstallmentCount (Taksit Sayısı) parametresinde
taksit sayısını göndermelidir. Gerekli tanımlamalar için şube ile görüşülmelidir.
Amount (Tutar): İşlem tutarının girildiği alandır. Bu alanda hiçbir noktalama işareti kullanılmadan ve
gerçek tutarın 100 katı gönderilmelidir. (Örneğin; 102.65 TL için 10265, 1 TL için 100)
DisplayAmount (Tutar Görünümü): Tutar (Amount) değeri ile aynı olacak şekilde gönderilmelidir.
CurrencyCode (Para Kodu): Para birimidir, “0949” olarak gönderilmesi gerekmektedir. Sanal posla yalnızca TL
ile işlem alınmaktadır.
MerchantOrderId (Üye İşyeri Sipariş Numarası): İşlemin üye işyeri tarafında yer alan numarasıdır. Bu alana
işyeri istediği sabit veya değişken değer atayabilmektedir.
TransactionSecurity (İşlem Güvenliği): İşlemin yapılacağı güvenlik türü bilgisidir. Güvenli Ödeme işlemleri için
sabit değer 3 gönderilmelidir.
UserName (Api Kullanıcı Adı): https://kurumsal.kuveytturk.com.tr adresine login olarak kullanıcı işlemleri
sayfasında API rolünde oluşturulan kullanıcı adıdır. (Yönetim - Kullanıcı İşlemleri- Kullanıcı Ekleme)
Maksimum 10 karakter uzunluğunda, harf veya rakamdan oluşturulmalıdır. Türkçe karakter
kullanılmamalıdır.
Password (Api Şifresi): https://kurumsal.kuveytturk.com.tr adresine login olarak kullanıcı işlemleri
sayfasında oluşturulan API kullanıcısının şifre bilgisidir.
CardHolderName: Kart sahibi adı- soyadı bilgisi. 2-45 karakter uzunluğunda olmalıdır.
DeviceChannel : DeviceData alanı içerisinde gönderilmesi beklenen işlemin yapıldığı cihaz bilgisi. 2 karakter
olmalıdır. 01-Mobil, 02-Web Browser için kullanılmalıdır.Kuveyt Türk Katılım Bankası 2024 Sayfa 9
ClientIP : DeviceData alanı içerisinde gönderilmesi beklenen kart sahibinin işleme geldiği IP adres
bilgisi. IPv4 adresi, ondalık sayılar nokta ile ayrılmış 4 grup biçiminde temsil edilir. Her setteki ondalık sayı
0-255 aralığındadır. Örnek IPv4 adresi: 1.12.123.255
BillAddrCity : CardHolderData alanı içerisinde gönderilmesi beklenen ödemede kullanılan kart ile ilişkili
kart hamilinin fatura adres şehri. Maksimum 50 karakter uzunluğunda olmalıdır.
BillAddrCountry: CardHolderData alanı içerisinde gönderilmesi beklenen ödemede kullanılan kart ile
ilişkili kart hamilinin fatura adresindeki ülke kodu. Maksimum 3 karakter uzunluğunda olmalıdır. ISO
3166-1 sayısal üç haneli ülke kodu standardı kullanılmalıdır.
BillAddrLine1 : CardHolderData alanı içerisinde gönderilmesi beklenen ödemede kullanılan kart ile ilişkili
kart hamilinin teslimat adresinde yer alan sokak vb. bilgileri içeren açık adresi. Maksimum 150 karakter
uzunluğunda olmalıdır.
BillAddrPostCode: CardHolderData alanı içerisinde gönderilmesi beklenen ödemede kullanılan kart ile
ilişkili kart hamilinin fatura adresindeki posta kodu.
BillAddrState: CardHolderData alanı içerisinde gönderilmesi beklenen ödemede kullanılan kart ile ilişkili
kart hamilinin fatura adresindeki il veya eyalet bilgisi kodu. ISO 3166-2'de tanımlı olan il/eyalet kodu
olmalıdır.
Email: CardHolderData alanı içerisinde gönderilmesi beklenen ödemede kullanılan kart ile ilişkili kart
hamilinin
iş yerinde oluşturduğu hesapta kullandığı email adresi. Maksimum 254 karakter uzunluğunda olmalıdır.
Cc: CardHolderData alanı içerisinde yer alan MobilePhone içerisinde gönderilmesi beklenen ödemede
kullanılan kart ile ilişkili kart hamilinin cep telefonuna ait ülke kodu. 1-3 karakter uzunluğunda olmalıdır.
Subscriber : CardHolderData alanı içerisinde yer alan MobilePhone içerisinde gönderilmesi beklenen
ödemede kullanılan kart ile ilişkili kart hamilinin cep telefonuna ait abone numarası. Maksimum 15 karakter
uzunluğunda olmalıdır.
4.3. Banka Tarafında Dönen Cevap Bilgileri
OrderId (İşlem Numarası): İşlemin Sanal Pos sisteminde kayıtlı numarasıdır. Banka Tarafında üretilmektedir.
İade ve iptal işlemlerinde kullanılmaktadır.
ProvisionNumber (Provizyon Numarası): Başarılı işlemlerde kart bankasının vermiş olduğu otorizasyon
numarasıdır. Kart bankası tarafında üretilmektedir. İade ve iptal işlemlerinde kullanılmaktadır.
RRN (Referans Numarası1): Pos bankası tarafında üretilen işlem referans numarasıdır. İade ve iptal
işlemlerinde kullanılmaktadır.Kuveyt Türk Katılım Bankası 2024 Sayfa 10
Stan (Referans Numarası2): Pos bankası tarafında üretilen işlem referans numarasıdır. İade ve iptal
işlemlerinde kullanılmaktadır.
ResponseCode (Cevap Kodu): Genel kabul görmüş kart veya pos bankası cevabıdır. Başarılı işlemlerde “00”
kodu verilmektedir. Her bir kod farklı bir cevabı bulunmaktadır.
ResponseMessage (Cevap Mesajı): Genel kabul görmüş kart veya pos bankası cevabının açıklamasıdır.
MD: Banka tarafından her işlem için oluşturulan değerdir. Ödemenin alınacağı Request 2 içerisinde
gönderilmesi gerekmektedir.
Not: Cevap kodu ve cevap mesajı listesi https://kurumsal.kuveytturk.com.tr adresindeki Yönetim -
Dokümantasyon alanında paylaşımdadır.
5. Entegrasyon
Sanal Pos tarafından sunulan Güvenli Ödeme yöntemi için kullanılacak örnek bir entegrasyon işlemi bu
kısımda anlatılmaktadır.
• Üye işyeri kullanıcıdan kart bilgilerini alır. Kart, sipariş ve üye işyerine ait bilgileri içeren mesaj
oluşturulur. Mesajda yer alacak Hashdata alanına dikkat edilmelidir. Bu mesaj yapısı XML
formatındadır. Bir sonraki bölümde mesaj yapısı ve örnek mesaj yer almaktadır.
• Üye işyeri oluşturduğu XML mesajı Sanal Pos tarafından sunulan Sanal Pos Güvenli Ödeme
Noktası (Kart Doğrulaması) noktasına HTTP POST yöntemi ile gönderir.
• Bu işlem sonucunda HTML yapısında bir cevap yer almaktadır. Üye işyeri bu HTML cevabı
tarayıcıya iletmelidir.
• Sanal Pos tarafından gerçekleştirilecek işlemler sonrasında işleme ait sonuç XML mesaj şeklinde
FailUrl ya da OkUrl’e HTTP Post ile iletilecektir. Bir sonraki bölümde mesaj yapısı ve örnek mesaj
yer
almaktadır.
• Güvenli Ödeme şifre ekranı ile sadece kart doğrulama işlemi yapılmaktadır, otorizasyon
(ödemenin alınması) verilmemektedir, otorizasyon (ödeme) almak için onay Sanal Pos Güvenli
Ödeme Onaylama (Otorizasyon Alınması) adresine istekte bulunmanız gerekmektedir.
• Üye işyeri FailUrl'e veya OkUrl'e gelecek cevaba göre kontrollerini yapar, dilerse kullanıcıya
gösterebilir.
• Eğer işlem sonucu başarılı olarak mesaj OkUrl'e iletildiyse (Response Code “00” ve Response
Message “Kart Doğrulandı”) üye işyeri ödeme işlemini sonlandırmak için Sanal Pos Güvenli
Ödeme Onaylama (Otorizasyon Alınması) adresine ödeme mesajı göndermelidir.
• Gönderilecek mesajda, bu noktada gelen cevapta yer alan "MD" değeri ile birlikte ilk aşamada
gönderilen üye işyeri ve sipariş bilgileri gönderilmelidir.
• Sanal Pos Güvenli Ödeme Onaylama (Otorizasyon Alınması) adresine gönderilen mesaj Sanal Pos
servisi tarafından işlenir ve XML cevap verilir. XML cevapta yer alan ResponseCode değeri "00" veKuveyt Türk Katılım Bankası 2024 Sayfa 11
Response Message “Otorizasyon verildi.” olduğu durumda ödeme işlemi başarılı gerçekleşmiş
olmaktadır.
5.1. Kullanılan Mesaj Yapıları
5.1.1. Satış İşlemi Mesaj Örneği – Kart Doğrulaması (Request 1)
<KuveytTurkVPosMessage xmlns:xsi=http://www.w3.org/2001/XMLSchemainstance
xmlns:xsd="http://www.w3.org/2001/XMLSchema">
// <APIVersion>1.0.0</APIVersion> Eylül 2022’de sonlandırıldı.
<APIVersion>TDV2.0.0</APIVersion>
<OkUrl>http://localhost/php//ThreeDModetest/Approval.php</OkUrl>
<FailUrl>http://localhost/php//ThreeDModetest/Fail.php</FailUrl>
<HashData>TJcp1k5UUT/TSa5X2m0+82E9I/o=</HashData>
<MerchantId>496</MerchantId>
<CustomerId>400235</CustomerId>
<DeviceData>
<DeviceChannel> 02</DeviceChannel>
<ClientIP>12.22.156.43</ClientIP>
</DeviceData>
<CardHolderData>
<BillAddrCity>İstanbul</BillAddrCity>
<BillAddrCountry>792</BillAddrCountry>
<BillAddrLine1>XXX Mahallesi XXX Caddesi No 55 Daire 1</BillAddrLine1>
<BillAddrPostCode>34000</BillAddrPostCode>
<BillAddrState>40</BillAddrState>
<Email>xxxxx@gmail.com</Email>
<MobilePhone>
<Cc>90</Cc>
<Subscriber>1234567899</Subscriber>
</MobilePhone>
</CardHolderData>
<UserName>apitest</UserName>
<CardNumber>****************</CardNumber>
<CardExpireDateYear>**</CardExpireDateYear>
<CardExpireDateMonth>**</CardExpireDateMonth>
<CardCVV2>***</CardCVV2>
<CardHolderName>Test </CardHolderName>
<CardType>Troy</CardType>
<TransactionType>Sale</TransactionType>
<InstallmentCount>0</InstallmentCount>
<Amount>500</Amount>
<DisplayAmount>500</DisplayAmount>
<CurrencyCode>0949</CurrencyCode>
<MerchantOrderId>20201221</MerchantOrderId>Kuveyt Türk Katılım Bankası 2024 Sayfa 12
<TransactionSecurity>3</TransactionSecurity>
</KuveytTurkVPosMessage>
5.1.2.Satış İşlemi Mesaj Örneği Banka Cevabı (Response 1)
<VPosMessage>
<OrderId>40790217</OrderId>
<OkUrl>http://localhost/php//ThreeDModetest/Approval.php</OkUrl>
<FailUrl>http://localhost/php//ThreeDModetest/Fail.php</FailUrl>
<MerchantId>496</MerchantId>
<SubMerchantId>0</SubMerchantId>
<CustomerId>0</CustomerId>
<UserName>apiuser</UserName>
<HashPassword>poCqMathhevCYY1LVNbWCQWbC5I=</HashPassword>
<CardNumber>4033********0327</CardNumber>
<BatchID>2159</BatchID>
<InstallmentCount>0</InstallmentCount>
<Amount>100</Amount>
<CancelAmount>100</CancelAmount>
<MerchantOrderId>20201221</MerchantOrderId>
<FECAmount>0</FECAmount>
<CurrencyCode>949</CurrencyCode>
<QeryId>0</QeryId>
<DebtId>0</DebtId>
<SurchargeAmount>0</SurchargeAmount>
<SGKDebtAmount>0</SGKDebtAmount>
<TransactionSecurity>3</TransactionSecurity>
<PaymentId xsi:nil= ”true” />
<OrderPOSTransactionId xsi:nil="true" />
<TranDate xsi:nil="true" />
</VPosMessage>
<IsEnrolled>true</IsEnrolled>
<IsVirtual>false</IsVirtual>
<ResponseCode>00</ResponseCode>
<ResponseMessage>Kart doğrulandı.</ResponseMessage>
<OrderId>40790217</OrderId>
<TransactionTime>0001-01-01T00:00:00</TransactionTime>
<MerchantOrderId>20201221</MerchantOrderId>
<HashData>q3HpRgAO4xPP5UVYBg8EcVtO+sQ=</HashData>
<MD>BUdzLdTOnnrFP1/q1C/EgfrVxHvdvDtx2MuEusn+lB9My5kmogvb4JreoUYD1++6</MD>
<ReferenceId>2d8429b16f3843e3a9052cc4a772986f</ReferenceId>
<BusinessKey>202012211058907118850000001</BusinessKey>Kuveyt Türk Katılım Bankası 2024 Sayfa 13
5.1.3. Satış İşlemi Onay Mesaj Örneği – Ödeme Alınması (Request 2)
<KuveytTurkVPosMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema">
// <APIVersion>1.0.0</APIVersion>
<APIVersion>TDV2.0.0</APIVersion>
<HashData>ANcybxW/c1G39+RMstZ3ROYakO8=</HashData>
<MerchantId>496</MerchantId>
<CustomerId>400235</CustomerId>
<UserName>apitest</UserName>
<TransactionType>Sale</TransactionType>
<InstallmentCount>0</InstallmentCount>
<Amount>100</Amount>
<MerchantOrderId>20201221</MerchantOrderId>
<TransactionSecurity>3</TransactionSecurity>
<KuveytTurkVPosAdditionalData>
<AdditionalData>
<Key>MD</Key>
<Data>BUdzLdTOnnrFP1/q1C/EgfrVxHvdvDtx2MuEusn+lB9My5kmogvb4JreoUYD1++6</Data>
</AdditionalData>
</KuveytTurkVPosAdditionalData>
</KuveytTurkVPosMessage>
5.1.4.Ödeme İşlem Sonucu Mesaj Yapısı (Response 2)
<VPosTransactionResponseContract xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<VPosMessage>
<OrderId>40790217</OrderId>
<OkUrl>http://localhost/php//ThreeDModetest/Approval.php</OkUrl>
<FailUrl>http://localhost/php//ThreeDModetest/Fail.php</FailUrl>
<MerchantId>496</MerchantId>
<SubMerchantId>0</SubMerchantId>
<CustomerId>400235</CustomerId
><UserName>apitest</UserName>
<HashPassword>poCqMathhevCYY1LVNbWCQWbC5I=</HashPassword>
<CardNumber>4033********0327</CardNumber>
<BatchID>2159</BatchID>
<InstallmentCount>0</InstallmentCount>
<Amount>100</Amount>
<CancelAmount>0</CancelAmount>
<MerchantOrderId>20201221</MerchantOrderId>
<FECAmount>0</FECAmount>
<CurrencyCode>949</CurrencyCode>Kuveyt Türk Katılım Bankası 2024 Sayfa 14
<QeryId>0</QeryId>
<DebtId>0</DebtId>
<SurchargeAmount>0</SurchargeAmount>
<SGKDebtAmount>0</SGKDebtAmount>
<TransactionSecurity>3</TransactionSecurity>
<DeferringCount xsi:nil="true"/>
<InstallmentMaturityCommisionFlag>0</InstallmentMaturityCommisionFlag>
<PaymentId xsi:nil="true" /><OrderPOSTransactionId xsi:nil="true" /><TranDate xsi:nil="true" />
<TransactionUserId xsi:nil="true" />
</VPosMessage>
<IsEnrolled>true</IsEnrolled>
<IsVirtual>false</IsVirtual>
<ProvisionNumber>412371</ProvisionNumber>
<RRN>035617458943</RRN>
<Stan>458943</Stan>
<ResponseCode>00</ResponseCode>
<ResponseMessage>OTORİZASYON VERİLDİ</ResponseMessage>
<OrderId>40790217</OrderId>
<TransactionTime>2020-12-21T17:22:45.383</TransactionTime>
<MerchantOrderId>20201221</MerchantOrderId>
<HashData>q4RhSZcSM+EbvCrNVb+kb0nZ/Po=</HashData>
<BusinessKey>202012211078954039910000001</BusinessKey>
</VPosTransactionResponseContract>
5.2. Tarım İşyeri
5.2.1. Satış İşlemi Mesaj Örneği – Kart Doğrulaması (Request 1) – Tarım İşyeri
<KuveytTurkVPosMessage xmlns:xsi=http://www.w3.org/2001/XMLSchemainstance
xmlns:xsd="http://www.w3.org/2001/XMLSchema">
// <APIVersion>1.0.0</APIVersion>
<APIVersion>TDV2.0.0</APIVersion>
<OkUrl>http://localhost/php//ThreeDModetest/Approval.php</OkUrl>
<FailUrl>http://localhost/php//ThreeDModetest/Fail.php</FailUrl>
<HashData>TJcp1k5UUT/TSa5X2m0+82E9I/o=</HashData>
<MerchantId>496</MerchantId>
<CustomerId>400235</CustomerId>
<UserName>apitest</UserName>
<CardNumber>****************</CardNumber>
<DeviceData>
<DeviceChannel> 02</DeviceChannel>
<ClientIP>12.22.156.43</ClientIP>
</DeviceData>
<CardHolderData>Kuveyt Türk Katılım Bankası 2024 Sayfa 15
<BillAddrCity>İstanbul</BillAddrCity>
<BillAddrCountry>792</BillAddrCountry>
<BillAddrLine1>XXX Mahallesi XXX Caddesi No 55 Daire 1</BillAddrLine1>
<BillAddrPostCode>34000</BillAddrPostCode>
<BillAddrState>40</BillAddrState>
<Email>xxxxx@gmail.com</Email>
<MobilePhone>
<Cc>90</Cc>
<Subscriber>1234567899</Subscriber>
</MobilePhone>
</CardHolderData>
<CardExpireDateYear>**</CardExpireDateYear>
<CardExpireDateMonth>**</CardExpireDateMonth>
<CardCVV2>***</CardCVV2>
<CardHolderName>Test </CardHolderName>
<CardType>Troy</CardType>
<TransactionType>Sale</TransactionType>
<InstallmentCount>0</InstallmentCount>
<DeferringCount>3</DeferringCount>
<Amount>500</Amount>
<DisplayAmount>500</DisplayAmount>
<CurrencyCode>0949</CurrencyCode>
<MerchantOrderId>20201221</MerchantOrderId>
<TransactionSecurity>3</TransactionSecurity>
</KuveytTurkVPosMessage>
5.2.2.Satış İşlemi Mesaj Örneği Banka Cevabı (Response 1) - Tarım İşyeri
<VPosMessage>
<OrderId>40790217</OrderId>
<OkUrl>http://localhost/php//ThreeDModetest/Approval.php</OkUrl>
<FailUrl>http://localhost/php//ThreeDModetest/Fail.php</FailUrl>
<MerchantId>496</MerchantId>
<SubMerchantId>0</SubMerchantId>
<CustomerId>0</CustomerId>
<UserName>apiuser</UserName>
<HashPassword>poCqMathhevCYY1LVNbWCQWbC5I=</HashPassword>
<CardNumber>4033********0327</CardNumber>
<BatchID>2159</BatchID>
<InstallmentCount>0</InstallmentCount>
<Amount>100</Amount>
<CancelAmount>100</CancelAmount>
<MerchantOrderId>20201221</MerchantOrderId>
<FECAmount>0</FECAmount>Kuveyt Türk Katılım Bankası 2024 Sayfa 16
<CurrencyCode>949</CurrencyCode>
<QeryId>0</QeryId>
<DebtId>0</DebtId>
<SurchargeAmount>0</SurchargeAmount>
<SGKDebtAmount>0</SGKDebtAmount>
<TransactionSecurity>3</TransactionSecurity>
<PaymentId xsi:nil= ”true” />
<OrderPOSTransactionId xsi:nil="true" />
<TranDate xsi:nil="true" />
</VPosMessage>
<IsEnrolled>true</IsEnrolled>
<IsVirtual>false</IsVirtual>
<ResponseCode>00</ResponseCode>
<ResponseMessage>Kart doğrulandı.</ResponseMessage>
<OrderId>40790217</OrderId>
<TransactionTime>0001-01-01T00:00:00</TransactionTime>
<MerchantOrderId>20201221</MerchantOrderId>
<HashData>q3HpRgAO4xPP5UVYBg8EcVtO+sQ=</HashData>
<MD>BUdzLdTOnnrFP1/q1C/EgfrVxHvdvDtx2MuEusn+lB9My5kmogvb4JreoUYD1++6</MD>
<ReferenceId>2d8429b16f3843e3a9052cc4a772986f</ReferenceId>
<BusinessKey>202012211058907118850000001</BusinessKey>
5.2.3. Satış İşlemi Onay Mesaj Örneği – Ödeme Alınması (Request 2) - Tarım İşyeri
<KuveytTurkVPosMessage xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema">
// <APIVersion>1.0.0</APIVersion> Eylül 2022’de sonlandırıldı.
<APIVersion>TDV2.0.0</APIVersion>
<HashData>ANcybxW/c1G39+RMstZ3ROYakO8=</HashData>
<MerchantId>496</MerchantId>
<CustomerId>400235</CustomerId>
<UserName>apitest</UserName>
<TransactionType>Sale</TransactionType>
<InstallmentCount>0</InstallmentCount>
<Amount>100</Amount>
<MerchantOrderId>20201221</MerchantOrderId>
<TransactionSecurity>3</TransactionSecurity>
<KuveytTurkVPosAdditionalData>
<AdditionalData>
<Key>MD</Key>
<Data>BUdzLdTOnnrFP1/q1C/EgfrVxHvdvDtx2MuEusn+lB9My5kmogvb4JreoUYD1++6</Data>
</AdditionalData>Kuveyt Türk Katılım Bankası 2024 Sayfa 17
</KuveytTurkVPosAdditionalData>
</KuveytTurkVPosMessage>
5.2.4.Ödeme İşlem Sonucu Mesaj Yapısı (Response 2) - Tarım İşyeri
<VPosTransactionResponseContract xmlns:xsd="http://www.w3.org/2001/XMLSchema"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
<VPosMessage>
<OrderId>40790217</OrderId>
<OkUrl>http://localhost/php//ThreeDModetest/Approval.php</OkUrl>
<FailUrl>http://localhost/php//ThreeDModetest/Fail.php</FailUrl>
<MerchantId>496</MerchantId>
<SubMerchantId>0</SubMerchantId>
<CustomerId>400235</CustomerId
><UserName>apitest</UserName>
<HashPassword>poCqMathhevCYY1LVNbWCQWbC5I=</HashPassword>
<CardNumber>4033********0327</CardNumber>
<BatchID>2159</BatchID>
<InstallmentCount>0</InstallmentCount>
<DeferringCount>3</DeferringCount>
<Amount>100</Amount>
<CancelAmount>0</CancelAmount>
<MerchantOrderId>20201221</MerchantOrderId>
<FECAmount>0</FECAmount>
<CurrencyCode>949</CurrencyCode>
<QeryId>0</QeryId>
<DebtId>0</DebtId>
<SurchargeAmount>0</SurchargeAmount>
<SGKDebtAmount>0</SGKDebtAmount>
<TransactionSecurity>3</TransactionSecurity>
<DeferringCount xsi:nil="true"/>
<InstallmentMaturityCommisionFlag>0</InstallmentMaturityCommisionFlag>
<PaymentId xsi:nil="true" /><OrderPOSTransactionId xsi:nil="true" /><TranDate xsi:nil="true" />
<TransactionUserId xsi:nil="true" />
</VPosMessage>
<IsEnrolled>true</IsEnrolled>
<IsVirtual>false</IsVirtual>
<ProvisionNumber>412371</ProvisionNumber>
<RRN>035617458943</RRN>
<Stan>458943</Stan>
<ResponseCode>00</ResponseCode>
<ResponseMessage>OTORİZASYON VERİLDİ</ResponseMessage>
<OrderId>40790217</OrderId>
<TransactionTime>2020-12-21T17:22:45.383</TransactionTime>Kuveyt Türk Katılım Bankası 2024 Sayfa 18
<MerchantOrderId>20201221</MerchantOrderId>
<HashData>q4RhSZcSM+EbvCrNVb+kb0nZ/Po=</HashData>
<BusinessKey>202012211078954039910000001</BusinessKey>
</VPosTransactionResponseContract>
Not: Ödeme işlemine ait cevap mesajının içerisinde yer alan VPOSMessage alanında, üye işyerlerinin
ödeme işlemi için gönderdikleri mesaj yer almaktadır.
Not: OkUrl veya FailUrl e iletilecek bu mesaj AuthenticationResponse altında UrlEncoded olacak
şekilde gönderilmektedir. Mesajı kullanmak için gelen formun içinde AuthenticationResponse
anahtarıyla gönderilen değeri UrlDecode ile çözünüz.
(Örneğin .NET için; System.Web.HttpUtility.UrlDecode(Request.Form["AuthenticationResponse"])
şeklinde kod parçası kullanınız.)
6. HashData Hesaplamaları
İşyerine gelen cevabın Kuveyt Türk Sanal POS tarafından gönderildiğinin kontrol edilebilmesi için
HashData değeri yer almaktadır. Üye işyerinin kendi oluşturduğu HashData ile Kuveyt Türk Sanal POS
tarafında Hashdata hesaplamasından beklediği değerin eşleşmesi gerekmektedir. Hashdata
hesaplamasında kullandığınız değerlerin banka tarafındaki değerlerden farklı olması durumunda
"Şifrelenen veriler (Hashdata) uyuşmamaktadır." hatası alınmaktadır.
Banka tarafında gönderilen Cevap mesajı Hash hesaplamasında kullanılan parametreler şöyledir:
MerchantOrderId, RRN, ResponseCode, OrderId, hashPassword
Not: “Response 1” banka cevap mesaj yapısında RRN üretilmediğinden hash hesaplamasına dâhil
edilmemektedir.
MerchantOrderId, ResponseCode, OrderId, hashPassword parametreleri kullanılmaktadır.
Üye işyerinin satış işleminde göndermesi gereken Hash hesaplamasında kullanılan parametreler şöyledir:
MerchantId, MerchantOrderId, Amount, OkUrl, FailUrl, UserName, hashPassword
Not: Satış İşlemi Onay Mesaj (Request 2) yapısında sayfa yönlendirmesi yapılmadığından Hash
hesaplamasında OkUrl ve FailUrl alanları dahil edilmeyecektir.
Request 2 için; MerchantId, MerchantOrderId, Amount, UserName, HashPassword
Hesaplama
$Password=""; // Web Yönetim ekranlarından oluşturulan Api rolündeki kullanıcı şifresi
$HashedPassword = base64_encode(sha1($Password,"ISO-8859-9")); //md5($Password);Kuveyt Türk Katılım Bankası 2024 Sayfa 19
$HashData=base64_encode(sha1($MerchantId.$MerchantOrderId.$Amount.$OkUrl.$FailUrl.$UserName
.$HashedPassword , "ISO-8859-9"));
7. Sanal Pos Güvenli Ödeme Noktası Adresleri (Güncel)
Sanal Pos Güvenli Ödeme Noktası – Kart Doğrulanması (Production) :
https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelPayGate
Sanal Pos Güvenli Ödeme Onaylama – Ödeme Alınması (Production) :
https://sanalpos.kuveytturk.com.tr/ServiceGateWay/Home/ThreeDModelProvisionGate
8. Test Ortam Bilgileri
CustomerId = "400235"; // Müşteri Numarası
MerchantId = "496" ; // Mağaza Numarası
Api Bilgileri
UserName="apitest";
Password="api123";
Test Kart Bilgileri
Kart No: # 5188’9619’3919’2544#
CVV: 929
Expirydate: 06/25
Kart Doğrulama Şifresi: 123456
Sanal Pos 3D Model Ödeme Noktası Adresi – Kart Doğrulama (Test):
https://boatest.kuveytturk.com.tr/boa.virtualpos.services/Home/ThreeDModelPayGate
Sanal Pos 3D Model Ödeme Onaylama Adresi – Ödeme Alma (Test):
https://boatest.kuveytturk.com.tr/boa.virtualpos.services/Home/ThreeDModelProvisionGate
Not: Test ortamında alınacak herhangi bir hatada bankaya bilgi verebilirsiniz.
Mail adresi: sanalposdestek@kuveytturk.com.trKuveyt Türk Katılım Bankası 2024 Sayfa 20