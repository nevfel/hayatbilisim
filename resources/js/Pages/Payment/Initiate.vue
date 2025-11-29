<template>
    <SiteLayout title="Ödeme">
        <Head title="Ödeme" />

        <div class="py-12">
            <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
                <!-- Sipariş Özeti -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Sipariş Özeti</h2>
                    <div class="border-t border-gray-200 pt-4">
                        <div class="flex justify-between mb-2">
                            <span class="text-gray-600">Sipariş No:</span>
                            <span class="font-semibold">{{ order.order_number }}</span>
                        </div>
                        <div class="flex justify-between mb-4">
                            <span class="text-gray-600">Toplam Tutar:</span>
                            <span class="text-2xl font-bold text-indigo-600">{{ formatCurrency(order.total_amount) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Kredi Kartı Formu -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Kredi Kartı Bilgileri</h2>

                    <form @submit.prevent="submitPayment">
                        <!-- Kart Sahibi -->
                        <div class="mb-4">
                            <label for="card_holder_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Kart Üzerindeki İsim
                            </label>
                            <input
                                id="card_holder_name"
                                v-model="form.card_holder_name"
                                type="text"
                                required
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="AD SOYAD"
                            />
                            <div v-if="form.errors.card_holder_name" class="text-red-600 text-sm mt-1">
                                {{ form.errors.card_holder_name }}
                            </div>
                        </div>

                        <!-- Kart Numarası -->
                        <div class="mb-4">
                            <label for="card_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Kart Numarası
                            </label>
                            <input
                                id="card_number"
                                v-model="form.card_number"
                                type="text"
                                required
                                maxlength="19"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="1234 5678 9012 3456"
                                @input="formatCardNumber"
                            />
                            <div v-if="form.errors.card_number" class="text-red-600 text-sm mt-1">
                                {{ form.errors.card_number }}
                            </div>
                        </div>

                        <!-- Son Kullanma Tarihi ve CVV -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Son Kullanma Tarihi
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input
                                        v-model="form.expiry_month"
                                        type="text"
                                        required
                                        maxlength="2"
                                        placeholder="MM"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    />
                                    <input
                                        v-model="form.expiry_year"
                                        type="text"
                                        required
                                        maxlength="2"
                                        placeholder="YY"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    />
                                </div>
                                <div v-if="form.errors.expiry_month || form.errors.expiry_year" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.expiry_month || form.errors.expiry_year }}
                                </div>
                            </div>
                            <div>
                                <label for="card_cvc" class="block text-sm font-medium text-gray-700 mb-2">
                                    CVV
                                </label>
                                <input
                                    id="card_cvc"
                                    v-model="form.card_cvc"
                                    type="text"
                                    required
                                    maxlength="4"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="123"
                                />
                                <div v-if="form.errors.card_cvc" class="text-red-600 text-sm mt-1">
                                    {{ form.errors.card_cvc }}
                                </div>
                            </div>
                        </div>

                        <!-- Güvenlik Uyarısı -->
                        <div class="bg-blue-50 border border-blue-200 rounded-md p-4 mb-6">
                            <div class="flex">
                                <svg class="h-5 w-5 text-blue-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                </svg>
                                <p class="text-sm text-blue-700">
                                    Ödemeniz 3D Secure güvenlik sistemi ile korunmaktadır.
                                </p>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full bg-indigo-600 text-white py-3 px-6 rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed font-semibold"
                        >
                            <span v-if="form.processing">İşleniyor...</span>
                            <span v-else>Ödeme Yap ({{ formatCurrency(order.total_amount) }})</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import SiteLayout from '@/Layouts/SiteLayout.vue';

const props = defineProps({
    order: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    card_holder_name: '',
    card_number: '',
    card_cvc: '',
    expiry_month: '',
    expiry_year: '',
});

const formatCardNumber = (e) => {
    let value = e.target.value.replace(/\s/g, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    form.card_number = formattedValue;
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(amount);
};

const submitPayment = () => {
    // Kart numarasındaki boşlukları temizle
    const cleanCardNumber = form.card_number.replace(/\s/g, '');

    form.transform((data) => ({
        ...data,
        card_number: cleanCardNumber,
    })).post(route('payment.start-3d', props.order.id));
};
</script>
