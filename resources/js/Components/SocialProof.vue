<script setup>

// TODO: Gerçek müşteri referansları alındığında name, company ve testimonial alanlarını güncelleyin.
// Fotoğraf eklemek için avatar alanını açıp img etiketini kullanabilirsiniz.
const testimonials = [
    {
        name: 'Hasan Karaca',
        title: 'Lojistik Müdürü',
        company: 'Lojistik Sektörü',
        initials: 'HK',
        testimonial: 'Hayat Bilişim ile çalışmaya başladıktan sonra, iş süreçlerimizde gözle görülür bir iyileşme yaşadık. 1C ERP çözümleri sayesinde stok yönetimi ve sipariş takibimiz daha verimli hale geldi. Bu sistemin esnekliği ve uyarlanabilirliği sayesinde, iş süreçlerimizi kolaylıkla yönetiyoruz.'
    },
    {
        name: 'Ayşe Demir',
        title: 'Üretim Müdürü',
        company: 'İmalat Sektörü',
        initials: 'AD',
        testimonial: 'IoT entegrasyonları sayesinde makinelerimizin performansını anlık olarak takip edebiliyoruz. Gerçek zamanlı raporlama ve analizler, operasyonel kararlarımızı hızlı ve güvenilir bir şekilde almamıza yardımcı oluyor. Hayat Bilişim\'in desteği, işletmemizin büyümesine büyük katkı sağladı.'
    },
    {
        name: 'Mehmet Yıldız',
        title: 'Finans Müdürü',
        company: 'Toptan Ticaret',
        initials: 'MY',
        testimonial: 'Hayat Bilişim AŞ, her zaman çözüm odaklı yaklaşımlarıyla ihtiyaçlarımıza yanıt verdi. ERP sistemimizle ilgili tüm sorularımıza anında destek sağlıyorlar. Ekibin uzmanlığı ve ilgisi, firmamızı geleceğe taşırken güvende hissetmemizi sağlıyor.'
    },
];
</script>

<template>
    <section id="testimonials" aria-label="What our customers are saying">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="mx-auto max-w-2xl md:text-center" v-motion-pop-visible>
                <h2 class="font-display text-3xl tracking-tight sm:text-4xl">Müşterilerimizin Söyledikleri</h2>
            </div>
            <div class="flex flex-col md:flex-row justify-between space-y-4 md:space-x-6 mt-12">
                <div v-for="testimonial in testimonials" class="card md:w-1/3 bg-base-200 flex p-4 flex-col items-center gap-6" v-motion-pop-visible>
                    <figure class="relative flex-col shadow-slate-900/10">
                        <blockquote class="relative">
                            <p class="text-lg tracking-tight">{{ testimonial.testimonial }}</p>
                        </blockquote>
                        <figcaption class="relative mt-6 flex w-full items-center justify-between border-t border-accent pt-6">
                            <div>
                                <div class="font-display text-base font-semibold">{{ testimonial.name }}</div>
                                <div class="text-sm opacity-60">{{ testimonial.title }}</div>
                                <div class="text-xs opacity-40 mt-0.5">{{ testimonial.company }}</div>
                            </div>
                            <div class="flex items-center justify-center h-14 w-14 rounded-full bg-primary text-primary-content font-bold text-lg flex-shrink-0">
                                {{ testimonial.initials }}
                            </div>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </section>
</template>