<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import api from '../http/api';
import { AxiosError } from 'axios';

const router = useRouter();
const route = useRoute();

const isEditMode = computed(() => route.params.id !== undefined);
const contactId = route.params.id;

const loading = ref(false);
const errorMessage = ref('');

const form = ref({
  name: '',
  email: '',
  address: '',
  phones: [''] as string[] 
});

const loadContactData = async () => {
  if (!isEditMode.value) return;

  loading.value = true;
  try {
    const response = await api.get(`/contacts/${contactId}`);
    const data = response.data.data;

    form.value.name = data.name;
    form.value.email = data.email;
    form.value.address = data.address;
    
    if (data.phones && data.phones.length > 0) {
      form.value.phones = data.phones;
    } else {
      form.value.phones = [''];
    }
  } catch (error) {
    console.error(error);
    errorMessage.value = 'Erro ao carregar dados do contato.';
   
  } finally {
    loading.value = false;
  }
};

const addPhoneField = () => {
  form.value.phones.push('');
};

const removePhoneField = (index: number) => {
  form.value.phones.splice(index, 1);
};

const saveContact = async () => {
  loading.value = true;
  errorMessage.value = '';

  try {
    const payload = {
      ...form.value,
      phones: form.value.phones.filter(p => p.trim() !== '')
    };

    if (isEditMode.value) {
      await api.put(`/contacts/${contactId}`, payload);
    } else {
      await api.post('/contacts', payload);
    }

    router.push('/'); 
  } catch (error) {
    const err = error as AxiosError;
    if (err.response?.status === 409) {
      errorMessage.value = 'Já existe um contato com este e-mail.';
    } else {
      errorMessage.value = 'Erro ao salvar contato. Verifique os dados.';
    }
  } finally {
    loading.value = false;
  }
  
};

onMounted(() => {
  if (isEditMode.value) {
    loadContactData();
  }
});
</script>

<template>
  <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">
      {{ isEditMode ? 'Editar Contato' : 'Novo Contato' }}
    </h2>

    <form @submit.prevent="saveContact" class="space-y-6">
      
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
        <input v-model="form.name" type="text" required
               class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-primary focus:outline-none" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
        <input v-model="form.email" type="email" required
               class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-primary focus:outline-none" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Endereço Residencial</label>
        <input v-model="form.address" type="text" required
               class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-primary focus:outline-none" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-2">Telefones</label>
        <div v-for="(phone, index) in form.phones" :key="index" class="flex gap-2 mb-2">
          <input v-model="form.phones[index]" type="text" placeholder="(99) 99999-9999"
                 class="flex-1 px-4 py-2 border rounded-md focus:ring-2 focus:ring-primary focus:outline-none" />
          
          <button type="button" @click="removePhoneField(index)" 
                  class="text-red-500 hover:text-red-700 px-2 border border-red-200 rounded hover:bg-red-50"
                  v-if="form.phones.length > 1">
            ✕
          </button>
        </div>
        <button type="button" @click="addPhoneField" 
                class="text-sm text-primary hover:text-primary-dark font-medium flex items-center gap-1 mt-2">
          + Adicionar outro número
        </button>
      </div>

      <div v-if="errorMessage" class="bg-red-50 text-red-600 p-3 rounded text-sm">
        {{ errorMessage }}
      </div>

      <div class="flex justify-end gap-3 pt-4 border-t">
        <button type="button" @click="router.push('/')" 
                class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded transition">
          Cancelar
        </button>
        <button type="submit" :disabled="loading"
                class="bg-primary hover:bg-primary-dark text-white px-6 py-2 rounded shadow transition disabled:opacity-50">
          {{ loading ? 'Salvando...' : 'Salvar Contato' }}
        </button>
      </div>
    </form>
  </div>
</template>