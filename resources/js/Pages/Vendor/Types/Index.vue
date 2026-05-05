<script setup lang="ts">
import { computed, ref } from 'vue'
import { router } from '@inertiajs/vue3'
import VendorLayout from '@/Layouts/VendorLayout.vue'
import TypeCard from '@/Components/Vendor/Types/TypeCard.vue'

defineOptions({
    layout: VendorLayout,
})

interface Product {
    id: number
    name: string
    image?: string
}

interface Type {
    id?: number
    name: string
    image?: string
    products?: Product[]
}

interface TenantCategory {
    id: number
    category_id: number
    name: string
    parent_id: number | null
    icon: string | null
    sort_order: number
    description: string | null
    meta_title: string | null
    meta_description: string | null
    is_active: boolean
    created_at: string
    updated_at: string
    deleted_at: string | null
}

const form = ref({
    name: '',
    image: '',
    description: ''
})

const fallbackImage =
    'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?q=80&w=800&auto=format&fit=crop'

const props = defineProps<{
    types: Type[],
    typesByTenant: TenantCategory[],
    featuredTypes: Type[]
}>()

console.log(props.featuredTypes)

const search = ref('')
const showTypesTable = ref(false)
const showCreateForm = ref(false)
const tableSearch = ref('')
const statusFilter = ref('All')
const sortBy = ref('newest')

/* ✅ source of truth من backend */
const addedTypeIds = computed(() =>
    props.typesByTenant.map(t => Number(t.category_id))
)

const statuses = ['All', 'Active', 'Draft', 'Archived']

/* 🔥 clean */
const normalizedTypes = computed(() =>
    props.types.map((type, index) => ({
        ...type,
        id: type.id ?? index + 1,
        image: cleanImage(type.image),
    })),
)

const filteredTypes = computed(() => {
    const term = search.value.trim().toLowerCase()

    if (!term) return normalizedTypes.value

    return normalizedTypes.value.filter((type) =>
        type.name.toLowerCase().includes(term),
    )
})

const tableTypes = computed(() =>
    normalizedTypes.value.map((type, index) => ({
        ...type,
        status: index % 5 === 0 ? 'Draft' : index % 7 === 0 ? 'Archived' : 'Active',
        productCount: props.featuredTypes[index % props.featuredTypes.length]?.products?.length ?? 0,
        createdAt: `2026-04-${String(30 - (index % 20)).padStart(2, '0')}`,
    })),
)

const addedTableTypes = computed(() =>
    tableTypes.value.filter((type) =>
        addedTypeIds.value.includes(Number(type.id))
    ),
)

const filteredTableTypes = computed(() => {
    const term = tableSearch.value.trim().toLowerCase()

    return addedTableTypes.value
        .filter((type) => {
            const matchesSearch = !term || type.name.toLowerCase().includes(term)
            const matchesStatus =
                statusFilter.value === 'All' || type.status === statusFilter.value

            return matchesSearch && matchesStatus
        })
        .sort((a, b) => {
            if (sortBy.value === 'name') return a.name.localeCompare(b.name)
            if (sortBy.value === 'products') return b.productCount - a.productCount

            return new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
        })
})

const totalProducts = computed(() =>
    props.featuredTypes.reduce(
        (total, type) => total + (type.products?.length ?? 0),
        0
    ),
)

/* ✅ actions */
function addType(type: Type) {
    router.post(route('vendor.types.attach', type.id), {}, {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            router.reload({ only: ['types', 'typesByTenant'] })
        }
    })
}

function deleteType(typeId: number) {
    router.delete(route('vendor.types.destroy', typeId), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            router.reload({ only: ['types', 'typesByTenant'] })
        }
    })
}

function isAdded(type: Type) {
    return props.typesByTenant.some(
        t => Number(t.category_id) === Number(type.id)
    )
}

function createType() {
    router.post('/vendor/types', form.value, {
        onSuccess: () => {
            showCreateForm.value = false

            // 🔥 reset form
            form.value = {
                name: '',
                image: '',
                description: ''
            }
        }
    })
}

/* helpers */

function statusClass(status: string) {
    return {
        Active: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        Draft: 'bg-amber-50 text-amber-700 ring-amber-200',
        Archived: 'bg-slate-100 text-slate-600 ring-slate-200',
    }[status] ?? 'bg-slate-100 text-slate-600 ring-slate-200'
}

