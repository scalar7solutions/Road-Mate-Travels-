<!-- HomeBanner.vue -->
<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'
import HomeQuickSearch from './HomeQuickSearch.vue'

type Banner = {
  id: number | string
  name?: string
  description?: string | null
  video_url?: string | null
  image_url?: string | null
  image?: string | null
}

type HeroBtn = {
  label: string
  href: string
  variant: 'primary' | 'secondary' | 'whatsapp'
}

type HeroSlide = {
  id: string
  imageUrl?: string
  videoUrl?: string
  headline: string
  subtext: string
  ctaButtons: HeroBtn[]
}

const props = defineProps<{
  banners: Banner[]
}>()

const whatsappHref = 'https://wa.me/94771234567'

const fallbackSlides: HeroSlide[] = [
  {
    id: '1',
    imageUrl: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80',
    headline: 'Explore Sri Lanka with Road Mate',
    subtext:
      'Discover breathtaking landscapes, ancient temples, and pristine beaches with our curated tours',
    ctaButtons: [
      { label: 'View Packages', href: '/packages', variant: 'primary' },
      { label: 'Vehicle Rentals', href: '/rentals', variant: 'secondary' },
    ],
  },
  {
    id: '2',
    imageUrl: 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=1920&q=80',
    headline: 'Private Tours • Flexible Plans',
    subtext: 'Create your perfect journey with custom itineraries tailored to your preferences',
    ctaButtons: [
      { label: 'Custom Tour', href: '/custom-tour', variant: 'primary' },
      { label: 'Contact Us', href: '/contact', variant: 'secondary' },
    ],
  },
  {
    id: '3',
    imageUrl: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1920&q=80',
    headline: 'Vehicle Rentals with Driver / Self Drive',
    subtext: 'Premium fleet of vehicles for every journey - from luxury sedans to spacious vans',
    ctaButtons: [
      { label: 'Explore Rentals', href: '/rentals', variant: 'primary' },
      { label: 'WhatsApp Us', href: '#', variant: 'whatsapp' },
    ],
  },
]

const slides = computed<HeroSlide[]>(() => {
  const list = (props.banners ?? []).filter(Boolean)

  if (!list.length) return fallbackSlides

  return list.map((b, idx) => {
    const img = (b.image_url || b.image || '').trim()
    const vid = (b.video_url || '').trim()

    return {
      id: String(b.id ?? idx + 1),
      imageUrl: img || undefined,
      videoUrl: !img && vid ? vid : undefined,
      headline: b.name || fallbackSlides[idx % fallbackSlides.length].headline,
      subtext:
        (b.description ?? '')?.trim() || fallbackSlides[idx % fallbackSlides.length].subtext,
      ctaButtons: [
        { label: 'Custom Tour', href: '/custom-tour', variant: 'primary' },
        { label: 'Contact Us', href: '/contact', variant: 'secondary' },
      ],
    }
  })
})

const currentIndex = ref(0)
const isPaused = ref(false)

let intervalId: number | null = null

const stop = () => {
  if (intervalId) window.clearInterval(intervalId)
  intervalId = null
}

const start = () => {
  stop()
  if (slides.value.length <= 1) return
  intervalId = window.setInterval(() => {
    if (isPaused.value) return
    goToNext()
  }, 6000)
}

const goToNext = () => {
  const max = slides.value.length - 1
  currentIndex.value = currentIndex.value === max ? 0 : currentIndex.value + 1
}

const goToPrevious = () => {
  const max = slides.value.length - 1
  currentIndex.value = currentIndex.value === 0 ? max : currentIndex.value - 1
}

const goTo = (idx: number) => {
  currentIndex.value = Math.max(0, Math.min(idx, slides.value.length - 1))
}

onMounted(() => {
  start()
})

onBeforeUnmount(() => {
  stop()
})

watch(
  () => slides.value.length,
  () => {
    currentIndex.value = 0
    start()
  }
)

const currentSlide = computed(() => slides.value[currentIndex.value])
</script>

