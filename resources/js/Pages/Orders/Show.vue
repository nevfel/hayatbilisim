<template>
    <AuthLayout title="Sipariş Detayı">
        <Head :title="`Sipariş #${order.order_number}`" />

        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Sipariş #{{ order.order_number }}</h1>
                            <p class="text-sm text-gray-500 mt-1">{{ order.created_at }}</p>
                        </div>
                        <span
                            :class="{
                                'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                'bg-blue-100 text-blue-800': order.status === 'processing',
                                'bg-green-100 text-green-800': order.status === 'completed',
                                'bg-red-100 text-red-800': order.status === 'cancelled',
                            }"
                            class="px-4 py-2 rounded-full text-sm font-medium"
                        >
                            {{ getStatusText(order.status) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Sipariş Kalemleri -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Sipariş Kalemleri</h2>
                        <div class="space-y-3">
                            <div
                                v-for="item in order.items"
                                :key="item.id"
                                class="flex justify-between pb-3 border-b"
                            >
                                <div>
                                    <p class="font-medium">{{ item.product_name }}</p>
                                    <p class="text-sm text-gray-500">Adet: {{ item.quantity }}</p>
                                </div>
                                <p class="font-semibold">{{ item.subtotal }} ₺</p>
                            </div>
                        </div>
                        <div class="mt-4 pt-4 border-t flex justify-between text-lg font-semibold">
                            <span>Toplam:</span>
                            <span>{{ order.total_amount }} ₺</span>
                        </div>
                    </div>

                    <!-- Fatura Bilgileri -->
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Fatura Bilgileri</h2>
                        <div class="space-y-2 text-sm">
                            <p><strong>Ad Soyad:</strong> {{ order.billing_name }}</p>
                            <p><strong>E-posta:</strong> {{ order.billing_email }}</p>
                            <p v-if="order.billing_phone"><strong>Telefon:</strong> {{ order.billing_phone }}</p>
                            <p v-if="order.billing_address"><strong>Adres:</strong> {{ order.billing_address }}</p>
                            <p v-if="order.billing_city"><strong>Şehir:</strong> {{ order.billing_city }}</p>
                            <p v-if="order.billing_postal_code"><strong>Posta Kodu:</strong> {{ order.billing_postal_code }}</p>
                        </div>
                    </div>
                </div>

                <!-- Ödeme Durumu -->
                <div v-if="order.payment" class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Ödeme Durumu</h2>
                    <div class="space-y-2">
                        <p><strong>Durum:</strong> {{ getPaymentStatusText(order.payment.status) }}</p>
                        <p v-if="order.payment.transaction_id">
                            <strong>İşlem No:</strong> {{ order.payment.transaction_id }}
                        </p>
                        <p><strong>Tutar:</strong> {{ order.payment.amount }} ₺</p>
                        <p v-if="order.payment.paid_at">
                            <strong>Ödeme Tarihi:</strong> {{ order.payment.paid_at }}
                        </p>
                    </div>
                </div>

                <div class="mt-6">
                    <Link
                        :href="route('orders.index')"
                        class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50"
                    >
                        Geri
                    </Link>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineProps({
    order: {
        type: Object,
        required: true,
    },
});

const getStatusText = (status) => {
    const statusMap = {
        pending: 'Beklemede',
        processing: 'İşleniyor',
        completed: 'Tamamlandı',
        cancelled: 'İptal Edildi',
    };
    return statusMap[status] || status;
};

const getPaymentStatusText = (status) => {
    const statusMap = {
        pending: 'Beklemede',
        success: 'Başarılı',
        failed: 'Başarısız',
        cancelled: 'İptal Edildi',
        refunded: 'İade Edildi',
    };
    return statusMap[status] || status;
};
</script>

