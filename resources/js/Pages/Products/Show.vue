<template>
    <component :is="layout">
        <Head :title="product.name" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="md:flex">
                        <div class="md:w-1/2">
                            <div class="h-96 bg-gray-200 flex items-center justify-center">
                                <span v-if="!product.image" class="text-gray-400 text-2xl">Resim Yok</span>
                                <img v-else :src="product.image" :alt="product.name" class="h-full w-full object-cover" />
                            </div>
                        </div>
                        <div class="md:w-1/2 p-8">
                            <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ product.name }}</h1>
                            <p class="text-gray-600 mb-6">{{ product.description }}</p>
                            <div class="mb-6">
                                <span class="text-3xl font-bold text-indigo-600">{{ product.price }} ₺</span>
                                <span class="ml-4 text-sm text-gray-500">Stok: {{ product.stock }}</span>
                            </div>
                            <div class="mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Adet</label>
                                <input
                                    v-model.number="quantity"
                                    type="number"
                                    min="1"
                                    :max="product.stock"
                                    class="w-24 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                />
                            </div>
                            <div class="flex gap-4">
                                <button
                                    v-if="product.stock > 0"
                                    @click="addToCart"
                                    class="flex-1 bg-indigo-600 text-white px-6 py-3 rounded-md hover:bg-indigo-700 font-medium"
                                >
                                    Sepete Ekle
                                </button>
                                <span v-else class="flex-1 bg-gray-300 text-gray-600 px-6 py-3 rounded-md text-center font-medium">
                                    Stokta Yok
                                </span>
                                <Link
                                    :href="route('products.index')"
                                    class="px-6 py-3 border border-gray-300 rounded-md hover:bg-gray-50"
                                >
                                    Geri
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import SiteLayout from '@/Layouts/SiteLayout.vue';

const page = usePage();

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
});

const layout = computed(() => {
    return page.props.user ? AuthLayout : SiteLayout;
});

const quantity = ref(1);

const addToCart = () => {
    router.post(route('cart.store'), {
        product_id: props.product.id,
        quantity: quantity.value,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            router.visit(route('cart.index'));
        },
    });
};
</script>

