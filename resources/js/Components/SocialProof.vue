<script setup>

const testimonials = [
    {
        name: 'Hasan Karaca, Lojistik Müdürü',
        avatar: 'https://randomuser.me/api/portraits/men/29.jpg',
        testimonial: 'Hayat Bilişim ile çalışmaya başladıktan sonra, iş süreçlerimizde gözle görülür bir iyileşme yaşadık. 1C ERP çözümleri sayesinde stok yönetimi ve sipariş takibimiz daha verimli hale geldi. Bu sistemin esnekliği ve uyarlanabilirliği sayesinde, iş süreçlerimizi kolaylıkla yönetiyoruz.'
    },
    {
        name: 'Ayşe Demir, Üretim Müdürü',
        avatar: 'https://randomuser.me/api/portraits/women/19.jpg',
        testimonial: 'IoT entegrasyonları sayesinde makinelerimizin performansını anlık olarak takip edebiliyoruz. Gerçek zamanlı raporlama ve analizler, operasyonel kararlarımızı hızlı ve güvenilir bir şekilde almamıza yardımcı oluyor. Hayat Bilişim’in desteği, işletmemizin büyümesine büyük katkı sağladı.'
    },
    {
        name: 'Mehmet Yıldız, Finans Müdürü',
        avatar: 'https://randomuser.me/api/portraits/men/44.jpg',
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
                                <div class="font-display text-base">{{ testimonial.name }}</div>
                            </div>
                            <div class="overflow-hidden rounded-full bg-slate-50">
                                <img alt="" class="h-14 w-14 object-cover" style="color:transparent"
                                    :src='testimonial.avatar'>
                            </div>
                        </figcaption>
                    </figure>
                </div>
            </div>
        </div>
    </section>
</template>