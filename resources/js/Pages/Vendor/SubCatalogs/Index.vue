<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import VendorLayout from '@/Layouts/VendorLayout.vue'

defineOptions({
    layout: VendorLayout,
})

interface SubCatalog {
    id: number
    name: string
    catalogId: number
    catalogName: string
    products: number
    status: 'Active' | 'Draft' | 'Archived'
    createdAt: string
}

const catalogNames: Record<number, string> = {
    1: 'Winter essentials',
    2: 'Cotton basics',
    3: 'Summer tops',
    4: 'Gym performance',
    5: 'Running collection',
    6: 'Yoga fits',
    7: 'New arrivals',
    8: 'Premium picks',
}

const catalogTypeIds: Record<number, number> = {
    1: 1,
    2: 1,
    3: 2,
    4: 3,
    5: 4,
    6: 4,
    7: 8,
    8: 9,
}

const subCatalogPool: SubCatalog[] = [
    { id: 1, name: 'Thermal shirts', catalogId: 1, catalogName: 'Winter essentials', products: 8, status: 'Active', createdAt: '2026-04-30' },
    { id: 2, name: 'Heavy cotton', catalogId: 2, catalogName: 'Cotton basics', products: 6, status: 'Draft', createdAt: '2026-04-29' },
    { id: 3, name: 'Ribbed tanks', catalogId: 3, catalogName: 'Summer tops', products: 10, status: 'Active', createdAt: '2026-04-28' },
    { id: 4, name: 'Compression wear', catalogId: 4, catalogName: 'Gym performance', products: 12, status: 'Active', createdAt: '2026-04-27' },
    { id: 5, name: 'Running tops', catalogId: 5, catalogName: 'Running collection', products: 9, status: 'Archived', createdAt: '2026-04-26' },
    { id: 6, name: 'Stretch sets', catalogId: 6, catalogName: 'Yoga fits', products: 7, status: 'Active', createdAt: '2026-04-25' },
    { id: 7, name: 'Limited drops', catalogId: 7, catalogName: 'New arrivals', products: 4, status: 'Draft', createdAt: '2026-04-24' },
    { id: 8, name: 'Best sellers', catalogId: 8, catalogName: 'Premium picks', products: 13, status: 'Active', createdAt: '2026-04-23' },
]

const addedCatalogIds = ref<number[]>([])
const addedTypeIds = ref<number[]>([])
const addedSubCatalogIds = ref<number[]>([])
const search = ref('')
const tableSearch = ref('')
const showTable = ref(false)
const showCreateForm = ref(false)
const statusFilter = ref('All')
const sortBy = ref('recommended')

const statuses = ['All', 'Active', 'Draft', 'Archived']
const addedTypesStorageKey = 'vendor-added-type-ids'
const addedCatalogsStorageKey = 'vendor-added-catalog-ids'
const addedSubCatalogsStorageKey = 'vendor-added-sub-catalog-ids'

onMounted(() => {
    addedTypeIds.value = JSON.parse(window.localStorage.getItem(addedTypesStorageKey) || '[]')
    addedCatalogIds.value = JSON.parse(window.localStorage.getItem(addedCatalogsStorageKey) || '[]')
    addedSubCatalogIds.value = JSON.parse(window.localStorage.getItem(addedSubCatalogsStorageKey) || '[]')
})

watch(
    addedTypeIds,
    (ids) => window.localStorage.setItem(addedTypesStorageKey, JSON.stringify(ids)),
    { deep: true },
)

watch(
    addedCatalogIds,
    (ids) => window.localStorage.setItem(addedCatalogsStorageKey, JSON.stringify(ids)),
    { deep: true },
)

watch(
    addedSubCatalogIds,
    (ids) => window.localStorage.setItem(addedSubCatalogsStorageKey, JSON.stringify(ids)),
    { deep: true },
)

const recommendedSubCatalogs = computed(() => {
    const preferred = subCatalogPool.filter((subCatalog) => addedCatalogIds.value.includes(subCatalog.catalogId))
    const remaining = subCatalogPool.filter((subCatalog) => !addedCatalogIds.value.includes(subCatalog.catalogId))

    return [...preferred, ...remaining].map((subCatalog) => ({
        ...subCatalog,
        recommended: addedCatalogIds.value.includes(subCatalog.catalogId),
    }))
})

const filteredSubCatalogs = computed(() => {
    const term = search.value.trim().toLowerCase()

    return recommendedSubCatalogs.value.filter(
        (subCatalog) =>
            !term ||
            subCatalog.name.toLowerCase().includes(term) ||
            subCatalog.catalogName.toLowerCase().includes(term),
    )
})

const addedSubCatalogs = computed(() =>
    recommendedSubCatalogs.value.filter((subCatalog) => addedSubCatalogIds.value.includes(subCatalog.id)),
)

