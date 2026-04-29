<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const name = ref('')

const submit = () => {
  router.post('/onboarding/step1', {
    name: name.value
  })
}
</script>

<template>
  <div class="max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4">Welcome 👋</h1>

    <input
      v-model="name"
      type="text"
      placeholder="Your name"
      class="border p-2 w-full mb-4"
    />

    <button
      @click="submit"
      class="bg-blue-500 text-white px-4 py-2 rounded"
    >
      Next
    </button>
  </div>
</template>