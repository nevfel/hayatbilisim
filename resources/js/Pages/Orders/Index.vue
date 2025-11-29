<template>
    <AuthLayout title="Siparişlerim">
        <Head title="Siparişlerim" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Siparişlerim</h1>

                <div v-if="orders.length === 0" class="bg-white rounded-lg shadow-md p-12 text-center">
                    <p class="text-gray-500 mb-4">Henüz siparişiniz bulunmamaktadır.</p>
                    <Link :href="route('products.index')" class="text-indigo-600 hover:text-indigo-700">
                        Alışverişe Başla
                    </Link>
                </div>

                <div v-else class="space-y-4">
                    <div
                        v-for="order in orders"
                        :key="order.id"
                        class="bg-white rounded-lg shadow-md overflow-hidden"
                    >
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Sipariş #{{ order.order_number }}</h3>
                                    <p class="text-sm text-gray-500">{{ order.created_at }}</p>
                                </div>
                                <div class="text-right">
                                    <span
                                        :class="{
                                            'bg-yellow-100 text-yellow-800': order.status === 'pending',
                                            'bg-blue-100 text-blue-800': order.status === 'processing',
                                            'bg-green-100 text-green-800': order.status === 'completed',
                                            'bg-red-100 text-red-800': order.status === 'cancelled',
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-medium"
                                    >
                                        {{ getStatusText(order.status) }}
                                    </span>
                                    <p class="text-lg font-semibold text-gray-900 mt-2">{{ order.total_amount }} ₺</p>
                                </div>
                            </div>
                            <div class="border-t pt-4">
                                <div class="space-y-2">
                                    <div
                                        v-for="item in order.items"
                                        :key="item.id"
                                        class="flex justify-between text-sm"
                                    >
                                        <span>{{ item.product_name }} x {{ item.quantity }}</span>
                                        <span>{{ item.subtotal }} ₺</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end gap-2">
                                <Link
                                    :href="route('orders.show', order.id)"
                                    class="px-4 py-2 border border-gray-300 rounded-md hover:bg-gray-50 text-sm"
                                >
                                    Detay
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';

defineProps({
    orders: {
        type: Array,
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
</script>

