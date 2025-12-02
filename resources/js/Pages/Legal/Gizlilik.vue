<template>
    <Head title="Gizlilik Sözleşmesi" />

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Gizlilik Sözleşmesi</h1>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        <strong>Son Güncelleme:</strong> {{ new Date().toLocaleDateString('tr-TR') }}
                    </p>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Gizlilik Taahhüdümüz</h2>
                        <p class="text-gray-700 mb-4">
                            Hayat Bilişim olarak, kişisel verilerinizin korunmasına büyük önem vermekteyiz. 
                            Bu gizlilik sözleşmesi, kişisel bilgilerinizin nasıl toplandığı, kullanıldığı 
                            ve korunduğu hakkında bilgi vermektedir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Toplanan Bilgiler</h2>
                        <p class="text-gray-700 mb-4">
                            Topladığımız bilgiler şunlardır:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li>Kayıt sırasında sağladığınız bilgiler (ad, e-posta, telefon)</li>
                            <li>Sipariş ve ödeme işlemlerinizle ilgili bilgiler</li>
                            <li>Site kullanımınızla ilgili teknik bilgiler (IP adresi, çerezler)</li>
                            <li>İletişim formları ve müşteri hizmetleri iletişimleriniz</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Bilgilerin Kullanımı</h2>
                        <p class="text-gray-700 mb-4">
                            Toplanan bilgiler aşağıdaki amaçlarla kullanılmaktadır:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li>Hizmetlerimizin sunulması ve iyileştirilmesi</li>
                            <li>Sipariş ve ödeme işlemlerinizin gerçekleştirilmesi</li>
                            <li>Müşteri hizmetleri ve destek sağlanması</li>
                            <li>Yasal yükümlülüklerin yerine getirilmesi</li>
                            <li>Güvenlik ve dolandırıcılık önleme</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Çerezler (Cookies)</h2>
                        <p class="text-gray-700 mb-4">
                            Sitemiz, kullanıcı deneyimini iyileştirmek ve site performansını analiz etmek 
                            için çerezler kullanmaktadır. Çerezleri tarayıcı ayarlarınızdan yönetebilirsiniz.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Bilgi Güvenliği</h2>
                        <p class="text-gray-700 mb-4">
                            Kişisel bilgilerinizin güvenliğini sağlamak için endüstri standardı güvenlik 
                            önlemleri almaktayız. Ancak, internet üzerinden veri aktarımının %100 güvenli 
                            olmadığını unutmayın.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Üçüncü Taraf Hizmetler</h2>
                        <p class="text-gray-700 mb-4">
                            Ödeme işlemleri için güvenilir üçüncü taraf ödeme sağlayıcıları kullanılmaktadır. 
                            Bu sağlayıcıların kendi gizlilik politikaları bulunmaktadır.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Haklarınız</h2>
                        <p class="text-gray-700 mb-4">
                            KVKK kapsamında kişisel verilerinizle ilgili haklarınızı kullanabilirsiniz. 
                            Detaylı bilgi için KVKK Aydınlatma Metni'ne bakabilirsiniz.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. İletişim</h2>
                        <p class="text-gray-700 mb-4">
                            Gizlilik sözleşmemiz hakkında sorularınız için bizimle iletişime geçebilirsiniz.
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