<template>
  <section
    class="relative h-[90vh] min-h-[600px] overflow-hidden"
    @mouseenter="isPaused = true"
    @mouseleave="isPaused = false"
  >
    <!-- Background (NOW: smooth fade + zoom like framer-motion) -->
    <Transition name="hero-bg" mode="out-in">
      <div :key="currentSlide?.id" class="absolute inset-0 hero-bg-layer">
        <!-- Image background -->
        <img
          v-if="currentSlide?.imageUrl"
          :src="currentSlide.imageUrl"
          :alt="currentSlide.headline"
          class="w-full h-full object-cover"
          draggable="false"
        />

        <!-- Video background -->
        <video
          v-else-if="currentSlide?.videoUrl"
          :src="currentSlide.videoUrl"
          class="w-full h-full object-cover"
          muted
          playsinline
          autoplay
          loop
        />

        <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-black/40 to-black/70" />
      </div>
    </Transition>

    <!-- Content -->
    <div class="absolute inset-0 flex items-center justify-center">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom text-center">
        <Transition name="hero-up" mode="out-in">
          <div :key="currentSlide?.id" class="will-change-transform">
            <h1
              class="text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold text-white mb-6 leading-tight"
            >
              {{ currentSlide?.headline }}
            </h1>
            <p class="text-lg md:text-xl text-white/90 max-w-3xl mx-auto mb-10">
              {{ currentSlide?.subtext }}
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center">
              <template v-for="(btn, i) in currentSlide?.ctaButtons" :key="i">
                <a
                  v-if="btn.variant === 'whatsapp'"
                  :href="whatsappHref"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold
                         bg-green-500 hover:bg-green-600 text-white transition-colors"
                >
                  <svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true">
                    <path
                      fill="currentColor"
                      d="M20.52 3.48A11.78 11.78 0 0 0 12.03 0C5.4 0 .01 5.38.01 12c0 2.11.55 4.17 1.6 6.01L0 24l6.17-1.62A11.92 11.92 0 0 0 12.03 24C18.66 24 24 18.62 24 12c0-3.2-1.25-6.2-3.48-8.52Z"
                    />
                  </svg>
                  {{ btn.label }}
                </a>

                <Link
                  v-else
                  :href="btn.href"
                  class="inline-flex items-center justify-center rounded-xl px-6 py-3 text-sm font-semibold transition-colors"
                  :class="
                    btn.variant === 'primary'
                      ? 'bg-indigo-600 hover:bg-indigo-700 text-white'
                      : 'border border-white text-white hover:bg-white hover:text-gray-900'
                  "
                >
                  {{ btn.label }}
                </Link>
              </template>
            </div>
          </div>
        </Transition>
      </div>
    </div>

    <!-- Navigation -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex items-center gap-6">
      <button
        type="button"
        @click="goToPrevious"
        class="p-2 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors"
        aria-label="Previous slide"
      >
        <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" aria-hidden="true">
          <path fill="currentColor" d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z" />
        </svg>
      </button>

      <div class="flex gap-3">
        <button
          v-for="(_, idx) in slides"
          :key="idx"
          type="button"
          @click="goTo(idx)"
          class="h-2 rounded-full transition-all duration-300"
          :class="idx === currentIndex ? 'w-8 bg-white' : 'w-2 bg-white/50 hover:bg-white/70'"
          :aria-label="`Go to slide ${idx + 1}`"
        />
      </div>

      <button
        type="button"
        @click="goToNext"
        class="p-2 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors"
        aria-label="Next slide"
      >
        <svg viewBox="0 0 24 24" class="w-6 h-6 text-white" aria-hidden="true">
          <path fill="currentColor" d="m8.59 16.59 1.41 1.41 6-6-6-6-1.41 1.41L13.17 12z" />
        </svg>
      </button>

      <button
        type="button"
        @click="isPaused = !isPaused"
        class="p-2 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-colors ml-2"
        :aria-label="isPaused ? 'Play slideshow' : 'Pause slideshow'"
      >
        <svg v-if="isPaused" viewBox="0 0 24 24" class="w-5 h-5 text-white" aria-hidden="true">
          <path fill="currentColor" d="M8 5v14l11-7z" />
        </svg>
        <svg v-else viewBox="0 0 24 24" class="w-5 h-5 text-white" aria-hidden="true">
          <path fill="currentColor" d="M6 5h4v14H6V5zm8 0h4v14h-4V5z" />
        </svg>
      </button>
    </div>

    <!-- Scroll indicator -->
    <div class="absolute bottom-8 right-8 hidden lg:block floaty">
      <div class="w-6 h-10 rounded-full border-2 border-white/50 flex justify-center pt-2">
        <div class="w-1.5 h-3 rounded-full bg-white/80" />
      </div>
    </div>
  </section>

  <!-- Quick Search -->
  <section class="relative z-10 -mt-24 pb-16">
    <HomeQuickSearch />
  </section>
</template>

<style scoped>
/* ========= HERO BACKGROUND TRANSITION (fade + zoom like framer-motion) ========= */
.hero-bg-layer {
  will-change: opacity, transform, filter;
  transform-origin: center;
}

/* timings + easing like TSX: ease [0.25, 0.1, 0.25, 1] ~= cubic-bezier(0.25,0.1,0.25,1) */
.hero-bg-enter-active {
  transition: opacity 0.8s cubic-bezier(0.25, 0.1, 0.25, 1),
    transform 0.8s cubic-bezier(0.25, 0.1, 0.25, 1),
    filter 0.8s cubic-bezier(0.25, 0.1, 0.25, 1);
}
.hero-bg-leave-active {
  transition: opacity 0.6s cubic-bezier(0.25, 0.1, 0.25, 1),
    transform 0.6s cubic-bezier(0.25, 0.1, 0.25, 1),
    filter 0.6s cubic-bezier(0.25, 0.1, 0.25, 1);
}

/* enter: start slightly zoomed in + tiny blur */
.hero-bg-enter-from {
  opacity: 0;
  transform: scale(1.08);
  filter: blur(2px);
}
.hero-bg-enter-to {
  opacity: 1;
  transform: scale(1);
  filter: blur(0px);
}

/* exit: fade out (keep it stable, or tiny zoom out if you want) */
.hero-bg-leave-from {
  opacity: 1;
  transform: scale(1);
  filter: blur(0px);
}
.hero-bg-leave-to {
  opacity: 0;
  transform: scale(1.02);
  filter: blur(2px);
}

/* ========= CONTENT TRANSITION (same as before) ========= */
.hero-up-enter-active,
.hero-up-leave-active {
  transition: opacity 0.5s ease, transform 0.5s ease;
}
.hero-up-enter-from {
  opacity: 0;
  transform: translateY(30px);
}
.hero-up-leave-to {
  opacity: 0;
  transform: translateY(-30px);
}

/* Scroll indicator float */
@keyframes floaty {
  0% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(10px);
  }
  100% {
    transform: translateY(0);
  }
}
.floaty {
  animation: floaty 2s ease-in-out infinite;
}
</style>
