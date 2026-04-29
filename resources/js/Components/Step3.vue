<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'

const loading = ref(false)

const finish = () => {
  loading.value = true

  router.post('/onboarding/finish', {}, {
    onFinish: () => loading.value = false
  })
}
</script>

<template>
  <div class="max-w-md mx-auto mt-10 text-center">
    <h1 class="text-2xl font-bold mb-4">You're all set 🎉</h1>

    <p class="mb-6 text-gray-600">
      Your company has been created successfully.<br />
      You can now start using the platform.
    </p>

    <button
      @click="finish"
      :disabled="loading"
      class="bg-blue-600 text-white px-6 py-3 rounded w-full"
    >
      <span v-if="!loading">Go to Dashboard 🚀</span>
      <span v-else>Loading...</span>
    </button>
  </div>
</template>