<template>
    <SiteLayout title="Sipariş Oluştur">
        <Head title="Sipariş Oluştur" />

        <div class="min-h-screen bg-base-200 py-8">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <h1 class="text-2xl font-bold mb-6">Sipariş Bilgileri</h1>

                <form @submit.prevent="submit" class="space-y-4">
                    <div class="grid lg:grid-cols-3 gap-4">
                        <!-- Sol Kolon: Form Alanları -->
                        <div class="lg:col-span-2 space-y-4">
                            <!-- Sepet Özeti (Compact) -->
                            <div class="card bg-base-100 shadow-sm">
                                <div class="card-body p-4">
                                    <h2 class="card-title text-base mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        Sepet Özeti
                                    </h2>
                                    <div class="space-y-1.5 text-sm">
                                        <div
                                            v-for="item in cartItems"
                                            :key="item.id"
                                            class="flex justify-between"
                                        >
                                            <span class="text-base-content/70">{{ item.product.name }} × {{ item.quantity }}</span>
                                            <span class="font-medium">{{ formatPrice(calculateItemTotal(item)) }}</span>
                                        </div>
                                        <div v-if="cartItems.some(item => item.selected_services && item.selected_services.length > 0)" class="divider my-2"></div>
                                        <div class="flex justify-between text-base font-bold pt-2 border-t">
                                            <span>Toplam</span>
                                            <span class="text-primary">{{ formatPrice(total) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Fatura Bilgileri -->
                            <div class="card bg-base-100 shadow-sm">
                                <div class="card-body p-4">
                                    <h2 class="card-title text-base mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        Fatura Bilgileri
                                    </h2>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="form-control">
                                            <label class="label py-1">
                                                <span class="label-text text-xs font-medium">
                                                    Ad Soyad <span class="text-error">*</span>
                                                </span>
                                            </label>
                                            <input
                                                v-model="form.billing_name"
                                                type="text"
                                                required
                                                placeholder="Adınız Soyadınız"
                                                class="input input-bordered input-sm"
                                                :class="{ 'input-error': form.errors.billing_name }"
                                            />
                                            <label v-if="form.errors.billing_name" class="label py-1">
                                                <span class="label-text-alt text-error">{{ form.errors.billing_name }}</span>
                                            </label>
                                        </div>
                                        <div class="form-control">
                                            <label class="label py-1">
                                                <span class="label-text text-xs font-medium">
                                                    E-posta <span class="text-error">*</span>
                                                </span>
                                            </label>
                                            <input
                                                v-model="form.billing_email"
                                                type="email"
                                                required
                                                placeholder="ornek@email.com"
                                                class="input input-bordered input-sm"
                                                :class="{ 'input-error': form.errors.billing_email }"
                                            />
                                            <label v-if="form.errors.billing_email" class="label py-1">
                                                <span class="label-text-alt text-error">{{ form.errors.billing_email }}</span>
                                            </label>
                                        </div>
                                        <div class="form-control">
                                            <label class="label py-1">
                                                <span class="label-text text-xs font-medium">Telefon</span>
                                            </label>
                                            <input
                                                v-model="form.billing_phone"
                                                type="tel"
                                                placeholder="05XX XXX XX XX"
                                                class="input input-bordered input-sm"
                                            />
                                        </div>
                                        <div class="form-control">
                                            <label class="label py-1">
                                                <span class="label-text text-xs font-medium">Şehir</span>
                                            </label>
                                            <input
                                                v-model="form.billing_city"
                                                type="text"
                                                placeholder="İstanbul"
                                                class="input input-bordered input-sm"
                                            />
                                        </div>
                                        <div class="form-control md:col-span-2">
                                            <label class="label py-1">
                                                <span class="label-text text-xs font-medium">Adres</span>
                                            </label>
                                            <textarea
                                                v-model="form.billing_address"
                                                rows="2"
                                                placeholder="Adres bilgilerinizi giriniz"
                                                class="textarea textarea-bordered textarea-sm"
                                            ></textarea>
                                        </div>
                                        <div class="form-control">
                                            <label class="label py-1">
                                                <span class="label-text text-xs font-medium">Posta Kodu</span>
                                            </label>
                                            <input
                                                v-model="form.billing_postal_code"
                                                type="text"
                                                placeholder="34000"
                                                class="input input-bordered input-sm"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Teslimat Bilgileri (Collapsible) -->
                            <div class="card bg-base-100 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="form-control">
                                        <label class="label cursor-pointer justify-start gap-2 py-2">
                                            <input
                                                v-model="showShipping"
                                                type="checkbox"
                                                class="checkbox checkbox-sm checkbox-primary"
                                            />
                                            <span class="label-text text-sm font-medium">
                                                Teslimat adresi fatura adresinden farklı
                                            </span>
                                        </label>
                                    </div>
                                    <div v-if="showShipping" class="mt-3 pt-3 border-t">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            <div class="form-control">
                                                <label class="label py-1">
                                                    <span class="label-text text-xs font-medium">Ad Soyad</span>
                                                </label>
                                                <input
                                                    v-model="form.shipping_name"
                                                    type="text"
                                                    placeholder="Ad Soyad"
                                                    class="input input-bordered input-sm"
                                                />
                                            </div>
                                            <div class="form-control">
                                                <label class="label py-1">
                                                    <span class="label-text text-xs font-medium">Şehir</span>
                                                </label>
                                                <input
                                                    v-model="form.shipping_city"
                                                    type="text"
                                                    placeholder="İstanbul"
                                                    class="input input-bordered input-sm"
                                                />
                                            </div>
                                            <div class="form-control md:col-span-2">
                                                <label class="label py-1">
                                                    <span class="label-text text-xs font-medium">Adres</span>
                                                </label>
                                                <textarea
                                                    v-model="form.shipping_address"
                                                    rows="2"
                                                    placeholder="Teslimat adresi"
                                                    class="textarea textarea-bordered textarea-sm"
                                                ></textarea>
                                            </div>
                                            <div class="form-control">
                                                <label class="label py-1">
                                                    <span class="label-text text-xs font-medium">Posta Kodu</span>
                                                </label>
                                                <input
                                                    v-model="form.shipping_postal_code"
                                                    type="text"
                                                    placeholder="34000"
                                                    class="input input-bordered input-sm"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sözleşme Onayı -->
                            <div class="card bg-base-100 shadow-sm">
                                <div class="card-body p-4">
                                    <div class="form-control">
                                        <label class="label cursor-pointer justify-start gap-2">
                                            <input
                                                v-model="form.terms_accepted"
                                                type="checkbox"
                                                required
                                                class="checkbox checkbox-sm checkbox-primary"
                                            />
                                            <span class="label-text text-xs">
                                                <Link :href="route('terms')" target="_blank" class="link link-primary">
                                                    Kullanım Şartları
                                                </Link>
                                                ve
                                                <Link :href="route('kvkk')" target="_blank" class="link link-primary">
                                                    KVKK Aydınlatma Metni
                                                </Link>
                                                'ni okudum ve kabul ediyorum. <span class="text-error">*</span>
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Sağ Kolon: Özet ve Butonlar -->
                        <div class="lg:col-span-1">
                            <div class="card bg-gradient-to-br from-primary to-secondary text-primary-content shadow-lg sticky top-24">
                                <div class="card-body p-4">
                                    <h3 class="card-title text-lg mb-3">Sipariş Özeti</h3>
                                    <div class="space-y-2 text-sm mb-4">
                                        <div class="flex justify-between">
                                            <span>Ara Toplam</span>
                                            <span>{{ formatPrice(total) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>KDV</span>
                                            <span>Dahil</span>
                                        </div>
                                        <div class="divider my-2"></div>
                                        <div class="flex justify-between text-lg font-bold">
                                            <span>Toplam</span>
                                            <span>{{ formatPrice(total) }}</span>
                                        </div>
                                    </div>
                                    <div class="card-actions flex-col gap-2">
                                        <button
                                            type="submit"
                                            :disabled="form.processing || !form.terms_accepted"
                                            class="btn btn-block btn-sm bg-white text-primary hover:bg-base-100 border-none"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                            {{ form.processing ? 'İşleniyor...' : 'Ödemeye Geç' }}
                                        </button>
                                        <Link
                                            :href="route('cart.index')"
                                            class="btn btn-block btn-sm btn-ghost text-primary-content"
                                        >
                                            Sepete Dön
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </SiteLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';
import { Head } from '@inertiajs/vue3';
import SiteLayout from '@/Layouts/SiteLayout.vue';

const props = defineProps({
    cartItems: {
        type: Array,
        required: true,
    },
    total: {
        type: Number,
        required: true,
    },
});

const showShipping = ref(false);

const form = useForm({
    billing_name: '',
    billing_email: '',
    billing_phone: '',
    billing_address: '',
    billing_city: '',
    billing_postal_code: '',
    billing_country: 'TR',
    shipping_name: '',
    shipping_address: '',
    shipping_city: '',
    shipping_postal_code: '',
    terms_accepted: false,
});

const formatPrice = (price) => {
    return new Intl.NumberFormat('tr-TR', {
        style: 'currency',
        currency: 'TRY'
    }).format(price);
};

const calculateItemTotal = (item) => {
    let total = item.product.price * item.quantity;
    if (item.selected_services) {
        item.selected_services.forEach(service => {
            total += service.price * item.quantity;
        });
    }
    return total;
};

const submit = () => {
    form.post(route('orders.store'));
};
</script>

