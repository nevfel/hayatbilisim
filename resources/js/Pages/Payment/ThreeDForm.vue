<template>
    <SiteLayout title="Güvenli Ödeme">
        <Head title="Güvenli Ödeme" />

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="mb-6">
                        <div class="inline-block">
                            <svg class="animate-spin h-12 w-12 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-4">Güvenli Ödeme Sayfasına Yönlendiriliyorsunuz</h1>
                    <p class="text-gray-600 mb-2">
                        Lütfen bekleyiniz, 3D Secure güvenlik sayfasına yönlendiriliyorsunuz...
                    </p>
                    <p class="text-sm text-gray-500">
                        Bu işlem birkaç saniye sürebilir.
                    </p>

                    <!-- Hidden Form - Otomatik Submit Edilecek -->
                    <form ref="paymentForm" :action="formData.gateway" method="POST" class="hidden">
                        <input
                            v-for="(value, key) in formData.inputs"
                            :key="key"
                            type="hidden"
                            :name="key"
                            :value="value"
                        />
                    </form>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import SiteLayout from '@/Layouts/SiteLayout.vue';

const props = defineProps({
    formData: {
        type: Object,
        required: true,
    },
    order: {
        type: Object,
        required: false,
    },
    quickPayment: {
        type: Object,
        required: false,
    },
    isQuickPayment: {
        type: Boolean,
        default: false,
    },
});

const paymentForm = ref(null);

onMounted(() => {
    // Sayfa yüklendikten 1 saniye sonra formu otomatik submit et
    setTimeout(() => {
        if (paymentForm.value) {
            paymentForm.value.submit();
        }
    }, 1000);
});
</script>

<style scoped>
@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}

.animate-spin {
    animation: spin 1s linear infinite;
}
</style>
