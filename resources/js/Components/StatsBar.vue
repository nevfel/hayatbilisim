<script setup>
// TODO: Gercek musteriniz sayisini ve proje sayisini asagidaki rakamlara yansitiniz.
const stats = [
    { value: '10+', label: 'Yil Deneyim' },
    { value: '7/24', label: 'Teknik Destek' },
    { value: '99.9%', label: 'Surdurulebilirlik' },
    { value: '100+', label: 'Tamamlanan Proje' },
];
</script>

<template>
    <div class="bg-base-200 border-y border-base-300">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div v-for="stat in stats" :key="stat.label" v-motion-pop-visible>
                    <div class="text-3xl font-bold text-primary">{{ stat.value }}</div>
                    <div class="text-sm mt-1 opacity-70">{{ stat.label }}</div>
                </div>
            </div>
        </div>
    </div>
</template>
