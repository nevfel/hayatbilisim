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
      <button type="submit" :disabled="isLoading" 
        class="w-full btn btn-primary text-primary-content mt-4 disabled:opacity-50 disabled:cursor-not-allowed">
        {{ isLoading ? 'GÖNDERİLİYOR...' : 'GÖNDER' }}
      </button>
    </form>

    <!-- Başarı Mesajı -->
    <p v-if="successMessage" class="mt-4 text-green-500 text-center">{{ successMessage }}</p>
    
    <!-- Hata Mesajı -->
    <p v-if="errorMessage" class="mt-4 text-red-500 text-center">{{ errorMessage }}</p>
    
    <!-- Yükleniyor Durumu -->
    <div v-if="isLoading" class="mt-4 text-center">
      <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-blue-500"></div>
      <p class="mt-2 text-gray-600">Mesaj gönderiliyor...</p>
    </div>
  </div>
</template>

<script>
import { reactive, ref } from 'vue';
import axios from 'axios';

export default {
  setup() {
    // Reactive form verisi
    const form = reactive({
      name: '',
      email: '',
      message: '',
    });

    // Durum mesajları için ref'ler
    const successMessage = ref('');
    const errorMessage = ref('');
    const isLoading = ref(false);

    // Form gönderim fonksiyonu
    const submitForm = async () => {
      // Önceki mesajları temizle
      successMessage.value = '';
      errorMessage.value = '';
      isLoading.value = true;

      try {
        const response = await axios.post('/api/contact', {
          name: form.name,
          email: form.email,
          message: form.message,
        });

        if (response.data.success) {
          successMessage.value = response.data.message;
          // Formu sıfırla
          form.name = '';
          form.email = '';
          form.message = '';
        } else {
          errorMessage.value = response.data.message || 'Bir hata oluştu.';
        }
      } catch (error) {
        console.error('E-posta gönderim hatası:', error);
        
        if (error.response && error.response.data) {
          errorMessage.value = error.response.data.message || 'Mesaj gönderilirken bir hata oluştu.';
        } else {
          errorMessage.value = 'Bağlantı hatası. Lütfen internet bağlantınızı kontrol edin.';
        }
      } finally {
        isLoading.value = false;
      }
    };

    return {
      form,
      successMessage,
      errorMessage,
      isLoading,
      submitForm,
    };
  },
};
</script>

<style scoped>
/* İhtiyacınıza göre ek stiller */
</style>