const filteredTableSubCatalogs = computed(() => {
    const term = tableSearch.value.trim().toLowerCase()

    return addedSubCatalogs.value
        .filter((subCatalog) => {
            const matchesSearch =
                !term ||
                subCatalog.name.toLowerCase().includes(term) ||
                subCatalog.catalogName.toLowerCase().includes(term)
            const matchesStatus =
                statusFilter.value === 'All' || subCatalog.status === statusFilter.value

            return matchesSearch && matchesStatus
        })
        .sort((a, b) => {
            if (sortBy.value === 'name') {
                return a.name.localeCompare(b.name)
            }

            if (sortBy.value === 'products') {
                return b.products - a.products
            }

            if (sortBy.value === 'newest') {
                return new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
            }

            return Number(b.recommended) - Number(a.recommended)
        })
})

const addedCatalogNames = computed(() =>
    addedCatalogIds.value.map((id) => catalogNames[id]).filter(Boolean),
)

function addSubCatalog(subCatalog: SubCatalog) {
    if (!addedSubCatalogIds.value.includes(subCatalog.id)) {
        addedSubCatalogIds.value.push(subCatalog.id)
    }

    if (!addedCatalogIds.value.includes(subCatalog.catalogId)) {
        addedCatalogIds.value.push(subCatalog.catalogId)
    }

    const typeId = catalogTypeIds[subCatalog.catalogId]

    if (typeId && !addedTypeIds.value.includes(typeId)) {
        addedTypeIds.value.push(typeId)
    }
}

function isAdded(subCatalog: SubCatalog) {
    return addedSubCatalogIds.value.includes(subCatalog.id)
}

function needsCatalogSync(subCatalog: SubCatalog) {
    return !addedCatalogIds.value.includes(subCatalog.catalogId)
}

