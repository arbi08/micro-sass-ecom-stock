<script setup lang="ts">
import { computed, ref } from 'vue'
import VendorLayout from '@/Layouts/VendorLayout.vue'

defineOptions({
    layout: VendorLayout,
})

interface AddedType {
    id: number
    name: string
    category: string
    products: number
    status: 'Active' | 'Draft' | 'Archived'
    createdAt: string
}

const showTypesTable = ref(false)
const search = ref('')
const categoryFilter = ref('All')
const statusFilter = ref('All')
const sortBy = ref('newest')

const addedTypes: AddedType[] = [
    { id: 1, name: 'Long Sleeves', category: 'Clothing', products: 18, status: 'Active', createdAt: '2026-04-30' },
    { id: 2, name: 'Tank Tops', category: 'Clothing', products: 12, status: 'Active', createdAt: '2026-04-29' },
    { id: 3, name: 'Sportswear', category: 'Fitness', products: 26, status: 'Draft', createdAt: '2026-04-28' },
    { id: 4, name: 'Electronics', category: 'Devices', products: 9, status: 'Active', createdAt: '2026-04-27' },
    { id: 5, name: 'Beauty', category: 'Cosmetics', products: 14, status: 'Archived', createdAt: '2026-04-26' },
    { id: 6, name: 'Health', category: 'Wellness', products: 7, status: 'Active', createdAt: '2026-04-25' },
]

const categories = computed(() => [
    'All',
    ...Array.from(new Set(addedTypes.map((type) => type.category))),
])

const statuses = ['All', 'Active', 'Draft', 'Archived']

const filteredTypes = computed(() => {
    const term = search.value.trim().toLowerCase()

    return addedTypes
        .filter((type) => {
            const matchesSearch =
                !term ||
                type.name.toLowerCase().includes(term) ||
                type.category.toLowerCase().includes(term)
            const matchesCategory =
                categoryFilter.value === 'All' || type.category === categoryFilter.value
            const matchesStatus =
                statusFilter.value === 'All' || type.status === statusFilter.value

            return matchesSearch && matchesCategory && matchesStatus
        })
        .sort((a, b) => {
            if (sortBy.value === 'name') {
                return a.name.localeCompare(b.name)
            }

            if (sortBy.value === 'products') {
                return b.products - a.products
            }

            return new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
        })
})

const activeTypes = computed(() =>
    addedTypes.filter((type) => type.status === 'Active').length,
)

const totalProducts = computed(() =>
    addedTypes.reduce((total, type) => total + type.products, 0),
)

function statusClass(status: AddedType['status']) {
    return {
        Active: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        Draft: 'bg-amber-50 text-amber-700 ring-amber-200',
        Archived: 'bg-slate-100 text-slate-600 ring-slate-200',
    }[status]
}
</script>

<template>
    <section class="min-h-full bg-slate-50">
        <div class="mx-auto w-full max-w-7xl px-4 py-4 sm:px-6 lg:px-8 lg:py-6">
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Catalog management</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-950 sm:text-3xl">Products</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            Manage products and review the types you added to the catalog.
                        </p>
                    </div>

                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                        <button
                            type="button"
                            class="inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                            @click="showTypesTable = !showTypesTable"
                        >
                            {{ showTypesTable ? 'Products view' : 'Types table' }}
                        </button>

                        <button
                            type="button"
                            class="inline-flex h-11 items-center justify-center rounded-lg bg-blue-700 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Ajouter
                        </button>
                    </div>
                </div>
            </div>

            <div
                v-if="!showTypesTable"
                class="mt-4 grid gap-4 sm:grid-cols-2 xl:grid-cols-3"
            >
                <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Active types</p>
                    <p class="mt-2 text-3xl font-bold text-slate-950">{{ activeTypes }}</p>
                    <p class="mt-2 text-sm text-slate-600">Types ready to receive products.</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Type products</p>
                    <p class="mt-2 text-3xl font-bold text-slate-950">{{ totalProducts }}</p>
                    <p class="mt-2 text-sm text-slate-600">Products linked to added types.</p>
                </div>
                <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:col-span-2 xl:col-span-1">
                    <p class="text-sm font-medium text-slate-500">Table mode</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">
                        Use the button on the right to hide this overview and open the searchable types table.
                    </p>
                </div>
            </div>

            <div v-else class="mt-4 rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-4">
                    <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950">Added types</h2>
                            <p class="mt-1 text-sm text-slate-600">
                                {{ filteredTypes.length }} results from {{ addedTypes.length }} types.
                            </p>
                        </div>

                        <div class="grid gap-2 sm:grid-cols-2 xl:grid-cols-4">
                            <label class="relative block sm:col-span-2 xl:col-span-1">
                                <span class="sr-only">Search added types</span>
                                <svg
                                    class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    aria-hidden="true"
                                >
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                                <input
                                    v-model="search"
                                    type="search"
                                    placeholder="Search type..."
                                    class="h-10 w-full rounded-lg border-slate-200 bg-slate-50 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                                />
                            </label>

                            <select
                                v-model="categoryFilter"
                                class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option v-for="category in categories" :key="category" :value="category">
                                    {{ category }}
                                </option>
                            </select>

                            <select
                                v-model="statusFilter"
                                class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option v-for="status in statuses" :key="status" :value="status">
                                    {{ status }}
                                </option>
                            </select>

                            <select
                                v-model="sortBy"
                                class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500"
                            >
                                <option value="newest">Newest</option>
                                <option value="name">Name A-Z</option>
                                <option value="products">Most products</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="hidden overflow-x-auto lg:block">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Category</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Products</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Created</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr
                                v-for="type in filteredTypes"
                                :key="type.id"
                                class="transition hover:bg-blue-50/50"
                            >
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-slate-950">{{ type.name }}</p>
                                    <p class="text-xs text-slate-500">ID #{{ type.id }}</p>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-700">{{ type.category }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-950">{{ type.products }}</td>
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
                                        :class="statusClass(type.status)"
                                    >
                                        {{ type.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-600">{{ type.createdAt }}</td>
                                <td class="px-4 py-4 text-right">
                                    <button
                                        type="button"
                                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                                    >
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid gap-3 p-3 lg:hidden">
                    <article
                        v-for="type in filteredTypes"
                        :key="type.id"
                        class="rounded-lg border border-slate-200 bg-slate-50 p-3"
                    >
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <h3 class="truncate text-sm font-semibold text-slate-950">{{ type.name }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ type.category }} · {{ type.createdAt }}</p>
                            </div>
                            <span
                                class="shrink-0 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
                                :class="statusClass(type.status)"
                            >
                                {{ type.status }}
                            </span>
                        </div>

                        <div class="mt-3 flex items-center justify-between border-t border-slate-200 pt-3">
                            <p class="text-sm font-semibold text-slate-950">{{ type.products }} products</p>
                            <button
                                type="button"
                                class="rounded-lg bg-blue-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-800"
                            >
                                View
                            </button>
                        </div>
                    </article>
                </div>

                <div
                    v-if="!filteredTypes.length"
                    class="border-t border-slate-200 p-8 text-center"
                >
                    <h3 class="font-semibold text-slate-950">No types found</h3>
                    <p class="mt-2 text-sm text-slate-600">Try another search, filter, or sort option.</p>
                </div>
            </div>
        </div>
    </section>
</template>
