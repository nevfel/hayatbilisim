<template>
    <component :is="layout">
        <Head title="Ürünler" />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-6">
                    <h1 class="text-3xl font-bold text-gray-900">Ürünler</h1>
                </div>

                <div v-if="products.length === 0" class="text-center py-12">
                    <p class="text-gray-500">Henüz ürün bulunmamaktadır.</p>
                </div>

                <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div
                        v-for="product in products"
                        :key="product.id"
                        class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow"
                    >
                        <div class="h-48 bg-gray-200 flex items-center justify-center">
                            <span v-if="!product.image" class="text-gray-400">Resim Yok</span>
                            <img v-else :src="product.image" :alt="product.name" class="h-full w-full object-cover" />
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ product.name }}</h3>
                            <p class="text-gray-600 mb-4 line-clamp-2">{{ product.description }}</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-indigo-600">{{ product.price }} ₺</span>
                                <span class="text-sm text-gray-500">Stok: {{ product.stock }}</span>
                            </div>
                            <div class="mt-4 flex gap-2">
                                <Link
                                    :href="route('products.show', product.id)"
                                    class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 text-center"
                                >
                                    Detay
                                </Link>
                                <button
                                    v-if="product.stock > 0"
                                    @click="addToCart(product.id)"
                                    class="flex-1 bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700"
                                >
                                    Sepete Ekle
                                </button>
                                <span v-else class="flex-1 bg-gray-300 text-gray-600 px-4 py-2 rounded-md text-center">
                                    Stokta Yok
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </component>
</template>

<script setup>
import { computed } from 'vue';
import { Head, Link, router, usePage } from '@inertiajs/vue3';
import AuthLayout from '@/Layouts/AuthLayout.vue';
import SiteLayout from '@/Layouts/SiteLayout.vue';

const page = usePage();

const props = defineProps({
    products: {
        type: Array,
        required: true,
    },
});

const layout = computed(() => {
    return page.props.user ? AuthLayout : SiteLayout;
});

const addToCart = (productId) => {
    router.post(route('cart.store'), {
        product_id: productId,
        quantity: 1,
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Başarı mesajı gösterilebilir
        },
    });
};
</script>

