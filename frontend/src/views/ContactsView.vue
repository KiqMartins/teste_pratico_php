<script setup lang="ts">
import { ref, onMounted, computed } from 'vue';
import { useRouter } from 'vue-router';
import api from '../http/api';
import type { Contact, ContactsResponse } from '../types';

const router = useRouter();
const contacts = ref<Contact[]>([]);
const loading = ref<boolean>(true);
const selectedContacts = ref<number[]>([]); 

const currentPage = ref(1);
const totalContacts = ref(0);
const limit = 10;

const fetchContacts = async () => {
  loading.value = true;
  try {
    const response = await api.get<ContactsResponse>(`/contacts?page=${currentPage.value}&limit=${limit}`);
    contacts.value = response.data.data;
    totalContacts.value = response.data.total;
  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
};

const deleteContact = async (id: number) => {
  if (!confirm('Deseja remover este contato?')) return;
  try {
    await api.delete(`/contacts/${id}`);
    contacts.value = contacts.value.filter(c => c.id !== id);
    totalContacts.value--;
  } catch (e) {
    alert('Erro ao excluir');
  }
};

const toggleSelectAll = (e: Event) => {
  const isChecked = (e.target as HTMLInputElement).checked;
  if (isChecked) {
    selectedContacts.value = contacts.value.map(c => c.id);
  } else {
    selectedContacts.value = [];
  }
};

onMounted(() => {
  fetchContacts();
});

const getInitials = (name: string) => {
  return name
    .split(' ')
    .map(n => n[0])
    .slice(0, 2)
    .join('')
    .toUpperCase();
};

const avatarColors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-pink-500'];
const getAvatarColor = (id: number) => avatarColors[id % avatarColors.length];
</script>

<template>
  <div class="p-6 max-w-7xl mx-auto font-sans text-gray-700">
    
    <div class="flex justify-between items-center mb-6">
      <div class="flex items-center gap-2">
        <h1 class="text-2xl font-bold text-indigo-600">Contacts</h1>
        <span class="text-gray-400 text-sm font-medium">{{ totalContacts }} Total</span>

      </div>

      <div class="flex gap-3">
        <button @click="$router.push('/contacts/new')" 
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm font-medium shadow-sm transition flex items-center gap-2">
          + Add Contact
        </button>
      </div>
    </div>

    <div class="bg-white border border-gray-200 rounded-t-lg p-3 flex items-center justify-between">
      <div class="flex items-center gap-4 pl-2">
        <input type="checkbox" @change="toggleSelectAll" class="rounded border-gray-300 text-blue-500 focus:ring-0 cursor-pointer">
        <span class="text-xs font-bold text-gray-400 uppercase tracking-wider">Basic Info</span>
      </div>
      <div class="flex gap-10 text-xs font-bold text-gray-400 uppercase tracking-wider pr-10 hidden md:flex">
        <span class="w-32">Phone</span>
        <span class="w-32">Created Date</span>
      </div>
    </div>

    <div v-if="loading" class="bg-white p-10 text-center border-x border-b border-gray-200">
      <span class="text-gray-400 text-sm">Loading contacts...</span>
    </div>

    <div v-else-if="contacts.length === 0" class="bg-white p-16 text-center border-x border-b border-gray-200">
      <div class="text-gray-300 text-6xl mb-4">📇</div>
      <p class="text-gray-500">No contacts found.</p>
      <button @click="$router.push('/contacts/new')" class="text-blue-500 text-sm font-bold mt-2 hover:underline">Add your first one</button>
    </div>

    <div v-else class="bg-white border-x border-b border-gray-200 rounded-b-lg shadow-sm">
      <div v-for="contact in contacts" :key="contact.id" 
           class="group flex items-center justify-between p-4 border-b border-gray-100 last:border-0 hover:bg-blue-50/30 transition-colors">
        
        <div class="flex items-center gap-4 flex-grow">
          <input type="checkbox" :value="contact.id" v-model="selectedContacts" 
                 class="rounded border-gray-300 text-blue-500 focus:ring-0 cursor-pointer opacity-40 group-hover:opacity-100 transition-opacity">
          
          <div :class="`h-10 w-10 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm ${getAvatarColor(contact.id)}`">
            {{ getInitials(contact.name) }}
          </div>

          <div>
            <h3 class="text-sm font-medium text-gray-700 group-hover:text-blue-600 cursor-pointer" @click="$router.push(`/contacts/${contact.id}/edit`)">
              {{ contact.name }}
            </h3>
            <p class="text-xs text-gray-400">{{ contact.email }}</p>
          </div>
          
          <div class="hidden lg:flex gap-2 ml-4">
             <span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-[10px] rounded border border-gray-200">Customer</span>
          </div>
        </div>

        <div class="hidden md:flex gap-10 items-center pr-4">
          <span class="w-32 text-sm text-gray-600 font-mono text-right">
             {{ contact.phones && contact.phones.length > 0 ? contact.phones[0].number : '-' }}
             <span v-if="contact.phones && contact.phones.length > 1" class="text-[10px] text-gray-400 ml-1">(+{{contact.phones.length - 1}})</span>
          </span>
          
          <span class="w-32 text-xs text-gray-500 text-right">Today</span>
        </div>

        <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity pl-4">
          <button @click="$router.push(`/contacts/${contact.id}/edit`)" title="Edit"
                  class="p-1.5 text-gray-400 hover:text-blue-600 hover:bg-blue-100 rounded">
            ✏️
          </button>
          <button @click="deleteContact(contact.id)" title="Delete"
                  class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded">
            🗑️
          </button>
        </div>

      </div>
      
      <div class="bg-gray-50 p-3 flex justify-between items-center text-xs text-gray-500 rounded-b-lg">
        <div>Showing {{ contacts.length }} of {{ totalContacts }}</div>
        <div class="flex gap-2">
           <button :disabled="currentPage === 1" @click="currentPage--; fetchContacts()" 
                   class="px-2 py-1 border rounded bg-white hover:bg-gray-100 disabled:opacity-50">Prev</button>
           <button :disabled="contacts.length < limit" @click="currentPage++; fetchContacts()" 
                   class="px-2 py-1 border rounded bg-white hover:bg-gray-100 disabled:opacity-50">Next</button>
        </div>
      </div>
    </div>
  </div>
</template>