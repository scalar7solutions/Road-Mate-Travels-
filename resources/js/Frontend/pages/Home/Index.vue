<script setup lang="ts">
import AppLayout from '../../layouts/AppLayout.vue'
import Product from './Product.vue'
import HomeBanner from './HomeBanner.vue'

import ExploreSriLanka from './ExploreSriLanka.vue'
import PupularPackages from './pupularpackages.vue'
import VehicleRental from './vehiclerental.vue'
import ChooseUs from './chooseus.vue'
import Testiminols from './testiminols.vue'
import Faqs from './Faqs.vue'


defineOptions({
  layout: AppLayout,
})

defineProps<{
  products: any
  activeCategory?: string | null
  banners: Array<{
    id: number | string
    name?: string
    description?: string | null
    video_url: string | null
  }>
}>()
</script>

<template>
  <!-- Hero + Quick Search (inside HomeBanner) -->
  <HomeBanner :banners="banners" />

  <!-- Sections (same order as TSX) -->
  <ExploreSriLanka />
  <PupularPackages />
  <VehicleRental />
  <ChooseUs />
  <Testiminols />
  <Faqs />
  <CTABanner />

 
</template>
