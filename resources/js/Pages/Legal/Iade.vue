<template>
    <Head title="İade Sözleşmesi" />

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">İade Sözleşmesi</h1>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        <strong>Son Güncelleme:</strong> {{ new Date().toLocaleDateString('tr-TR') }}
                    </p>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. İade Hakkı</h2>
                        <p class="text-gray-700 mb-4">
                            6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler 
                            Yönetmeliği hükümlerine göre, alıcılar ürün/hizmeti teslim aldıkları tarihten 
                            itibaren 14 gün içinde hiçbir gerekçe göstermeksizin cayma hakkını kullanabilirler.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. İade Koşulları</h2>
                        <p class="text-gray-700 mb-4">
                            İade için aşağıdaki koşullar geçerlidir:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li>Ürün/hizmet, teslim alındığı halde ve kullanılmamış olmalıdır</li>
                            <li>Ürün/hizmetin orijinal ambalajı ve faturası ile birlikte iade edilmesi gerekir</li>
                            <li>İade talebi, cayma hakkı süresi içinde (14 gün) yapılmalıdır</li>
                            <li>İade edilecek ürün/hizmetin hasar görmemiş olması gerekir</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. İade İşlemi</h2>
                        <p class="text-gray-700 mb-4">
                            İade talebinizi bizimle iletişime geçerek bildirebilirsiniz. İade talebiniz 
                            onaylandıktan sonra, ürün/hizmeti belirtilen adrese göndermeniz gerekmektedir. 
                            İade kargo ücreti, ürün/hizmetin hatasından kaynaklanmıyorsa müşteriye aittir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. İade İnceleme</h2>
                        <p class="text-gray-700 mb-4">
                            İade edilen ürün/hizmet, teslim alındıktan sonra incelenir. İnceleme sonucu 
                            uygun bulunursa, iade işlemi tamamlanır ve ödeme iadesi yapılır.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Ödeme İadesi</h2>
                        <p class="text-gray-700 mb-4">
                            İade onayından sonra, ödeme iadesi aynı ödeme yöntemi ile 14 iş günü içinde 
                            gerçekleştirilir. Kredi kartı ile yapılan ödemelerde, iade tutarı kartınıza 
                            işlem gününden itibaren 5-10 iş günü içinde yansır.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. İade Edilemeyecek Ürünler/Hizmetler</h2>
                        <p class="text-gray-700 mb-4">
                            Aşağıdaki durumlarda iade kabul edilmez:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li>Kullanılmış veya hasar görmüş ürünler</li>
                            <li>Orijinal ambalajı açılmış ve kullanıma uygun olmayan ürünler</li>
                            <li>Kişiye özel üretilen veya özelleştirilmiş ürünler</li>
                            <li>Dijital içerik ve yazılım ürünleri (açıldıktan sonra)</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Hatalı veya Kusurlu Ürün/Hizmet</h2>
                        <p class="text-gray-700 mb-4">
                            Teslim alınan ürün/hizmet hatalı veya kusurlu ise, cayma hakkı süresi sınırı 
                            olmaksızın değişim veya iade talebinde bulunabilirsiniz. Bu durumda kargo ücreti 
                            satıcıya aittir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. İletişim</h2>
                        <p class="text-gray-700 mb-4">
                            İade sözleşmesi hakkında sorularınız veya iade talebiniz için bizimle iletişime geçebilirsiniz.
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


