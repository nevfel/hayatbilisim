<script setup>
</script>

<template>
    <div class="hero min-h-[calc(100vh-80px)] bg-base-200 bg-cover bg-no-repeat bg-right lg:bg-[url('/storage/images/15404.jpg')]">
        <div class="hero-content -mt-24 max-w-[50rem] text-left bg-clip-content">
            <div class="max-w-2xl lg:-ml-48" v-motion-pop-visible>
                <h1 class="text-4xl md:text-6xl font-bold">Dijital Dönüşümde
                    <span class="text-transparent bg-clip-text w-24 h-12 bg-gradient-to-r from-primary to-secondary"> Güvenilir Çözüm Ortağınız</span>
                </h1>

                <p class="py-3 lg:font-bold">Hayat Bilişim AŞ olarak, işletmelerin dijital dönüşüm yolculuklarında yanlarında yer alıyoruz. Çeşitli endüstrilere özel iş uygulamaları geliştiriyor, her ölçekteki işletme için esnek ve ölçeklenebilir 1C ERP yazılım çözümleri sunuyoruz. </p>
                <p class="py-3 lg:font-bold">Nesnelerin İnterneti (IoT) sistem entegrasyonları ile şirketlerin operasyonel verimliliğini artırmalarına yardımcı oluyoruz.</p>
                <p class="py-3 lg:font-bold">
                    Dijital dönüşümün sunduğu fırsatları, yüksek teknoloji altyapımız ve deneyimli ekibimizle sizin için erişilebilir kılıyoruz. İşletmenizi geleceğe taşıyan çözümlerimizle tanışın!
                </p>



                <div class="flex gap-4 mt-3">
                    <a :href="route('product.show')" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        1C ERP Paketimizi İnceleyin
                    </a>
                    <a href="/#messaging" class="btn btn-outline btn-primary">Daha Fazla Bilgi</a>
                </div>

            </div>
        </div>
    </div>
</template>
