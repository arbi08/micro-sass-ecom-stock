<script setup lang="ts">
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

defineProps<{
    type: Type
}>()
</script>

<template>
    <article class="overflow-hidden rounded-lg border border-slate-200 bg-white shadow-sm">
        <div class="flex items-center gap-3 border-b border-slate-100 p-4">
            <div class="h-14 w-14 shrink-0 overflow-hidden rounded-lg bg-slate-100">
                <img
                    v-if="type.image"
                    :src="type.image"
                    :alt="type.name"
                    class="h-full w-full object-cover"
                />
            </div>

            <div class="min-w-0">
                <h3 class="truncate text-sm font-semibold text-slate-950">
                    {{ type.name }}
                </h3>
                <p class="mt-1 text-xs font-medium text-slate-500">
                    {{ type.products?.length ?? 0 }} products
                </p>
            </div>
        </div>

        <div class="overflow-x-auto p-3">
            <div class="flex min-w-max gap-3 lg:min-w-0 lg:grid lg:grid-cols-2">
                <div
                    v-for="product in type.products"
                    :key="product.id"
                    class="w-40 rounded-lg border border-slate-100 bg-slate-50 p-2 lg:w-auto"
                >
                    <div class="aspect-[4/3] overflow-hidden rounded-md bg-slate-100">
                        <img
                            v-if="product.image"
                            :src="product.image"
                            :alt="product.name"
                            class="h-full w-full object-cover"
                        />
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <p class="min-w-0 flex-1 truncate text-xs font-medium text-slate-700">
                            {{ product.name }}
                        </p>
                        <button
                            type="button"
                            class="shrink-0 rounded-md bg-blue-700 px-2.5 py-1 text-[11px] font-semibold text-white transition hover:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Ajouter
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </article>
</template>
