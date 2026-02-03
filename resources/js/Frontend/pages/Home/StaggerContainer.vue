<script setup lang="ts">
import { onBeforeUnmount, onMounted, provide, ref } from 'vue'

const props = withDefaults(
  defineProps<{
    className?: string
    staggerDelay?: number // seconds per item
  }>(),
  {
    className: '',
    staggerDelay: 0.1,
  }
)

const el = ref<HTMLElement | null>(null)
const inView = ref(false)
let obs: IntersectionObserver | null = null

provide('staggerInView', inView)
provide('staggerDelay', props.staggerDelay)

onMounted(() => {
  obs = new IntersectionObserver(
    ([entry]) => {
      if (entry.isIntersecting) {
        inView.value = true
        obs?.disconnect()
      }
    },
    { root: null, threshold: 0.12, rootMargin: '-50px' }
  )

  if (el.value) obs.observe(el.value)
})

onBeforeUnmount(() => {
  obs?.disconnect()
  obs = null
})
</script>

<template>
  <div ref="el" :class="className">
    <slot />
  </div>
</template>
