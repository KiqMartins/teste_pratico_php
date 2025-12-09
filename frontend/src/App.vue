<script setup lang="ts">
import { RouterView, useRouter } from 'vue-router'
import { ref, onMounted } from 'vue';

const router = useRouter();
const userId = ref<string | null>(localStorage.getItem('user_id'));

const logout = () => {
  localStorage.removeItem('user_id');
  userId.value = null;
  router.push('/login');
};

</script>

<template>
  <div class="min-h-screen flex flex-col">
    <header class="bg-primary text-white shadow-lg" v-if="userId">
      <div class="container mx-auto px-4 py-4 flex justify-between items-center">
        <h1 class="text-xl font-bold flex items-center gap-2">
          <span>📒</span> My Agenda
        </h1>
        <nav class="flex gap-4">
          <RouterLink to="/" class="hover:text-primary-light transition">My Contacts</RouterLink>
          <button @click="logout" class="hover:text-red-200 transition">Exit</button>
        </nav>
      </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8">
      <RouterView />
    </main>

    <footer class="bg-white border-t py-4 text-center text-gray-500 text-sm">
      Teste Prático PHP - Frontend Vue.js
    </footer>
  </div>
</template>