import { createI18n } from 'vue-i18n'
import en from './i18n/en.json'
import fr from './i18n/fr.json'
import ar from './i18n/ar.json'


export const i18n = createI18n({
  legacy: false,
  locale: 'en',
  fallbackLocale: 'en',
  messages: {
    en: en,
    fr: fr,
    ar: ar
  }
})