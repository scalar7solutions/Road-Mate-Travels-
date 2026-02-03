<script setup lang="ts">
import AppLayout from '../../layouts/AppLayout.vue'

import Banner from './Banner.vue'
import DestinationGroups from './DestinationGroups.vue'
import Map from './Map.vue'

defineOptions({
  layout: AppLayout,
})
</script>

<template>
  <div>
    <Banner />
    <DestinationGroups />
    <Map />
  </div>
</template>
