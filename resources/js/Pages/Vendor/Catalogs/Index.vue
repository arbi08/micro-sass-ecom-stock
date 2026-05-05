<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import VendorLayout from '@/Layouts/VendorLayout.vue'

defineOptions({
    layout: VendorLayout,
})

interface Catalog {
    id: number
    name: string
    typeId: number
    typeName: string
    items: number
    status: 'Active' | 'Draft' | 'Archived'
    createdAt: string
}

const typeNames: Record<number, string> = {
    1: 'Long Sleeves',
    2: 'Tank Tops',
    3: 'Sportswear Sportswear Sportswear',
    4: 'Sportswear',
    5: 'Sportswear',
    6: 'Sportswear',
}

const catalogPool: Catalog[] = [
    { id: 1, name: 'Winter essentials', typeId: 1, typeName: 'Long Sleeves', items: 18, status: 'Active', createdAt: '2026-04-30' },
    { id: 2, name: 'Cotton basics', typeId: 1, typeName: 'Long Sleeves', items: 11, status: 'Draft', createdAt: '2026-04-29' },
    { id: 3, name: 'Summer tops', typeId: 2, typeName: 'Tank Tops', items: 16, status: 'Active', createdAt: '2026-04-28' },
    { id: 4, name: 'Gym performance', typeId: 3, typeName: 'Sportswear Sportswear Sportswear', items: 22, status: 'Active', createdAt: '2026-04-27' },
    { id: 5, name: 'Running collection', typeId: 4, typeName: 'Sportswear', items: 13, status: 'Archived', createdAt: '2026-04-26' },
    { id: 6, name: 'Yoga fits', typeId: 4, typeName: 'Sportswear', items: 9, status: 'Active', createdAt: '2026-04-25' },
    { id: 7, name: 'New arrivals', typeId: 8, typeName: 'General', items: 7, status: 'Draft', createdAt: '2026-04-24' },
    { id: 8, name: 'Premium picks', typeId: 9, typeName: 'General', items: 15, status: 'Active', createdAt: '2026-04-23' },
]

const addedTypeIds = ref<number[]>([])
const addedCatalogIds = ref<number[]>([])
const search = ref('')
const tableSearch = ref('')
const showTable = ref(false)
const showCreateForm = ref(false)
const statusFilter = ref('All')
const sortBy = ref('recommended')

const statuses = ['All', 'Active', 'Draft', 'Archived']
const addedTypesStorageKey = 'vendor-added-type-ids'
const addedCatalogsStorageKey = 'vendor-added-catalog-ids'

onMounted(() => {
    addedTypeIds.value = JSON.parse(window.localStorage.getItem(addedTypesStorageKey) || '[]')
    addedCatalogIds.value = JSON.parse(window.localStorage.getItem(addedCatalogsStorageKey) || '[]')
})

watch(
    addedCatalogIds,
    (ids) => window.localStorage.setItem(addedCatalogsStorageKey, JSON.stringify(ids)),
    { deep: true },
)

watch(
    addedTypeIds,
    (ids) => window.localStorage.setItem(addedTypesStorageKey, JSON.stringify(ids)),
    { deep: true },
)

const recommendedCatalogs = computed(() => {
    const preferred = catalogPool.filter((catalog) => addedTypeIds.value.includes(catalog.typeId))
    const remaining = catalogPool.filter((catalog) => !addedTypeIds.value.includes(catalog.typeId))

    return [...preferred, ...remaining].map((catalog) => ({
        ...catalog,
        recommended: addedTypeIds.value.includes(catalog.typeId),
    }))
})

const filteredCatalogs = computed(() => {
    const term = search.value.trim().toLowerCase()

    return recommendedCatalogs.value.filter(
        (catalog) =>
            !term ||
            catalog.name.toLowerCase().includes(term) ||
            catalog.typeName.toLowerCase().includes(term),
    )
})

const addedCatalogs = computed(() =>
    recommendedCatalogs.value.filter((catalog) => addedCatalogIds.value.includes(catalog.id)),
)

