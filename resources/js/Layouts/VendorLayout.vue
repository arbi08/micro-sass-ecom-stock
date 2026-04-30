<template>
  <div class="flex h-screen">
    <Sidebar />

    <div class="flex-1 flex flex-col">
      <Navbar />

      <main class="p-6 bg-gray-100 flex-1 overflow-y-auto">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
    import Sidebar from '../components/vendor/Sidebar.vue'
    import Navbar from '../components/vendor/Navbar.vue'
    import { watch } from 'vue'
    import { useI18n } from 'vue-i18n'

    const { locale } = useI18n()
    watch(locale, (lang) => {
        document.dir = lang === 'ar' ? 'rtl' : 'ltr'
    })
</script>