<script setup>
import { Head } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Navbar from '@/Components/Navbar.vue';
import Footer from '@/Components/Footer.vue';

defineProps({
    title: String,
});
</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="min-h-screen flex flex-col">
            <Navbar />

            <!-- Page Content -->
            <main class="flex-1">
                <slot />
            </main>

            <Footer />
        </div>
    </div>
</template>
