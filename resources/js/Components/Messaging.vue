<template>
  <div class="mx-auto max-w-4xl p-6 bg-white shadow-md rounded-lg" id="messaging">
    <h2 class="text-2xl font-bold mb-6 text-center">BİZE ULAŞIN</h2>

    <form @submit.prevent="submitForm">
      <!-- İsim Alanı -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="name">İsim</label>
        <input v-model="form.name" type="text" id="name"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          required />
      </div>

      <!-- E-posta Alanı -->
      <div class="mb-4">
        <label class="block text-gray-700 font-semibold mb-2" for="email">E-posta</label>
        <input v-model="form.email" type="email" id="email"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          required />
      </div>

      <!-- Mesaj Alanı -->
      <div class="mb-6">
        <label class="block text-gray-700 font-semibold mb-2" for="message">Mesaj</label>
        <textarea v-model="form.message" id="message" rows="4"
          class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          required></textarea>
      </div>

      <!-- Gönder Butonu -->

      <button type="submit" class="w-full btn btn-primary text-primary-content mt-4">
        GÖNDER
      </button>
    </form>

    <!-- Başarı Mesajı -->
    <p v-if="successMessage" class="mt-4 text-green-500 text-center">{{ successMessage }}</p>
  </div>
</template>

<script>
import { reactive, ref } from 'vue';

export default {
  setup() {
    // Reactive form verisi
    const form = reactive({
      name: '',
      email: '',
      message: '',
    });

    // Başarı mesajı için ref
    const successMessage = ref('');

    // Form gönderim fonksiyonu
    const submitForm = () => {
      console.log('Form gönderildi:', form);

      // Başarı mesajı ayarla
      successMessage.value = 'Mesajınız başarıyla gönderildi!';

      // Formu sıfırla
      form.name = '';
      form.email = '';
      form.message = '';
    };

    return {
      form,
      successMessage,
      submitForm,
    };
  },
};
</script>

<style scoped>
/* İhtiyacınıza göre ek stiller */
</style>