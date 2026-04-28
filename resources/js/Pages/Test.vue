<script setup lang="ts">
import type { User } from '../Repositories/User';

import { useI18n } from 'vue-i18n'


const { locale, t } = useI18n()

const changeLang = (lang: string) => {
    locale.value = lang
    localStorage.setItem('lang', lang) // باش يتسجل
}
const name = 'Test1111';
const user: User = {
    id: 1,
    name: 'John Doe',
    email: 'john.doe@example.com',
};
defineProps({
    name: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div>
        <h1>Test {{ name }} {{ user.name }} {{ user.email }} {{ user.id }}</h1>
        <div class="flex gap-2">
            <button @click="changeLang('en')">EN</button>
            <button @click="changeLang('fr')">FR</button>
            <button @click="changeLang('ar')">AR</button>
        </div>
        <h1>{{ t('da.hello') }}</h1>
        <p>{{ t('da.welcome') }}</p>

    </div>
</template>
