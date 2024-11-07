<script setup>
import { Head, Link } from '@inertiajs/vue3';
import SiteLayout from '@/Layouts/SiteLayout.vue';
import Hero from '@/Components/Hero.vue';
import Features from '@/Components/Features.vue';
import Benefits from '@/Components/Benefits.vue';
import SocialProof from '@/Components/SocialProof.vue';
import Messaging from '@/Components/Messaging.vue';
import FAQ from '@/Components/FAQ.vue';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <SiteLayout title="Welcome">

        <!-- HERO SECTION -->
        <Hero />

        <div class="space-y-24 py-24 bg-gradient-to-t to-5% from-base-300 to-base-100">
            <!-- PRODUCT FEATURES & BENEFITS -->
            <Features />
            <Benefits />

            <!-- SOCIAL PROOF -->
            <SocialProof />

            <!-- MESSAGE -->
            <Messaging />

            <!-- FAQ -->
            <FAQ />
        </div>
    </SiteLayout>
</template>
