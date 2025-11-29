<template>
    <Head title="Kullanım Şartları" />

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Kullanım Şartları</h1>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        <strong>Son Güncelleme:</strong> {{ new Date().toLocaleDateString('tr-TR') }}
                    </p>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Genel Hükümler</h2>
                        <p class="text-gray-700 mb-4">
                            Bu kullanım şartları, [Şirket Adı] ("Şirket") tarafından işletilen web sitesi 
                            ve hizmetlerin kullanımına ilişkin kuralları belirlemektedir. Siteyi kullanarak 
                            bu şartları kabul etmiş sayılırsınız.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Hizmetlerin Kullanımı</h2>
                        <p class="text-gray-700 mb-4">
                            Site üzerinden sunulan hizmetlerden yararlanmak için:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li>18 yaşını doldurmuş olmanız gerekmektedir</li>
                            <li>Doğru ve güncel bilgiler sağlamanız gerekmektedir</li>
                            <li>Hesap bilgilerinizin güvenliğinden siz sorumlusunuz</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Sipariş ve Ödeme</h2>
                        <p class="text-gray-700 mb-4">
                            Siparişleriniz aşağıdaki koşullara tabidir:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li>Siparişleriniz onaylandıktan sonra iptal edilemez</li>
                            <li>Ödemeler güvenli ödeme sistemleri üzerinden yapılmaktadır</li>
                            <li>Fiyatlar ve stok durumu değişiklik gösterebilir</li>
                            <li>Ödeme işlemi tamamlandıktan sonra sipariş işleme alınır</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. İade ve İptal</h2>
                        <p class="text-gray-700 mb-4">
                            İade ve iptal işlemleri Tüketicinin Korunması Hakkında Kanun ve Mesafeli 
                            Satış Sözleşmeleri Yönetmeliği hükümlerine tabidir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Fikri Mülkiyet</h2>
                        <p class="text-gray-700 mb-4">
                            Sitede yer alan tüm içerikler, markalar, logolar ve tasarımlar Şirket'in 
                            fikri mülkiyetidir ve koruma altındadır.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Sorumluluk Sınırlaması</h2>
                        <p class="text-gray-700 mb-4">
                            Şirket, site üzerinden sunulan hizmetlerde kesintisiz hizmet garantisi vermemektedir. 
                            Teknik sorunlar, bakım çalışmaları veya diğer nedenlerle hizmet kesintileri 
                            yaşanabilir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Değişiklikler</h2>
                        <p class="text-gray-700 mb-4">
                            Şirket, bu kullanım şartlarını önceden haber vermeksizin değiştirme hakkını 
                            saklı tutar. Değişiklikler sitede yayınlandığı tarihten itibaren geçerlidir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Uygulanacak Hukuk</h2>
                        <p class="text-gray-700 mb-4">
                            Bu kullanım şartları Türkiye Cumhuriyeti yasalarına tabidir. Uyuşmazlıklar 
                            [Şehir] mahkemelerinde çözülecektir.
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

