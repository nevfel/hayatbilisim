<template>
    <SiteLayout title="Havale Ödemesi">
        <Head title="Havale Ödemesi" />

        <div class="min-h-screen bg-gray-50 py-12">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md p-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">Havale ile Ödeme</h1>

                <div v-if="$page.props.flash.error" class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-md p-4">
                    {{ $page.props.flash.error }}
                </div>

                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                    <h2 class="text-lg font-semibold text-blue-900 mb-2">Banka Bilgileri</h2>
                    <div class="space-y-2 text-sm text-blue-800">
                        <p><strong>Banka:</strong> Örnek Banka A.Ş.</p>
                        <p><strong>Şube:</strong> 0001</p>
                        <p><strong>Hesap No:</strong> 1234567890</p>
                        <p><strong>IBAN:</strong> TR00 0000 0000 0000 0000 0000 00</p>
                        <p><strong>Hesap Sahibi:</strong> Örnek Şirket A.Ş.</p>
                    </div>
                </div>

                <div class="mb-6 bg-gray-50 p-4 rounded-md">
                    <p class="text-sm text-gray-600 mb-2"><strong>Ödeme Tutarı:</strong></p>
                    <p class="text-2xl font-bold text-indigo-600">{{ order.total_amount }} ₺</p>
                    <p class="text-xs text-gray-500 mt-1">Sipariş No: {{ order.order_number }}</p>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Havale Tarihi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            v-model="form.transfer_date"
                            required
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <div v-if="form.errors.transfer_date" class="mt-1 text-sm text-red-600">
                            {{ form.errors.transfer_date }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Havale Tutarı <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="number"
                            v-model="form.transfer_amount"
                            step="0.01"
                            min="0"
                            required
                            placeholder="0.00"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <div v-if="form.errors.transfer_amount" class="mt-1 text-sm text-red-600">
                            {{ form.errors.transfer_amount }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Banka Adı
                        </label>
                        <input
                            type="text"
                            v-model="form.bank_name"
                            placeholder="Örn: Ziraat Bankası"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <div v-if="form.errors.bank_name" class="mt-1 text-sm text-red-600">
                            {{ form.errors.bank_name }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Havale Dekontu (Opsiyonel)
                        </label>
                        <input
                            type="file"
                            @change="handleFileChange"
                            accept="image/*,.pdf"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        />
                        <p class="mt-1 text-xs text-gray-500">JPG, PNG veya PDF formatında (Max: 2MB)</p>
                        <div v-if="form.errors.transfer_receipt" class="mt-1 text-sm text-red-600">
                            {{ form.errors.transfer_receipt }}
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Notlar (Opsiyonel)
                        </label>
                        <textarea
                            v-model="form.notes"
                            rows="3"
                            placeholder="Ek bilgi veya notlarınız..."
                            class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        ></textarea>
                        <div v-if="form.errors.notes" class="mt-1 text-sm text-red-600">
                            {{ form.errors.notes }}
                        </div>
                    </div>

                    <div class="flex gap-4 pt-4">
                        <Link
                            :href="route('welcome')"
                            class="flex-1 px-6 py-3 border border-gray-300 rounded-md hover:bg-gray-50 text-center"
                        >
                            İptal
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex-1 px-6 py-3 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            <span v-if="form.processing">Gönderiliyor...</span>
                            <span v-else>Ödeme Bilgilerini Gönder</span>
                        </button>
                    </div>
                </form>

                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-200 rounded-md">
                    <p class="text-sm text-yellow-800">
                        <strong>Önemli:</strong> Havale işleminizi tamamladıktan sonra lütfen ödeme bilgilerinizi gönderin. 
                        Ödeme onaylandıktan sonra siparişiniz işleme alınacaktır.
                    </p>
                </div>
            </div>
            </div>
        </div>
    </SiteLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import SiteLayout from '@/Layouts/SiteLayout.vue';

const props = defineProps({
    order: Object,
    payment: Object,
});

const form = useForm({
    transfer_date: '',
    transfer_amount: props.order.total_amount,
    bank_name: '',
    transfer_receipt: null,
    notes: '',
});

const handleFileChange = (event) => {
    form.transfer_receipt = event.target.files[0];
};

const submit = () => {
    form.post(route('payment.confirm', props.order.id), {
        forceFormData: true,
    });
};
</script>

