<template>
    <SiteLayout title="Sepetim">
        <Head title="Sepetim" />

        <div class="min-h-screen bg-gradient-to-b from-base-100 to-base-200 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-4xl font-bold mb-8">Sepetim</h1>

                <div v-if="cartItems.length === 0" class="card bg-base-100 shadow-xl p-12 text-center">
                    <p class="text-base-content/70 mb-4">Sepetiniz boş.</p>
                    <Link :href="route('product.show')" class="btn btn-primary">
                        ERP Çözümümüze Göz Atın
                    </Link>
                </div>

                <div v-else class="grid lg:grid-cols-3 gap-6">
                    <!-- Cart Items -->
                    <div class="lg:col-span-2 space-y-4">
                        <div
                            v-for="item in cartItems"
                            :key="item.id"
                            class="card bg-base-100 shadow-xl"
                        >
                            <div class="card-body">
                                <div class="flex justify-between items-start mb-4">
                                    <div class="flex-1">
                                        <h3 class="card-title text-xl mb-2">{{ item.product.name }}</h3>
                                        <p class="text-base-content/70 text-sm mb-3">{{ item.product.description }}</p>
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="text-sm font-semibold">Temel Paket:</span>
                                            <span class="text-primary font-bold">{{ formatPrice(item.product.price) }}</span>
                                        </div>
                                    </div>
                                    <button
                                        @click="removeItem(item.id)"
                                        class="btn btn-ghost btn-sm btn-circle text-error"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <!-- Selected Services -->
                                <div v-if="item.selected_services && item.selected_services.length > 0" class="mb-4">
                                    <div class="divider divider-start text-sm">Seçili Ek Hizmetler</div>
                                    <div class="space-y-2">
                                        <div
                                            v-for="service in item.selected_services"
                                            :key="service.id"
                                            class="flex items-center justify-between p-3 bg-base-200 rounded-lg"
                                        >
                                            <div class="flex-1">
                                                <span class="font-semibold text-sm">{{ service.name }}</span>
                                                <p class="text-xs text-base-content/60 mt-1">{{ service.description }}</p>
                                            </div>
                                            <span class="text-primary font-bold ml-4">{{ formatPrice(service.price) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center justify-between pt-4 border-t">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-semibold">Adet:</span>
                                        <div class="join">
                                            <button
                                                @click="updateQuantity(item.id, item.quantity - 1)"
                                                :disabled="item.quantity <= 1"
                                                class="btn btn-sm join-item"
                                            >
                                                -
                                            </button>
                                            <span class="btn btn-sm join-item no-animation">{{ item.quantity }}</span>
                                            <button
                                                @click="updateQuantity(item.id, item.quantity + 1)"
                                                class="btn btn-sm join-item"
                                            >
                                                +
                                            </button>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-xs text-base-content/60 mb-1">Ara Toplam</div>
                                        <span class="text-2xl font-bold text-primary">
                                            {{ formatPrice(calculateItemTotal(item)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="card bg-gradient-to-br from-primary to-secondary text-primary-content shadow-2xl sticky top-24">
                            <div class="card-body">
                                <h2 class="card-title text-2xl mb-6">Sipariş Özeti</h2>

                                <div class="space-y-3 mb-6">
                                    <div v-for="item in cartItems" :key="item.id" class="text-sm">
                                        <div class="flex justify-between text-primary-content/90 mb-1">
                                            <span>{{ item.product.name }} ({{ item.quantity }}x)</span>
                                            <span>{{ formatPrice(item.product.price * item.quantity) }}</span>
                                        </div>
                                        <div v-if="item.selected_services && item.selected_services.length > 0" class="ml-4 space-y-1">
                                            <div
                                                v-for="service in item.selected_services"
                                                :key="service.id"
                                                class="flex justify-between text-xs text-primary-content/70"
                                            >
                                                <span>+ {{ service.name }} ({{ item.quantity }}x)</span>
                                                <span>{{ formatPrice(service.price * item.quantity) }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                <div class="flex justify-between items-center text-2xl font-bold mb-6">
                                    <span>Toplam</span>
                                    <span>{{ formatPrice(total) }}</span>
                                </div>

                                <Link
                                    :href="route('orders.create')"
                                    class="btn btn-lg w-full bg-white text-primary hover:bg-base-100 border-none"
                                >
                                    Siparişi Tamamla
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </Link>

                                <div class="alert bg-white/10 border-white/30 mt-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm">Güvenli ödeme ile satın alın</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import SiteLayout from '@/Layouts/SiteLayout.vue';

defineProps({
    cartItems: {
        type: Array,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(price);
};

const calculateItemTotal = (item) => {
    let total = item.product.price * item.quantity;

    if (item.selected_services) {
        item.selected_services.forEach(service => {
            total += service.price * item.quantity;
        });
    }

    return total;
};

const updateQuantity = (cartId, quantity) => {
    router.put(route('cart.update', cartId), {
        quantity: quantity,
    }, {
        preserveScroll: true,
    });
};

const removeItem = (cartId) => {
    if (confirm('Bu ürünü sepetten çıkarmak istediğinize emin misiniz?')) {
        router.delete(route('cart.destroy', cartId), {
            preserveScroll: true,
        });
    }
};
</script>