const filteredTableCatalogs = computed(() => {
    const term = tableSearch.value.trim().toLowerCase()

    return addedCatalogs.value
        .filter((catalog) => {
            const matchesSearch =
                !term ||
                catalog.name.toLowerCase().includes(term) ||
                catalog.typeName.toLowerCase().includes(term)
            const matchesStatus =
                statusFilter.value === 'All' || catalog.status === statusFilter.value

            return matchesSearch && matchesStatus
        })
        .sort((a, b) => {
            if (sortBy.value === 'name') {
                return a.name.localeCompare(b.name)
            }

            if (sortBy.value === 'items') {
                return b.items - a.items
            }

            if (sortBy.value === 'newest') {
                return new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
            }

            return Number(b.recommended) - Number(a.recommended)
        })
})

const addedTypeNames = computed(() =>
    addedTypeIds.value.map((id) => typeNames[id]).filter(Boolean),
)

function addCatalog(catalog: Catalog) {
    if (!addedCatalogIds.value.includes(catalog.id)) {
        addedCatalogIds.value.push(catalog.id)
    }

    if (!addedTypeIds.value.includes(catalog.typeId)) {
        addedTypeIds.value.push(catalog.typeId)
    }
}

function isAdded(catalog: Catalog) {
    return addedCatalogIds.value.includes(catalog.id)
}

function statusClass(status: string) {
    return {
        Active: 'bg-emerald-50 text-emerald-700 ring-emerald-200',
        Draft: 'bg-amber-50 text-amber-700 ring-amber-200',
        Archived: 'bg-slate-100 text-slate-600 ring-slate-200',
    }[status] ?? 'bg-slate-100 text-slate-600 ring-slate-200'
}
</script>

