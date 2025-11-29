<template>
    <SiteLayout title="Ödeme Başarılı">
        <Head title="Ödeme Başarılı" />

        <div class="py-12">
            <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <div class="mb-6">
                        <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Ödeme Başarıyla Tamamlandı!</h1>
                    <p class="text-gray-600 mb-6">
                        Siparişiniz alınmıştır. Sipariş numaranız: <strong>{{ order.order_number }}</strong>
                    </p>
                    <p class="text-sm text-gray-500 mb-6">
                        Sipariş detaylarınız {{ order.billing_email }} adresine gönderilmiştir.
                    </p>
                    <div class="space-y-4">
                        <template v-if="$page.props.auth.user">
                            <Link
                                :href="route('orders.show', order.id)"
                                class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            >
                                Sipariş Detayını Görüntüle
                            </Link>
                            <div>
                                <Link
                                    :href="route('orders.index')"
                                    class="text-indigo-600 hover:text-indigo-700"
                                >
                                    Tüm Siparişlerim
                                </Link>
                            </div>
                        </template>
                        <template v-else>
                            <Link
                                :href="route('welcome')"
                                class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                            >
                                Ana Sayfaya Dön
                            </Link>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SiteLayout from '@/Layouts/SiteLayout.vue';

defineProps({
    order: {
        type: Object,
        required: true,
    },
});
</script>

