<script setup>
import { ref, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import SiteLayout from '@/Layouts/SiteLayout.vue';

const props = defineProps({
    product: Object,
});

const selectedServices = ref([]);
const quantity = ref(1);

const meta = computed(() => {
    try {
        return typeof props.product.meta === 'string'
            ? JSON.parse(props.product.meta)
            : props.product.meta;
    } catch {
        return { features: [], services: [] };
    }
});

const totalPrice = computed(() => {
    let total = props.product.price * quantity.value;

    selectedServices.value.forEach(serviceId => {
        const service = meta.value.services?.find(s => s.id === serviceId);
        if (service) {
            total += service.price * quantity.value;
        }
    });

    return total;
});

const toggleService = (serviceId) => {
    const index = selectedServices.value.indexOf(serviceId);
    if (index > -1) {
        selectedServices.value.splice(index, 1);
    } else {
        selectedServices.value.push(serviceId);
    }
};

const addToCart = () => {
    const selectedServiceObjects = selectedServices.value.map(serviceId => {
        return meta.value.services?.find(s => s.id === serviceId);
    }).filter(Boolean);

    router.post(route('cart.store'), {
        product_id: props.product.id,
        quantity: quantity.value,
        selected_services: selectedServiceObjects,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            alert('Ürün sepete eklendi!');
        }
    });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(price);
};
</script>

