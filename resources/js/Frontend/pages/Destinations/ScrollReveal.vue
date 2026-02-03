<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, ref } from 'vue'

const props = withDefaults(
  defineProps<{
    delay?: number
    direction?: 'up' | 'down' | 'left' | 'right'
    className?: string
  }>(),
  {
    delay: 0,
    direction: 'up',
    className: '',
  }
)

const el = ref<HTMLElement | null>(null)
const inView = ref(false)
let obs: IntersectionObserver | null = null

const initialTransform = computed(() => {
  switch (props.direction) {
    case 'up':
      return 'translate3d(0, 40px, 0)'
    case 'down':
      return 'translate3d(0, -40px, 0)'
    case 'left':
      return 'translate3d(40px, 0, 0)'
    case 'right':
      return 'translate3d(-40px, 0, 0)'
    default:
      return 'translate3d(0, 40px, 0)'
  }
})

onMounted(() => {
  obs = new IntersectionObserver(
    ([entry]) => {
      if (entry.isIntersecting) {
        inView.value = true
        obs?.disconnect()
      }
    },
    { root: null, threshold: 0.15, rootMargin: '-50px' }
  )

  if (el.value) obs.observe(el.value)
})

onBeforeUnmount(() => {
  obs?.disconnect()
  obs = null
})
</script>

<template>
  <div
    ref="el"
    :class="className"
    :style="{
      opacity: inView ? 1 : 0,
      transform: inView ? 'translate3d(0,0,0)' : initialTransform,
      transitionProperty: 'opacity, transform',
      transitionDuration: '600ms',
      transitionTimingFunction: 'ease-out',
      transitionDelay: `${delay}s`,
      willChange: 'opacity, transform',
    }"
  >
    <slot />
  </div>
</template>
