<script setup>
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'

const form = ref({
    shop_name: '',
    first_name: '',
    last_name: '',
    email: '',
    password: ''
})

function submit() {
    router.post('/register-tenant', form.value)
}
</script>

<template>
    <form @submit.prevent="submit" class="space-y-4">
        <input v-model="form.shop_name" placeholder="Shop name" />
        <input v-model="form.first_name" placeholder="First name" />
        <input v-model="form.last_name" placeholder="Last name" />
        <input v-model="form.email" placeholder="Email" />
        <input v-model="form.password" type="password" placeholder="Password" />

        <button type="submit">Create Shop</button>
    </form>
</template>