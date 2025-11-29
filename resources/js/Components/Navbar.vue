<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { router } from '@inertiajs/vue3';

const logout = () => {
    router.post(route('logout'));
};

const isMobileMenuOpen = ref(false);

const toggleMobileMenu = () => {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
};
</script>

<template>
    <nav class="w-screen bg-base-100 z-50 sticky top-0 shadow-md">
        <div class="navbar max-w-7xl mx-auto bg-base-100 flex justify-between items-center px-4">
            <div class="flex items-center">
                <a :href="route('welcome')" class="flex items-center hover:opacity-80 transition-opacity">
                    <ApplicationLogo class="block h-9 w-auto" />
                    <span class="btn btn-ghost text-xl ml-2">{{ $page.props.appName }}</span>
                </a>
            </div>
            <div class="hidden md:flex">
                <ul class="menu menu-horizontal px-1">
                    <li>
                        <a :href="route('welcome')" :class="{ 'active': $page.url === '/' }">
                            Anasayfa
                        </a>
                    </li>
                    <li>
                        <a :href="route('product.show')" :class="{ 'active': $page.url === '/erp-cozumu' }">
                            ERP Çözümü
                        </a>
                    </li>
                    <li>
                        <a :href="route('cart.index')" :class="{ 'active': $page.url.startsWith('/cart') }">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Sepet
                        </a>
                    </li>
                    <li v-if="$page.props.user">
                        <a :href="route('orders.index')" :class="{ 'active': $page.url.startsWith('/orders') }">
                            Siparişlerim
                        </a>
                    </li>
                    <li>
                        <a href="/#messaging">Bize Ulaşın</a>
                    </li>
                    <li v-if="$page.props.user">
                        <details>
                            <summary>
                                Hesabım
                            </summary>
                            <ul class="p-2 bg-base-100 rounded-t-none z-50">
                                <li><a :href="route('dashboard')">Panel</a></li>
                                <li><a :href="route('profile.show')">Profil</a></li>
                                <li><a @click.prevent="logout">Çıkış</a></li>
                            </ul>
                        </details>
                    </li>
                    <li v-else>
                        <a :href="route('login')">Giriş</a>
                    </li>
                </ul>
            </div>
            <div class="md:hidden flex items-center">
                <button @click="toggleMobileMenu" class="btn btn-ghost" aria-label="Menüyü Aç/Kapat">
                    <svg v-if="!isMobileMenuOpen" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="opacity-0 transform -translate-y-2"
            enter-to-class="opacity-100 transform translate-y-0"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="opacity-100 transform translate-y-0"
            leave-to-class="opacity-0 transform -translate-y-2"
        >
            <div v-if="isMobileMenuOpen" class="md:hidden border-t border-base-300 bg-base-100">
                <ul class="menu menu-vertical px-2 py-4">
                    <li>
                        <a :href="route('welcome')" @click="toggleMobileMenu">Anasayfa</a>
                    </li>
                    <li>
                        <a :href="route('product.show')" @click="toggleMobileMenu">ERP Çözümü</a>
                    </li>
                    <li>
                        <a :href="route('cart.index')" @click="toggleMobileMenu">Sepet</a>
                    </li>
                    <li v-if="$page.props.user">
                        <a :href="route('orders.index')" @click="toggleMobileMenu">Siparişlerim</a>
                    </li>
                    <li>
                        <a href="/#messaging" @click="toggleMobileMenu">Bize Ulaşın</a>
                    </li>
                    <li v-if="$page.props.user">
                        <details>
                            <summary>
                                Hesabım
                            </summary>
                            <ul class="p-2 bg-base-100 rounded-t-none">
                                <li><a :href="route('dashboard')" @click="toggleMobileMenu">Panel</a></li>
                                <li><a :href="route('profile.show')" @click="toggleMobileMenu">Profil</a></li>
                                <li><a @click.prevent="logout">Çıkış</a></li>
                            </ul>
                        </details>
                    </li>
                    <li v-else>
                        <a :href="route('login')" @click="toggleMobileMenu">Giriş</a>
                    </li>
                </ul>
            </div>
        </transition>
    </nav>
</template>