function needsTypeSync(subCatalog: SubCatalog) {
    const typeId = catalogTypeIds[subCatalog.catalogId]

    return Boolean(typeId && !addedTypeIds.value.includes(typeId))
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
                        <h1 class="mt-1 text-2xl font-bold text-slate-950 sm:text-3xl">Sub Catalogs</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600">
                            Sub catalog suggestions linked to your added catalogs appear first.
                        </p>
                    </div>

                    <div class="grid grid-cols-3 gap-3 sm:flex sm:items-stretch">
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-medium text-slate-500">Added types</p>
                            <p class="mt-1 text-2xl font-bold text-slate-950">{{ addedTypeIds.length }}</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-medium text-slate-500">Added catalogs</p>
                            <p class="mt-1 text-2xl font-bold text-slate-950">{{ addedCatalogIds.length }}</p>
                        </div>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-4 py-3">
                            <p class="text-xs font-medium text-slate-500">Sub catalogs</p>
                            <p class="mt-1 text-2xl font-bold text-slate-950">{{ addedSubCatalogs.length }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <form v-if="showCreateForm" class="mt-4 rounded-lg border border-slate-200 bg-white p-4 shadow-sm sm:p-5" @submit.prevent>
                <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-950">New sub catalog</h2>
                        <p class="mt-1 text-sm text-slate-600">Create a sub catalog and attach it to one of your catalogs.</p>
                    </div>
                    <button type="button" class="rounded-lg border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50" @click="showCreateForm = false">
                        Back to list
                    </button>
                </div>

                <div class="mt-5 grid gap-4 lg:grid-cols-2">
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">Sub catalog name</span>
                        <input type="text" placeholder="Example: Thermal shirts" class="mt-2 h-11 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500" />
                    </label>
                    <label class="block">
                        <span class="text-sm font-semibold text-slate-700">Parent catalog</span>
                        <select class="mt-2 h-11 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500">
                            <option v-for="catalogName in addedCatalogNames" :key="catalogName">{{ catalogName }}</option>
                            <option v-if="!addedCatalogNames.length">No added catalog yet</option>
                        </select>
                    </label>
                    <label class="block lg:col-span-2">
                        <span class="text-sm font-semibold text-slate-700">Description</span>
                        <textarea rows="4" placeholder="Short internal note..." class="mt-2 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500"></textarea>
                    </label>
                </div>

                <div class="mt-5 flex justify-end">
                    <button type="submit" class="inline-flex h-11 items-center justify-center rounded-lg bg-blue-700 px-5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800">
                        Save sub catalog
                    </button>
                </div>
            </form>

            <div v-if="!showCreateForm" class="mt-4 flex flex-col gap-3 rounded-lg border border-slate-200 bg-white p-3 shadow-sm lg:flex-row lg:items-center lg:justify-between">
                <input
                    v-model="search"
                    type="search"
                    placeholder="Search sub catalogs..."
                    :disabled="showTable"
                    class="h-11 w-full rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500 sm:max-w-sm"
                    :class="showTable ? 'cursor-not-allowed opacity-60' : ''"
                />

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <button type="button" class="inline-flex h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm transition hover:bg-slate-50" @click="showTable = !showTable">
                        {{ showTable ? 'Cards view' : 'Table view' }}
                    </button>
                    <button type="button" class="inline-flex h-11 items-center justify-center rounded-lg bg-blue-700 px-4 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-800" @click="showCreateForm = true">
                        New sub catalog
                    </button>
                </div>
            </div>

            <div v-if="!showCreateForm && !showTable" class="mt-4">
                <div v-if="addedCatalogNames.length" class="mb-3 rounded-lg border border-blue-100 bg-blue-50 p-3 text-sm text-blue-800">
                    Priority suggestions for: {{ addedCatalogNames.join(', ') }}
                </div>

                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4">
                    <article v-for="subCatalog in filteredSubCatalogs" :key="subCatalog.id" class="group flex min-h-32 flex-col justify-between rounded-lg border border-slate-200 bg-white p-4 shadow-sm transition hover:-translate-y-0.5 hover:border-blue-200 hover:shadow-md" :class="subCatalog.recommended ? 'ring-1 ring-blue-100' : ''">
                        <div class="min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h2 class="break-words text-sm font-semibold leading-5 text-slate-950">{{ subCatalog.name }}</h2>
                                <span v-if="subCatalog.recommended" class="shrink-0 rounded-full bg-blue-50 px-2 py-1 text-[11px] font-semibold text-blue-700">Catalog match</span>
                            </div>
                            <p class="mt-2 text-xs font-medium text-slate-500">{{ subCatalog.catalogName }}</p>
                            <div class="mt-3 flex flex-wrap gap-2">
                                <span v-if="needsCatalogSync(subCatalog)" class="rounded-full bg-indigo-50 px-2 py-1 text-[11px] font-semibold text-indigo-700">Adds catalog</span>
                                <span v-if="needsTypeSync(subCatalog)" class="rounded-full bg-emerald-50 px-2 py-1 text-[11px] font-semibold text-emerald-700">Adds type</span>
                            </div>
                        </div>

                        <div class="mt-4 flex items-center justify-between gap-3 border-t border-slate-100 pt-3">
                            <p class="text-sm font-semibold text-slate-950">{{ subCatalog.products }} products</p>
                            <button v-if="!isAdded(subCatalog)" type="button" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border border-slate-200 text-slate-500 transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-700" aria-label="Add sub catalog" @click="addSubCatalog(subCatalog)">
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
                            <h2 class="text-lg font-bold text-slate-950">Added sub catalogs</h2>
                            <p class="mt-1 text-sm text-slate-600">{{ filteredTableSubCatalogs.length }} results from {{ addedSubCatalogs.length }} added sub catalogs.</p>
                        </div>

                        <div class="grid min-w-0 gap-2 sm:grid-cols-3">
                            <input v-model="tableSearch" type="search" placeholder="Search added sub catalogs..." class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500 sm:col-span-3 xl:col-span-1" />
                            <select v-model="statusFilter" class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                            </select>
                            <select v-model="sortBy" class="h-10 rounded-lg border-slate-200 bg-slate-50 text-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="recommended">Recommended first</option>
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
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Sub catalog</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Catalog</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Products</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Status</th>
                                <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-slate-500">Created</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <tr v-for="subCatalog in filteredTableSubCatalogs" :key="subCatalog.id" class="transition hover:bg-blue-50/50">
                                <td class="px-4 py-4 font-semibold text-slate-950">{{ subCatalog.name }}</td>
                                <td class="px-4 py-4 text-sm text-slate-700">{{ subCatalog.catalogName }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-950">{{ subCatalog.products }}</td>
                                <td class="px-4 py-4"><span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset" :class="statusClass(subCatalog.status)">{{ subCatalog.status }}</span></td>
                                <td class="px-4 py-4 text-sm text-slate-600">{{ subCatalog.createdAt }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid min-w-0 gap-3 p-3 lg:hidden">
                    <article v-for="subCatalog in filteredTableSubCatalogs" :key="subCatalog.id" class="w-full min-w-0 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 p-3">
                        <div class="min-w-0">
                            <h3 class="truncate text-sm font-semibold text-slate-950">{{ subCatalog.name }}</h3>
                            <p class="mt-1 text-xs text-slate-500">{{ subCatalog.catalogName }} - {{ subCatalog.createdAt }}</p>
                            <span class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset" :class="statusClass(subCatalog.status)">{{ subCatalog.status }}</span>
                        </div>
                        <div class="mt-3 border-t border-slate-200 pt-3 text-sm font-semibold text-slate-950">{{ subCatalog.products }} products</div>
                    </article>
                </div>

                <div v-if="!filteredTableSubCatalogs.length" class="border-t border-slate-200 p-8 text-center">
                    <h3 class="font-semibold text-slate-950">No added sub catalogs yet</h3>
                    <p class="mt-2 text-sm text-slate-600">Go back to cards view and add sub catalogs first.</p>
                </div>
            </div>
        </div>
    </section>
</template>