function cleanImage(image?: string) {
    if (!image) return fallbackImage
    return image.split('](')[0]
}

const sortedFeaturedTypes = computed(() => {
    return [...props.featuredTypes].sort((a, b) => {
        const countA = a.products?.length ?? 0
        const countB = b.products?.length ?? 0

        const isFullA = countA >= 20
        const isFullB = countB >= 20

        if (isFullA && !isFullB) return -1
        if (!isFullA && isFullB) return 1

        return countB - countA
    })
})
</script>

<template>
    <section class="min-h-full overflow-x-hidden bg-slate-50">
        <div class="mx-auto w-full max-w-7xl px-4 py-4 sm:px-6 lg:px-8 lg:py-6">
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Catalog management</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-950 sm:text-3xl">Types</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            Organize your product families and keep the storefront easier to scan.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:flex sm:items-stretch">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-medium text-slate-500">Active types</p>
                            <p class="mt-1 text-2xl font-bold text-slate-950">{{ normalizedTypes.length }}</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-medium text-slate-500">Featured items</p>
                            <p class="mt-1 text-2xl font-bold text-slate-950">{{ totalProducts }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- add new type form -->
            <form v-if="showCreateForm" class="mt-4 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:p-5"
                @submit.prevent="createType">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950">New type</h2>
                        <p class="mt-1 text-sm text-slate-600">Create a new product type for your catalog.</p>
                    </div>
                    <button type="button"
                        class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        @click="showCreateForm = false">
                        Back to list
                    </button>
                </div>

                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">Type name</span>
                        <input type="text" placeholder="Example: Long Sleeves"
                            class="mt-2 h-11 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" />
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">Image URL</span>
                        <input type="url" placeholder="https://..."
                            class="mt-2 h-11 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" />
                    </label>
                    <label class="block lg:col-span-2">
                        <span class="text-sm font-semibold text-slate-700">Description</span>
                        <textarea rows="4" placeholder="Short internal note..."
                            class="mt-2 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500"></textarea>
                    </label>
                </div>

                <div class="mt-5 flex justify-end">
                    <button type="submit"
                        class="inline-flex h-11 items-center justify-center rounded-lg bg-blue-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                        Save type
                    </button>
                </div>
            </form>

            <div v-if="!showCreateForm"
                class="mt-4 flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-3 shadow-sm lg:flex-row lg:items-center lg:justify-between">
                <label class="relative block w-full sm:max-w-sm">
                    <span class="sr-only">Search types</span>
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                    <input v-model="search" type="search" placeholder="Search types..." :disabled="showTypesTable"
                        class="h-11 w-full rounded-lg border-slate-200 bg-slate-50 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                        :class="showTypesTable ? 'cursor-not-allowed opacity-60' : ''" />
                </label>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <button type="button"
                        class="inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        @click="showTypesTable = !showTypesTable">
                        {{ showTypesTable ? 'Cards view' : 'Table view' }}
                    </button>

                    <button type="button"
                        class="inline-flex h-11 items-center justify-center rounded-lg bg-blue-700 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        @click="showCreateForm = true">
                        New type
                    </button>
                </div>
            </div>
            <!-- Cards view -->
            <div v-if="!showCreateForm && !showTypesTable && filteredTypes.length"
                class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
                <article v-for="type in filteredTypes" :key="type.id"
                    class="group flex min-h-28 items-center gap-4 rounded-lg border border-slate-200 bg-white p-3 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <div class="h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                        <img :src="type.image" :alt="type.name"
                            class="h-full w-full object-cover transition duration-300 group-hover:scale-105" />
                    </div>

                    <div class="min-w-0 flex-1">
                        <p class="max-h-10 overflow-hidden break-words text-sm font-semibold leading-5 text-slate-950">
                            {{ type.name }}
                        </p>
                        <p class="mt-1 text-xs font-medium text-slate-500">Type collection</p>
                    </div>

                    <button v-if="!isAdded(type)" type="button"
                        class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition group-hover:border-blue-200 group-hover:bg-blue-50 group-hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        aria-label="Add type" @click="addType(type)">
                        <span aria-hidden="true">+</span>
                    </button>
                </article>
            </div>

            <div v-else-if="!showCreateForm && !showTypesTable"
                class="mt-4 rounded-lg border border-dashed border-slate-300 bg-white p-8 text-center">
                <h2 class="text-base font-semibold text-slate-950">No types found</h2>
                <p class="mt-2 text-sm text-slate-600">Try another search term or create a new type.</p>
            </div>

            <div v-if="!showCreateForm && !showTypesTable" class="mt-6 grid gap-4 xl:grid-cols-3">
                <TypeCard v-for="type in featuredTypes" :key="type.id" :type="type" />
            </div>
            <!-- Table view -->
            <div v-else-if="!showCreateForm" class="mt-4 rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-4">
                    <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950">Added types</h2>
                            <p class="mt-1 text-sm text-slate-600">
                                {{ filteredTableTypes.length }} results from {{ addedTableTypes.length }} added types.
                            </p>
                        </div>

                        <div class="grid min-w-0 gap-2 sm:grid-cols-3">
                            <label class="relative block sm:col-span-3 xl:col-span-1">
                                <span class="sr-only">Search added types</span>
                                <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.3-4.3" />
                                </svg>
                                <input v-model="tableSearch" type="search" placeholder="Search added types..."
                                    class="h-10 w-full rounded-lg border-slate-200 bg-slate-50 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-blue-500" />
                            </label>

                            <select v-model="statusFilter"
                                class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500">
                                <option v-for="status in statuses" :key="status" :value="status">
                                    {{ status }}
                                </option>
                            </select>

                            <select v-model="sortBy"
                                class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm text-slate-700 focus:border-blue-500 focus:ring-blue-500">
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
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Type</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Products</th>
                                <!-- <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Category ID</th> -->
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Status</th>
                                <th
                                    class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Created</th>
                                <th
                                    class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-slate-500">
                                    Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr v-for="type in filteredTableTypes" :key="type.id"
                                class="transition hover:bg-blue-50/50">
                                <td class="px-4 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-11 w-11 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                            <img :src="type.image" :alt="type.name"
                                                class="h-full w-full object-cover" />
                                        </div>
                                        <div class="min-w-0">
                                            <p class="truncate font-semibold text-slate-950">{{ type.name }}</p>
                                            <p class="text-xs text-slate-500">ID #{{ type.id }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-950">{{ type.productCount }}</td>
                                <!-- <td class="px-4 py-4 text-sm font-semibold text-slate-950">{{ type.id }}</td> -->
                                <td class="px-4 py-4">
                                    <span
                                        class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
                                        :class="statusClass(type.status)">
                                        {{ type.status }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-slate-600">{{ type.createdAt }}</td>
                                <td class="px-4 py-4 text-right">
                                    <button type="button" @click="deleteType(type.id)"
                                        class="shrink-0 mr-2 rounded-lg bg-red-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-red-800">
                                        Delete
                                    </button>
                                    <button type="button"
                                        class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700">
                                        View
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid min-w-0 gap-3 p-3 lg:hidden">
                    <article v-for="type in filteredTableTypes" :key="type.id"
                        class="w-full min-w-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <div class="flex min-w-0 items-start gap-3">
                            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                                <img :src="type.image" :alt="type.name" class="h-full w-full object-cover" />
                            </div>
                            <div class="min-w-0 flex-1">
                                <h3 class="truncate text-sm font-semibold text-slate-950">{{ type.name }}</h3>
                                <p class="mt-1 text-xs text-slate-500">{{ type.createdAt }}</p>
                                <span
                                    class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset"
                                    :class="statusClass(type.status)">
                                    {{ type.status }}
                                </span>
                            </div>
                        </div>

                        <div
                            class="mt-3 flex min-w-0 items-center justify-between gap-3 border-t border-slate-200 pt-3">
                            <p class="text-sm font-semibold text-slate-950">{{ type.productCount }} products</p>

                            <button type="button"
                                class="shrink-0 rounded-lg bg-blue-700 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-blue-800">
                                View
                            </button>
                        </div>
                    </article>
                </div>

                <div v-if="!filteredTableTypes.length" class="border-t border-slate-200 p-8 text-center">
                    <h3 class="font-semibold text-slate-950">No types found</h3>
                    <p class="mt-2 text-sm text-slate-600">Try another search, filter, or sort option.</p>
                </div>
            </div>
        </div>
    </section>
</template>
