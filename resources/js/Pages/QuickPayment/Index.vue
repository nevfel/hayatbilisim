<template>
    <SiteLayout title="Hızlı Ödemeler">
        <Head title="Hızlı Ödemeler" />

        <div class="min-h-screen bg-base-200 py-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold">Hızlı Ödemeler</h1>
                </div>

                <!-- Create -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="card-title text-base">Hızlı Ödeme Linki Oluştur</h2>

                        <div class="grid md:grid-cols-2 gap-3 mt-2">
                            <div class="form-control">
                                <label class="label py-1"><span class="label-text text-xs font-medium">Firma Adı</span></label>
                                <input v-model="form.gon_adsoyad" class="input input-bordered input-sm" placeholder="Firma ünvanı" />
                            </div>
                            <div class="form-control">
                                <label class="label py-1"><span class="label-text text-xs font-medium">E-posta</span></label>
                                <input v-model="form.gon_email" class="input input-bordered input-sm" placeholder="email@firma.com" />
                            </div>
                            <div class="form-control">
                                <label class="label py-1"><span class="label-text text-xs font-medium">Telefon (opsiyonel)</span></label>
                                <input v-model="form.gon_phone" class="input input-bordered input-sm" placeholder="05xx..." />
                            </div>
                            <div class="form-control">
                                <label class="label py-1"><span class="label-text text-xs font-medium">Tutar (TL)</span></label>
                                <input v-model.number="form.amount" type="number" min="1" step="1" class="input input-bordered input-sm" placeholder="1000" />
                            </div>
                            <div class="form-control md:col-span-2">
                                <label class="label py-1"><span class="label-text text-xs font-medium">Açıklama</span></label>
                                <input v-model="form.description" class="input input-bordered input-sm" placeholder="Ödeme açıklaması" />
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap gap-2 items-center">
                            <button class="btn btn-sm btn-primary" :disabled="creating" @click="createLink">
                                {{ creating ? 'Oluşturuluyor...' : 'Link Oluştur' }}
                            </button>
                            <div v-if="createError" class="text-sm text-error">{{ createError }}</div>
                        </div>

                        <div v-if="created?.payment_link" class="mt-4 p-3 rounded-lg bg-base-200">
                            <div class="text-xs font-medium mb-1">Ödeme Linki</div>
                            <div class="flex flex-col md:flex-row gap-2 md:items-center">
                                <input class="input input-bordered input-sm w-full" :value="created.payment_link" readonly />
                                <button class="btn btn-sm" @click="copy(created.payment_link)">Kopyala</button>
                                <a class="btn btn-sm btn-outline" :href="created.payment_link" target="_blank" rel="noreferrer">Aç</a>
                            </div>
                            <div class="text-xs text-base-content/70 mt-2">
                                Ödeme No: <span class="font-mono">{{ created.payment_number }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- List -->
                <div class="card bg-base-100 shadow-sm">
                    <div class="card-body p-5">
                        <h2 class="card-title text-base">Son Ödemeler</h2>

                        <div class="overflow-x-auto mt-2">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Firma</th>
                                        <th>E-posta</th>
                                        <th>Tutar</th>
                                        <th>Durum</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="p in quickPayments.data" :key="p.id">
                                        <td class="font-mono">{{ p.payment_number }}</td>
                                        <td>{{ p.gon_adsoyad }}</td>
                                        <td>{{ p.gon_email }}</td>
                                        <td>{{ formatCurrency(p.amount) }}</td>
                                        <td>
                                            <span class="badge" :class="statusBadge(p)">{{ p.status }}</span>
                                        </td>
                                        <td class="text-right">
                                            <a class="btn btn-xs btn-outline" :href="route('quick-payment.show', p.payment_number)" target="_blank">Detay</a>
                                        </td>
                                    </tr>
                                    <tr v-if="!quickPayments.data?.length">
                                        <td colspan="6" class="text-center text-sm text-base-content/60 py-6">Kayıt yok.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </SiteLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import SiteLayout from '@/Layouts/SiteLayout.vue';

const props = defineProps({
    quickPayments: { type: Object, required: true },
    filters: { type: Object, default: () => ({}) },
});

const form = ref({
    amount: 1000,
    gon_email: '',
    gon_adsoyad: '',
    gon_phone: '',
    description: '',
});

const creating = ref(false);
const createError = ref('');
const created = ref(null);

const formatCurrency = (amount) => new Intl.NumberFormat('tr-TR', { style: 'currency', currency: 'TRY' }).format(amount);

const statusBadge = (p) => {
    if (p.payment_ok && p.status === 'completed') return 'badge-success';
    if (p.status === 'failed') return 'badge-error';
    return 'badge-ghost';
};

const createLink = async () => {
    createError.value = '';
    created.value = null;
    creating.value = true;

    try {
        const res = await window.axios.post(route('admin.quick-payments.create'), form.value);
        created.value = res.data;
    } catch (e) {
        createError.value = e?.response?.data?.message || e?.message || 'Link oluşturulamadı.';
    } finally {
        creating.value = false;
    }
};

const copy = async (text) => {
    try {
        await navigator.clipboard.writeText(text);
    } catch {
        // ignore
    }
};
</script>

