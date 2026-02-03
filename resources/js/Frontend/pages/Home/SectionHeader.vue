<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

defineProps<{
  title: string
  subtitle?: string
  ctaLabel?: string
  ctaHref?: string
  centered?: boolean
}>()
</script>

<template>
  <div
    class="mb-8 lg:mb-12 flex flex-col"
    :class="
      centered
        ? 'items-center text-center'
        : 'sm:flex-row sm:items-end sm:justify-between'
    "
  >
    <div>
      <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 text-foreground mb-2">
        {{ title }}
      </h2>
      <p v-if="subtitle" class="text-gray-600 text-muted-foreground max-w-2xl">
        {{ subtitle }}
      </p>
    </div>

    <Link
      v-if="ctaLabel && ctaHref"
      :href="ctaHref"
      class="mt-4 sm:mt-0 inline-flex items-center gap-2 text-indigo-600 text-primary font-medium hover:gap-3 transition-all"
    >
      {{ ctaLabel }}
      <svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true">
        <path
          fill="currentColor"
          d="M13 5l7 7-7 7-1.41-1.41L16.17 13H4v-2h12.17l-4.58-4.59L13 5z"
        />
      </svg>
    </Link>
  </div>
</template>
