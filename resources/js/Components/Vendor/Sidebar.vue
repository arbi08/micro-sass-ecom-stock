<template>
  <!-- Overlay (mobile فقط) -->
  <div
    v-if="isOpen"
    class="fixed inset-0 bg-black/50 z-40 2xl:hidden"
    @click="$emit('close')"
  ></div>

  <!-- Sidebar -->
  <aside
    class="fixed 2xl:static top-0 left-0 z-50 h-full w-64 
           bg-gradient-to-b from-blue-950 to-blue-900 text-white 
           flex flex-col border-r border-blue-800
           transform transition-transform duration-300"
    :class="isOpen ? 'translate-x-0' : '-translate-x-full 2xl:translate-x-0'"
  >
    <!-- HEADER -->
    <div class="p-5 border-b border-blue-800">
      <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
          <h2 class="truncate text-lg font-bold tracking-wide">Vendor Panel</h2>
          <p class="text-xs text-blue-300">Management System</p>
        </div>
        <button
          type="button"
          class="rounded-lg px-2 py-1 text-sm text-blue-100 transition hover:bg-blue-800 2xl:hidden"
          @click="$emit('close')"
        >
          x
        </button>
      </div>
    </div>

    <!-- SCROLL AREA -->
    <div class="flex-1 overflow-y-auto p-3 space-y-6" style="scrollbar-width: none;">

      <!-- DASHBOARD (TOP ITEM) -->
      <Link
        href="/vendor/dashboard"
        class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm font-medium transition"
        :class="isActive('/vendor/dashboard') ? 'bg-blue-800' : 'hover:bg-blue-800/60'"
      >
        📊 <span>{{ $t('dashboard') }}</span>
      </Link>

      <!-- SECTIONS -->
      <div v-for="section in menu" :key="section.title">

        <p class="text-[11px] uppercase text-blue-400 font-semibold tracking-wider px-2 mb-2">
          {{ $t(section.title) }}
        </p>

        <nav class="space-y-1">
          <Link
            v-for="item in section.items"
            :key="item.name"
            :href="item.route"
            class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm transition"
            :class="isActive(item.route) ? 'bg-blue-800' : 'hover:bg-blue-800/60 hover:translate-x-1'"
          >
            <span class="w-2 h-2 rounded-full bg-blue-400"></span>
            {{ $t(item.name) }}
          </Link>
        </nav>

      </div>
    </div>

    <!-- FOOTER: LANGUAGE -->
    <div class="p-4 border-t border-blue-800">
      <select
        v-model="locale"
        class="w-full p-2 rounded-lg bg-blue-800 text-white text-sm focus:outline-none"
      >
        <option value="en">English</option>
        <option value="fr">Français</option>
        <option value="ar">العربية</option>
      </select>
    </div>

  </aside>
</template>

<script setup lang="ts">
import { useI18n } from 'vue-i18n'
import { Link, usePage } from '@inertiajs/vue3'
defineProps({
  isOpen: Boolean
})
defineEmits(['close'])
const { locale } = useI18n()
const page = usePage()

// Active route helper
const isActive = (route: string) => {
  return page.url.startsWith(route)
}

interface MenuItem {
  name: string
  route: string
}

interface MenuSection {
  title: string
  items: MenuItem[]
}

const menu: MenuSection[] = [
  {
    title: 'catalog_management',
    items: [
      { name: 'products', route: '/vendor/products' },
      { name: 'types', route: '/vendor/types' },
      { name: 'catalogs', route: '/vendor/catalogs' },
      { name: 'sub_catalogs', route: '/vendor/sub-catalogs' }
    ]
  },
  {
    title: 'suppliers',
    items: [
      { name: 'suppliers', route: '#' }
    ]
  },
  {
    title: 'sales',
    items: [
      { name: 'orders', route: '#' },
      { name: 'invoices', route: '#' },
      { name: 'payments', route: '#' },
      { name: 'conversations', route: '#' }
    ]
  },
  {
    title: 'purchasing',
    items: [
      { name: 'purchases', route: '#' },
      { name: 'shipments', route: '#' }
    ]
  },
  {
    title: 'inventory',
    items: [
      { name: 'stocks', route: '#' }
    ]
  }
]
</script>
