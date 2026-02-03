<script setup lang="ts">
import AppLayout from '../../layouts/AppLayout.vue'

import Banner from './Banner.vue'
import TourPackagesGroup from './TourPackagesGroup.vue'

defineOptions({
  layout: AppLayout,
})
</script>

<template>
  <div>
    <Banner />
    <TourPackagesGroup />
  </div>
</template>
