<script setup>
import { ref } from 'vue';

const questions = ref([
    { question: "Hayat Bilişim’in sunduğu ERP çözümleri hangi sektörler için uygundur?", answer: "Hayat Bilişim’in 1C ERP çözümleri, üretim, lojistik, perakende, sağlık, enerji gibi çeşitli sektörlerde faaliyet gösteren her ölçekteki işletme için uygundur. Çözümlerimiz, işletmenizin özel ihtiyaçlarına göre özelleştirilebilir ve ölçeklenebilir.", show: false },
    { question: "1C ERP yazılımı mevcut sistemlerimle entegre edilebilir mi?", answer: "Evet, 1C ERP yazılımımız, mevcut sistemlerinizle sorunsuz entegrasyon sağlar. Mevcut altyapınıza göre uyarlanabilen esnek yapısıyla, ERP sistemimizi diğer yazılım ve donanımlarınızla entegre ederek iş süreçlerinizi tek bir platformda yönetebilirsiniz.", show: false },
    { question: "IoT sistem entegrasyonu ile hangi verimlilik avantajlarını elde edebilirim?", answer: "IoT entegrasyonları, gerçek zamanlı veri toplama, izleme ve analiz imkanı sunarak operasyonel verimliliği artırır. Makine performansını izlemek, ekipman bakımını optimize etmek ve enerji tasarrufu sağlamak gibi birçok fayda elde edebilirsiniz.", show: false },
    { question: "Hayat Bilişim’in ERP ve IoT çözümlerini kullanmaya başlamak ne kadar sürer?", answer: "Uygulama süresi, işletmenizin ihtiyaçlarına ve mevcut altyapısına bağlı olarak değişebilir. Küçük ölçekli işletmelerde kısa sürede kurulum yapabilirken, daha büyük işletmelerde ihtiyaç analizi ve yapılandırmaya bağlı olarak süreç birkaç hafta sürebilir.", show: false },
    { question: "ERP yazılımını kullanmak için teknik bilgiye sahip olmam gerekir mi?", answer: "Hayır, ERP yazılımımız kullanıcı dostu bir arayüze sahiptir. Ayrıca, ekibinize gerekli eğitimleri sağlayarak sistemi kolayca kullanmalarını sağlıyoruz. Teknik destek ekibimiz, ihtiyacınız olan her konuda size yardımcı olmaya hazırdır.", show: false },
    { question: "IoT çözümleriniz güvenli mi?", answer: "Evet, IoT çözümlerimizde güvenlik en öncelikli unsurdur. Veri aktarımını şifreli kanallar üzerinden gerçekleştiriyor ve güvenlik protokollerini sürekli güncelleyerek işletmenizin verilerini koruyoruz.", show: false },
]);



</script>

<template>
    <div class="flex flex-col max-w-4xl mx-auto px-4 items-center leading-7 text-center">
        <h2 id="pricing" class="font-display text-3xl tracking-tight sm:text-4xl" v-motion-pop-visible>
            Sık Sorulan Sorular
        </h2>
        <p class="box-border mt-2 text-xl  border-solid sm:text-2xl">
            <!-- Subheading -->
        </p>
        <div class="w-full space-y-2">
            <div v-for="question in questions" class="collapse collapse-arrow bg-base-200" v-motion-pop-visible>
                <input type="radio" name="my-accordion-2" checked="checked" />
                <div class="collapse-title text-xl font-medium">
                    {{ question.question }}
                </div>
                <div class="collapse-content">
                    <!-- generate lorem impsum -->
                    <p>{{ question.answer }}</p>
                </div>
            </div>
        </div>
    </div>
</template>