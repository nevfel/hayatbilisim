<template>
    <SiteLayout title="Ödeme Başarılı">
        <Head title="Ödeme Başarılı" />

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="mb-6">
                        <svg class="mx-auto h-20 w-20 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Ödemeniz Başarıyla Tamamlandı!</h1>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 gap-4 text-left">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600 font-medium">Ödeme No:</span>
                                <span class="text-gray-900 font-semibold">{{ quickPayment.payment_number }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600 font-medium">Tutar:</span>
                                <span class="text-gray-900 font-semibold text-lg">{{ formatCurrency(quickPayment.amount) }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2" v-if="quickPayment.payment_date">
                                <span class="text-gray-600 font-medium">Ödeme Tarihi:</span>
                                <span class="text-gray-900 font-semibold">{{ formatDate(quickPayment.payment_date) }}</span>
                            </div>
                            <div class="flex justify-between" v-if="quickPayment.description">
                                <span class="text-gray-600 font-medium">Açıklama:</span>
                                <span class="text-gray-900">{{ quickPayment.description }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-6">
                        <p class="text-sm text-green-700">
                            Ödeme dekontunuz <strong>{{ quickPayment.gon_email }}</strong> adresine gönderilmiştir.
                        </p>
                    </div>

                    <div class="text-sm text-gray-500 mb-6">
                        <p>Herhangi bir sorunuz varsa lütfen bizimle iletişime geçin.</p>
                    </div>

                    <div class="mt-8">
                        <a
                            :href="route('welcome')"
                            class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold"
                        >
                            Ana Sayfaya Dön
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import SiteLayout from '@/Layouts/SiteLayout.vue';

const props = defineProps({
    quickPayment: {
        type: Object,
        required: true,
    },
});

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(amount);
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    const date = new Date(dateString);
    return new Intl.DateTimeFormat('tr-TR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    }).format(date);
};
</script>
