<template>
    <Head title="KVKK Aydınlatma Metni" />

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">KVKK Aydınlatma Metni</h1>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        <strong>Son Güncelleme:</strong> {{ new Date().toLocaleDateString('tr-TR') }}
                    </p>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Veri Sorumlusu</h2>
                        <p class="text-gray-700 mb-4">
                            6698 sayılı Kişisel Verilerin Korunması Kanunu ("KVKK") uyarınca, kişisel verileriniz 
                            [Şirket Adı] ("Şirket" veya "Veri Sorumlusu") tarafından aşağıda açıklanan kapsamda 
                            işlenmektedir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. İşlenen Kişisel Veriler</h2>
                        <p class="text-gray-700 mb-4">
                            Şirketimiz tarafından işlenen kişisel verileriniz şunlardır:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li>Kimlik Bilgileri: Ad, soyad, T.C. kimlik numarası</li>
                            <li>İletişim Bilgileri: E-posta adresi, telefon numarası, adres bilgileri</li>
                            <li>Müşteri İşlem Bilgileri: Sipariş bilgileri, ödeme bilgileri, fatura bilgileri</li>
                            <li>İnternet Trafik Verileri: IP adresi, çerez bilgileri, tarayıcı bilgileri</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Kişisel Verilerin İşlenme Amaçları</h2>
                        <p class="text-gray-700 mb-4">
                            Kişisel verileriniz aşağıdaki amaçlarla işlenmektedir:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li>Sipariş ve ödeme işlemlerinin gerçekleştirilmesi</li>
                            <li>Müşteri hizmetlerinin sunulması</li>
                            <li>Yasal yükümlülüklerin yerine getirilmesi</li>
                            <li>İş süreçlerinin yönetilmesi</li>
                            <li>Güvenlik ve denetim faaliyetlerinin yürütülmesi</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Kişisel Verilerin Aktarılması</h2>
                        <p class="text-gray-700 mb-4">
                            Kişisel verileriniz, yukarıda belirtilen amaçların gerçekleştirilmesi için, 
                            ödeme hizmet sağlayıcıları, kargo şirketleri ve yasal yükümlülükler çerçevesinde 
                            ilgili kamu kurum ve kuruluşları ile paylaşılabilir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Kişisel Verilerin Saklanma Süresi</h2>
                        <p class="text-gray-700 mb-4">
                            Kişisel verileriniz, işlenme amacının gerektirdiği süre boyunca ve yasal 
                            saklama sürelerine uygun olarak saklanmaktadır.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. KVKK Kapsamındaki Haklarınız</h2>
                        <p class="text-gray-700 mb-4">
                            KVKK'nın 11. maddesi uyarınca aşağıdaki haklara sahipsiniz:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li>Kişisel verilerinizin işlenip işlenmediğini öğrenme</li>
                            <li>Kişisel verileriniz işlenmişse buna ilişkin bilgi talep etme</li>
                            <li>Kişisel verilerinizin işlenme amacını ve bunların amacına uygun kullanılıp kullanılmadığını öğrenme</li>
                            <li>Yurt içinde veya yurt dışında kişisel verilerinizin aktarıldığı üçüncü kişileri bilme</li>
                            <li>Kişisel verilerinizin eksik veya yanlış işlenmiş olması hâlinde bunların düzeltilmesini isteme</li>
                            <li>Kişisel verilerinizin silinmesini veya yok edilmesini isteme</li>
                            <li>Kişisel verilerinizin düzeltilmesi, silinmesi, yok edilmesi hâlinde bunların kişisel verilerin aktarıldığı üçüncü kişilere bildirilmesini isteme</li>
                            <li>İşlenen verilerin münhasıran otomatik sistemler ile analiz edilmesi suretiyle kişinin kendisi aleyhine bir sonucun ortaya çıkmasına itiraz etme</li>
                            <li>Kişisel verilerin kanuna aykırı olarak işlenmesi sebebiyle zarara uğraması hâlinde zararın giderilmesini talep etme</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. İletişim</h2>
                        <p class="text-gray-700 mb-4">
                            KVKK kapsamındaki haklarınızı kullanmak için [e-posta adresi] adresine 
                            başvurabilirsiniz.
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
</script>

