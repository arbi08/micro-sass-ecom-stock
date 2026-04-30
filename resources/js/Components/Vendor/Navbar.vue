<template>
  <header
    class="h-14 bg-white border-b border-gray-200 flex items-center justify-between px-5 shadow-sm"
  >
    <!-- Left: Title / Breadcrumb -->
    <div class="flex items-center gap-3">
      <h1 class="font-semibold text-gray-800">Vendor Dashboard</h1>
    </div>

    <!-- Center: Search -->
    <div class="hidden md:flex flex-1 max-w-md mx-6">
      <input
        type="text"
        placeholder="Search products, orders..."
        class="w-full px-3 py-1.5 text-sm border rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>

    <!-- Right: Actions -->
    <div class="flex items-center gap-4">

      <!-- Language -->
      <span class="text-xs text-gray-500 uppercase tracking-wide">
        {{ locale }}
      </span>

      <!-- Notifications -->
      <button class="relative">
        🔔
        <span class="absolute -top-1 -right-1 w-2 h-2 bg-red-500 rounded-full"></span>
      </button>

      <!-- User -->
      <div class="flex items-center gap-2 cursor-pointer">
        <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold">
          V
        </div>
      </div>

    </div>
  </header>
</template>

<script setup lang="ts">
import { useI18n } from 'vue-i18n'

const { locale } = useI18n()
</script>