<template>
    <SiteLayout :title="product.name">
        <div class="min-h-screen bg-gradient-to-b from-base-100 to-base-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                <!-- Header -->
                <div class="text-center mb-12" v-motion-pop-visible>
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">
                        {{ product.name }}
                    </h1>
                    <p class="text-lg md:text-xl text-base-content/70 max-w-3xl mx-auto">
                        {{ product.description }}
                    </p>
                </div>

                <div class="grid lg:grid-cols-2 gap-12">
                    <!-- Left Column - Product Details -->
                    <div class="space-y-8">
                        <!-- Base Package Features -->
                        <div class="card bg-base-100 shadow-xl" v-motion-slide-visible-left>
                            <div class="card-body">
                                <h2 class="card-title text-2xl mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    Temel Paket İçeriği
                                </h2>
                                <ul class="space-y-3">
                                    <li v-for="(feature, index) in meta.features" :key="index" class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-success flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="text-base-content">{{ feature }}</span>
                                    </li>
                                </ul>
                                <div class="divider"></div>
                                <div class="flex items-center justify-between">
                                    <span class="text-lg font-semibold">Temel Paket Fiyatı</span>
                                    <span class="text-2xl font-bold text-primary">{{ formatPrice(product.price) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Services -->
                        <div class="card bg-base-100 shadow-xl" v-motion-slide-visible-left>
                            <div class="card-body">
                                <h2 class="card-title text-2xl mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-secondary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Ek Hizmetler
                                </h2>
                                <p class="text-base-content/70 mb-4">
                                    İhtiyaçlarınıza göre aşağıdaki hizmetleri paketinize ekleyebilirsiniz:
                                </p>
                                <div class="space-y-3">
                                    <div
                                        v-for="service in meta.services"
                                        :key="service.id"
                                        class="form-control"
                                    >
                                        <label class="label cursor-pointer justify-start gap-4 p-4 rounded-lg hover:bg-base-200 transition-colors"
                                               :class="{ 'bg-primary/10 border-2 border-primary': selectedServices.includes(service.id) }">
                                            <input
                                                type="checkbox"
                                                :checked="selectedServices.includes(service.id)"
                                                @change="toggleService(service.id)"
                                                class="checkbox checkbox-primary"
                                            />
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <span class="label-text font-semibold text-base">{{ service.name }}</span>
                                                    <span class="text-primary font-bold">{{ formatPrice(service.price) }}</span>
                                                </div>
                                                <p class="text-sm text-base-content/70">{{ service.description }}</p>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column - Order Summary -->
                    <div class="lg:sticky lg:top-24 h-fit">
                        <div class="card bg-gradient-to-br from-primary to-secondary text-primary-content shadow-2xl" v-motion-slide-visible-right>
                            <div class="card-body">
                                <h2 class="card-title text-2xl mb-6">Sipariş Özeti</h2>

                                <!-- Quantity Selector -->
                                <div class="form-control mb-6">
                                    <label class="label">
                                        <span class="label-text text-primary-content/90">Adet</span>
                                    </label>
                                    <div class="flex items-center gap-3">
                                        <button
                                            @click="quantity = Math.max(1, quantity - 1)"
                                            class="btn btn-circle btn-sm bg-white/20 border-white/40 hover:bg-white/30"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" />
                                            </svg>
                                        </button>
                                        <input
                                            v-model.number="quantity"
                                            type="number"
                                            min="1"
                                            class="input input-bordered w-20 text-center bg-white/20 border-white/40 text-primary-content font-bold"
                                        />
                                        <button
                                            @click="quantity++"
                                            class="btn btn-circle btn-sm bg-white/20 border-white/40 hover:bg-white/30"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Price Breakdown -->
                                <div class="space-y-3 mb-6">
                                    <div class="flex justify-between text-primary-content/90">
                                        <span>Temel Paket ({{ quantity }}x)</span>
                                        <span>{{ formatPrice(product.price * quantity) }}</span>
                                    </div>

                                    <div v-if="selectedServices.length > 0" class="divider my-2"></div>

                                    <div
                                        v-for="serviceId in selectedServices"
                                        :key="serviceId"
                                        class="flex justify-between text-primary-content/90 text-sm"
                                    >
                                        <span>{{ meta.services?.find(s => s.id === serviceId)?.name }} ({{ quantity }}x)</span>
                                        <span>{{ formatPrice(meta.services?.find(s => s.id === serviceId)?.price * quantity) }}</span>
                                    </div>
                                </div>

                                <div class="divider"></div>

                                <!-- Total -->
                                <div class="flex justify-between items-center text-2xl font-bold mb-6">
                                    <span>Toplam</span>
                                    <span>{{ formatPrice(totalPrice) }}</span>
                                </div>

                                <!-- Add to Cart Button -->
                                <button
                                    @click="addToCart"
                                    class="btn btn-lg w-full bg-white text-primary hover:bg-base-100 border-none"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Sepete Ekle
                                </button>

                                <div class="alert alert-info bg-white/10 border-white/30 mt-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span class="text-sm">Fiyatlar KDV dahildir. Kurulum 15 iş günü içinde başlar.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Why Choose Us Section -->
                <div class="mt-16 card bg-base-100 shadow-xl" v-motion-pop-visible>
                    <div class="card-body">
                        <h2 class="card-title text-3xl mb-6 justify-center">Neden Hayat Bilişim?</h2>
                        <div class="grid md:grid-cols-3 gap-6">
                            <div class="text-center">
                                <div class="avatar placeholder mb-4">
                                    <div class="bg-primary text-primary-content rounded-full w-16">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="font-bold text-lg mb-2">10+ Yıl Deneyim</h3>
                                <p class="text-base-content/70">Sektörde kanıtlanmış uzmanlık ve referanslar</p>
                            </div>
                            <div class="text-center">
                                <div class="avatar placeholder mb-4">
                                    <div class="bg-secondary text-secondary-content rounded-full w-16">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="font-bold text-lg mb-2">Uzman Ekip</h3>
                                <p class="text-base-content/70">Alanında uzman danışmanlar ve teknik ekip</p>
                            </div>
                            <div class="text-center">
                                <div class="avatar placeholder mb-4">
                                    <div class="bg-accent text-accent-content rounded-full w-16">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <h3 class="font-bold text-lg mb-2">Kesintisiz Destek</h3>
                                <p class="text-base-content/70">7/24 teknik destek ve sürekli güncelleme garantisi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>
