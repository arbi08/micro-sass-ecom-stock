<template>
  <div class="flex h-screen overflow-hidden">
    <!-- Sidebar -->
    <Sidebar :isOpen="isOpen" @close="isOpen = false" />

    <div class="min-w-0 flex-1 flex flex-col">
      <Navbar @toggleSidebar="isOpen = !isOpen" />

      <main class="flex-1 overflow-y-auto bg-gray-100 p-3 sm:p-4 lg:p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup lang="ts">
  import Navbar from "@/Components/Vendor/Navbar.vue";
import Sidebar from "@/Components/Vendor/Sidebar.vue";
import { watch, ref } from "vue";
  import { useI18n } from "vue-i18n";
  const isOpen = ref(false);
  const { locale } = useI18n();
  watch(locale, (lang) => {
    document.dir = lang === "ar" ? "rtl" : "ltr";
  });
</script>
