<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const company_name = ref('')
const loading = ref(false)

const submit = () => {
  loading.value = true

  router.post('/onboarding/step2', {
    company_name: company_name.value
  }, {
    onFinish: () => loading.value = false
  })
}
</script>

<template>
  <div class="max-w-md mx-auto mt-10">
    <h1 class="text-2xl font-bold mb-4">Create your company 🏢</h1>

    <input
      v-model="company_name"
      type="text"
      placeholder="Company name"
      class="border p-2 w-full mb-4"
    />

    <button
      @click="submit"
      :disabled="loading"
      class="bg-green-500 text-white px-4 py-2 rounded w-full"
    >
      <span v-if="!loading">Create & Continue</span>
      <span v-else>Loading...</span>
    </button>
  </div>
</template>