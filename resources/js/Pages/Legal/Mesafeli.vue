<template>
    <Head title="Mesafeli Satış Sözleşmesi" />

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Mesafeli Satış Sözleşmesi</h1>
                
                <div class="prose max-w-none">
                    <p class="text-gray-700 mb-4">
                        <strong>Son Güncelleme:</strong> {{ new Date().toLocaleDateString('tr-TR') }}
                    </p>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Taraflar</h2>
                        <p class="text-gray-700 mb-4">
                            Bu sözleşme, aşağıdaki taraflar arasında aşağıda belirtilen hüküm ve şartlar çerçevesinde imzalanmıştır:
                        </p>
                        <ul class="list-disc list-inside text-gray-700 mb-4 space-y-2">
                            <li><strong>Satıcı:</strong> Hayat Bilişim</li>
                            <li><strong>Alıcı:</strong> Sitemiz üzerinden ürün/hizmet satın alan gerçek veya tüzel kişi</li>
                        </ul>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Konu</h2>
                        <p class="text-gray-700 mb-4">
                            Bu sözleşmenin konusu, alıcının satıcıdan elektronik ortamda sipariş verdiği 
                            ürün/hizmetin satışı ve teslimi ile ilgili tarafların hak ve yükümlülüklerinin belirlenmesidir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. Ürün/Hizmet Bilgileri</h2>
                        <p class="text-gray-700 mb-4">
                            Satıcı, sattığı ürün/hizmetin temel özellikleri, satış fiyatı, ödeme şekli 
                            ve teslimat bilgilerini açık ve anlaşılır bir şekilde web sitesinde belirtmektedir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Sipariş ve Ödeme</h2>
                        <p class="text-gray-700 mb-4">
                            Alıcı, web sitesi üzerinden sipariş vererek ödeme yapmayı kabul eder. 
                            Ödeme işlemleri güvenli ödeme sistemleri üzerinden gerçekleştirilir. 
                            Sipariş onayı, alıcıya e-posta ile bildirilir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Teslimat</h2>
                        <p class="text-gray-700 mb-4">
                            Ürün/hizmet teslimatı, sipariş onayından sonra belirtilen süre içerisinde 
                            gerçekleştirilir. Teslimat süresi ve yöntemi sipariş sırasında belirtilir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Cayma Hakkı</h2>
                        <p class="text-gray-700 mb-4">
                            Alıcı, 6502 sayılı Tüketicinin Korunması Hakkında Kanun ve Mesafeli Sözleşmeler 
                            Yönetmeliği hükümlerine göre, sözleşmeden cayma hakkına sahiptir. Cayma hakkı, 
                            ürün/hizmetin teslim alındığı tarihten itibaren 14 gün içinde kullanılabilir.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. İade ve İptal</h2>
                        <p class="text-gray-700 mb-4">
                            İade ve iptal koşulları, İade Sözleşmesi'nde detaylı olarak belirtilmiştir. 
                            İade talepleri, cayma hakkı süresi içinde yapılmalıdır.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Uyuşmazlıkların Çözümü</h2>
                        <p class="text-gray-700 mb-4">
                            Bu sözleşmeden doğan uyuşmazlıkların çözümünde Türkiye Cumhuriyeti yasaları 
                            uygulanır. Uyuşmazlıklar öncelikle müzakere yoluyla çözülmeye çalışılır.
                        </p>
                    </section>

                    <section class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. İletişim</h2>
                        <p class="text-gray-700 mb-4">
                            Mesafeli satış sözleşmesi hakkında sorularınız için bizimle iletişime geçebilirsiniz.
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


