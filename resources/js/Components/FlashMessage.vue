<script setup>
import { ref, computed, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const show = ref(false);

const flash = computed(() => page.props.flash || {});

const message = computed(() => {
    return flash.value.success || flash.value.error || flash.value.info || flash.value.warning || '';
});

const messageType = computed(() => {
    if (flash.value.success) return 'success';
    if (flash.value.error) return 'error';
    if (flash.value.warning) return 'warning';
    if (flash.value.info) return 'info';
    return 'info';
});

onMounted(() => {
    if (message.value) {
        show.value = true;
        // 5 saniye sonra otomatik kapat
        setTimeout(() => {
            show.value = false;
        }, 5000);
    }
});
</script>

<template>
    <div v-if="show && message" class="fixed top-4 right-4 z-50 max-w-md animate-fade-in">
        <div
            class="rounded-lg shadow-lg p-4 flex items-start space-x-3"
            :class="{
                'bg-green-50 border border-green-200': messageType === 'success',
                'bg-red-50 border border-red-200': messageType === 'error',
                'bg-yellow-50 border border-yellow-200': messageType === 'warning',
                'bg-blue-50 border border-blue-200': messageType === 'info',
            }"
        >
            <!-- Icon -->
            <div class="flex-shrink-0">
                <!-- Success Icon -->
                <svg
                    v-if="messageType === 'success'"
                    class="h-6 w-6 text-green-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <!-- Error Icon -->
                <svg
                    v-if="messageType === 'error'"
                    class="h-6 w-6 text-red-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <!-- Warning Icon -->
                <svg
                    v-if="messageType === 'warning'"
                    class="h-6 w-6 text-yellow-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>

                <!-- Info Icon -->
                <svg
                    v-if="messageType === 'info'"
                    class="h-6 w-6 text-blue-500"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Message -->
            <div class="flex-1">
                <p
                    class="text-sm font-medium"
                    :class="{
                        'text-green-800': messageType === 'success',
                        'text-red-800': messageType === 'error',
                        'text-yellow-800': messageType === 'warning',
                        'text-blue-800': messageType === 'info',
                    }"
                >
                    {{ message }}
                </p>
            </div>

            <!-- Close Button -->
            <button
                @click="show = false"
                class="flex-shrink-0 inline-flex text-gray-400 hover:text-gray-600 focus:outline-none"
            >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
    </div>
</template>

<style scoped>
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-1rem);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
</style>