<template>
    <section class="min-h-full overflow-x-hidden bg-slate-50">
        <div class="mx-auto w-full max-w-7xl px-4 py-4 sm:px-6 lg:px-8 lg:py-6">
            <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="min-w-0">
                        <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Catalog management</p>
                        <h1 class="mt-1 text-2xl font-bold text-slate-950 sm:text-3xl">Catalogs</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            Suggested catalogs from your added types appear first, then the remaining catalog options.
                        </p>
                    </div>

                    <div class="grid grid-cols-2 gap-3 sm:flex sm:items-stretch">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-medium text-slate-500">Added types</p>
                            <p class="mt-1 text-2xl font-bold text-slate-950">{{ addedTypeIds.length }}</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-medium text-slate-500">Added catalogs</p>
                            <p class="mt-1 text-2xl font-bold text-slate-950">{{ addedCatalogs.length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <form v-if="showCreateForm" class="mt-4 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:p-5" @submit.prevent>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950">New catalog</h2>
                        <p class="mt-1 text-sm text-slate-600">Create a catalog and attach it to one of your types.</p>
                    </div>
                    <button type="button" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" @click="showCreateForm = false">
                        Back to list
                    </button>
                </div>

                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">Catalog name</span>
                        <input type="text" placeholder="Example: Winter essentials" class="mt-2 h-11 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" />
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">Parent type</span>
                        <select class="mt-2 h-11 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                            <option v-for="typeName in addedTypeNames" :key="typeName">{{ typeName }}</option>
                            <option v-if="!addedTypeNames.length">No added type yet</option>
                        </select>
                    </label>
                    <label class="block lg:col-span-2">
                        <span class="text-sm font-semibold text-slate-700">Description</span>
                        <textarea rows="4" placeholder="Short internal note..." class="mt-2 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500"></textarea>
                    </label>
                </div>

                <div class="mt-5 flex justify-end">
                    <button type="submit" class="inline-flex h-11 items-center justify-center rounded-lg bg-blue-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                        Save catalog
                    </button>
                </div>
            </form>

            <div v-if="!showCreateForm" class="mt-4 flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-3 shadow-sm lg:flex-row lg:items-center lg:justify-between">
                <label class="relative block w-full sm:max-w-sm">
                    <span class="sr-only">Search catalogs</span>
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-slate-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                    <input
                        v-model="search"
                        type="search"
                        placeholder="Search catalogs..."
                        :disabled="showTable"
                        class="h-11 w-full rounded-lg border-slate-200 bg-slate-50 pl-9 pr-3 text-sm text-slate-900 placeholder:text-slate-400 focus:border-blue-500 focus:bg-white focus:ring-blue-500"
                        :class="showTable ? 'cursor-not-allowed opacity-60' : ''"
                    />
                </label>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <button
                        type="button"
                        class="inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        @click="showTable = !showTable"
                    >
                        {{ showTable ? 'Cards view' : 'Table view' }}
                    </button>
                    <button
                        type="button"
                        class="inline-flex h-11 items-center justify-center rounded-lg bg-blue-700 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        @click="showCreateForm = true"
                    >
                        New catalog
                    </button>
                </div>
            </div>

            <div v-if="!showCreateForm && !showTable" class="mt-4">
                <div v-if="addedTypeNames.length" class="mb-3 rounded-lg border border-blue-100 bg-blue-50 p-3 text-sm text-blue-800">
                    Priority suggestions for: {{ addedTypeNames.join(', ') }}
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
                    <article
                        v-for="catalog in filteredCatalogs"
                        :key="catalog.id"
                        class="group flex min-h-32 flex-col justify-between rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md"
                        :class="catalog.recommended ? 'ring-1 ring-blue-100' : ''"
                    >
                        <div class="min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h2 class="break-words text-sm font-semibold leading-5 text-slate-950">{{ catalog.name }}</h2>
                                <span v-if="catalog.recommended" class="shrink-0 rounded-full bg-blue-50 px-2 py-1 text-[11px] font-semibold text-blue-700">Type match</span>
                            </div>
                            <p class="mt-2 text-xs font-medium text-slate-500">{{ catalog.typeName }}</p>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
                            <p class="text-sm font-semibold text-slate-950">{{ catalog.items }} items</p>
                            <button
                                v-if="!isAdded(catalog)"
                                type="button"
                                class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700"
                                aria-label="Add catalog"
                                @click="addCatalog(catalog)"
                            >
                                +
                            </button>
                        </div>
                    </article>
                </div>
            </div>

            <div v-else-if="!showCreateForm" class="mt-4 rounded-lg border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-4">
                    <div class="flex flex-col gap-3 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <h2 class="text-lg font-bold text-slate-950">Added catalogs</h2>
                            <p class="mt-1 text-sm text-slate-600">{{ filteredTableCatalogs.length }} results from {{ addedCatalogs.length }} added catalogs.</p>
                        </div>

                        <div class="grid min-w-0 gap-2 sm:grid-cols-3">
                            <input v-model="tableSearch" type="search" placeholder="Search added catalogs..." class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500 sm:col-span-3 xl:col-span-1" />
                            <select v-model="statusFilter" class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                            </select>
                            <select v-model="sortBy" class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="recommended">Recommended first</option>
                                <option value="newest">Newest</option>
                                <option value="name">Name A-Z</option>
                                <option value="items">Most items</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="hidden overflow-x-auto lg:block">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Catalog</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Items</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr v-for="catalog in filteredTableCatalogs" :key="catalog.id" class="transition hover:bg-blue-50/50">
                                <td class="px-4 py-4 font-semibold text-slate-950">{{ catalog.name }}</td>
                                <td class="px-4 py-4 text-sm text-slate-700">{{ catalog.typeName }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-950">{{ catalog.items }}</td>
                                <td class="px-4 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset" :class="statusClass(catalog.status)">{{ catalog.status }}</span></td>
                                <td class="px-4 py-4 text-sm text-slate-600">{{ catalog.createdAt }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid min-w-0 gap-3 p-3 lg:hidden">
                    <article v-for="catalog in filteredTableCatalogs" :key="catalog.id" class="w-full min-w-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <div class="min-w-0">
                            <h3 class="truncate text-sm font-semibold text-slate-950">{{ catalog.name }}</h3>
                            <p class="mt-1 text-xs text-slate-500">{{ catalog.typeName }} · {{ catalog.createdAt }}</p>
                            <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset" :class="statusClass(catalog.status)">{{ catalog.status }}</span>
                        </div>
                        <div class="mt-3 border-t border-slate-200 pt-3 text-sm font-semibold text-slate-950">{{ catalog.items }} items</div>
                    </article>
                </div>

                <div v-if="!filteredTableCatalogs.length" class="border-t border-slate-200 p-8 text-center">
                    <h3 class="font-semibold text-slate-950">No added catalogs yet</h3>
                    <p class="mt-2 text-sm text-slate-600">Go back to cards view and add catalogs first.</p>
                </div>
            </div>
        </div>
    </section>
</template>
