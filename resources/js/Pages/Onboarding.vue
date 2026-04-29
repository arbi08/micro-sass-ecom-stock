<script setup>
import Step1 from '../Components/Step1.vue'
import Step2 from '../Components/Step2.vue'
import Step3 from '../Components/Step3.vue'

defineProps({ step: Number })
</script>

<template>
  <Step1 v-if="step === 1" />
  <Step2 v-if="step === 2" />
  <Step3 v-if="step === 3" />
</template>