<template>
  <header class="flex h-14 items-center justify-between gap-3 border-b border-gray-200 bg-white px-4 shadow-sm sm:px-5">
    <div class="flex min-w-0 items-center gap-3">
      <button
        type="button"
        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xl transition hover:bg-gray-100 2xl:hidden"
        aria-label="Open sidebar"
        @click="$emit('toggleSidebar')"
      >
        <span class="flex flex-col gap-1" aria-hidden="true">
          <span class="h-0.5 w-5 rounded-full bg-gray-700"></span>
          <span class="h-0.5 w-5 rounded-full bg-gray-700"></span>
          <span class="h-0.5 w-5 rounded-full bg-gray-700"></span>
        </span>
      </button>

      <h1 class="truncate font-semibold text-gray-800">Vendor Dashboard</h1>
    </div>

    <div class="hidden max-w-md flex-1 md:flex md:mx-4 lg:mx-6">
      <input
        type="text"
        placeholder="Search products, orders..."
        class="w-full rounded-lg border bg-gray-50 px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
      />
    </div>

    <div class="flex shrink-0 items-center gap-3 sm:gap-4">
      <span class="text-xs uppercase tracking-wide text-gray-500">
        {{ locale }}
      </span>

      <button type="button" class="relative">
        !
        <span class="absolute -right-1 -top-1 h-2 w-2 rounded-full bg-red-500"></span>
      </button>

      <div class="flex cursor-pointer items-center gap-2">
        <div class="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-sm font-bold text-white">
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
