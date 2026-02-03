<script setup lang="ts">
import { ref } from 'vue'

type FAQ = { id: string | number; question: string; answer: string }

defineProps<{
  items: FAQ[]
}>()

const openIndex = ref<number | null>(0)

const toggle = (i: number) => {
  openIndex.value = openIndex.value === i ? null : i
}
</script>

<template>
  <div class="space-y-3">
    <div
      v-for="(it, i) in items"
      :key="it.id"
      class="rounded-2xl bg-white bg-card shadow-lg shadow-card border border-gray-100 border-border overflow-hidden"
    >
      <button
        type="button"
        class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left"
        @click="toggle(i)"
      >
        <span class="font-semibold text-gray-900 text-foreground">
          {{ it.question }}
        </span>

        <svg
          viewBox="0 0 20 20"
          class="w-5 h-5 text-gray-500 transition-transform"
          :class="openIndex === i ? 'rotate-180' : ''"
          aria-hidden="true"
        >
          <path
            fill="currentColor"
            d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z"
          />
        </svg>
      </button>

      <Transition
        enter-active-class="transition-all duration-300 ease-out"
        enter-from-class="max-h-0 opacity-0"
        enter-to-class="max-h-[500px] opacity-100"
        leave-active-class="transition-all duration-200 ease-in"
        leave-from-class="max-h-[500px] opacity-100"
        leave-to-class="max-h-0 opacity-0"
      >
        <div v-if="openIndex === i" class="px-5 pb-5 text-gray-600 text-muted-foreground leading-relaxed">
          {{ it.answer }}
        </div>
      </Transition>
    </div>
  </div>
</template>
