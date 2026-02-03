<script setup lang="ts">
import { computed, inject } from 'vue'

const props = withDefaults(
  defineProps<{
    index: number
    className?: string
  }>(),
  {
    className: '',
  }
)

const inView = inject('staggerInView') as { value: boolean } | undefined
const staggerDelay = inject('staggerDelay') as number | undefined

const delay = computed(() => {
  const step = typeof staggerDelay === 'number' ? staggerDelay : 0.1
  return props.index * step
})

const visible = computed(() => !!inView?.value)
</script>

<template>
  <div
    :class="className"
    :style="{
      opacity: visible ? 1 : 0,
      transform: visible ? 'translate3d(0,0,0)' : 'translate3d(0,20px,0)',
      transitionProperty: 'opacity, transform',
      transitionDuration: '500ms',
      transitionTimingFunction: 'ease-out',
      transitionDelay: `${delay}s`,
      willChange: 'opacity, transform',
    }"
  >
    <slot />
  </div>
</template>
