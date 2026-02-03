<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import ScrollReveal from './ScrollReveal.vue'
import StaggerContainer from './StaggerContainer.vue'
import StaggerItem from './StaggerItem.vue'
import SectionHeader from './SectionHeader.vue'

type PricingOption = { basePrice: number }
type TourPackage = {
  id: string
  slug: string
  name: string
  destinationGroupName: string
  days: number
  maxPassengers: number
  rating: number
  featured: boolean
  coverImage: string
  pricingOptions: PricingOption[]
}

// design-only mock (connect later)
const packages: TourPackage[] = [
  {
    id: '1',
    slug: 'cultural-triangle-explorer',
    name: 'Cultural Triangle Explorer',
    destinationGroupName: 'Cultural Triangle',
    days: 5,
    maxPassengers: 6,
    rating: 4.9,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1588598198321-31fd5adb8cfd?w=800&q=80',
    pricingOptions: [{ basePrice: 150 }],
  },
  {
    id: '2',
    slug: 'hill-country-escape',
    name: 'Hill Country Escape',
    destinationGroupName: 'Hill Country',
    days: 4,
    maxPassengers: 4,
    rating: 4.8,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1586183189334-4c0e80c6f0ff?w=800&q=80',
    pricingOptions: [{ basePrice: 250 }],
  },
  {
    id: '3',
    slug: 'southern-beaches-tour',
    name: 'Southern Beaches & Galle Fort',
    destinationGroupName: 'Southern Coast',
    days: 3,
    maxPassengers: 6,
    rating: 4.7,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80',
    pricingOptions: [{ basePrice: 150 }],
  },
  {
    id: '4',
    slug: 'wildlife-safari-adventure',
    name: 'Wildlife Safari Adventure',
    destinationGroupName: 'Wildlife & Safari',
    days: 3,
    maxPassengers: 4,
    rating: 4.9,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?w=800&q=80',
    pricingOptions: [{ basePrice: 450 }],
  },
  {
    id: '5',
    slug: 'complete-sri-lanka',
    name: 'Complete Sri Lanka Experience',
    destinationGroupName: 'Cultural Triangle',
    days: 10,
    maxPassengers: 6,
    rating: 5.0,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80',
    pricingOptions: [{ basePrice: 450 }],
  },
  {
    id: '6',
    slug: 'adventure-seekers',
    name: 'Adventure Seekers Package',
    destinationGroupName: 'Adventure Trails',
    days: 4,
    maxPassengers: 8,
    rating: 4.8,
    featured: false,
    coverImage: 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&q=80',
    pricingOptions: [{ basePrice: 250 }],
  },
]

const formatPrice = (n: number) => `$${Math.round(n)}`
</script>

<template>
  <section class="py-16 lg:py-20 section-padding">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom">
      <ScrollReveal>
        <SectionHeader
          title="Popular Tour Packages"
          subtitle="Hand-picked tours designed for unforgettable experiences"
          ctaLabel="View All Packages"
          ctaHref="/packages"
        />
      </ScrollReveal>

      <StaggerContainer className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <StaggerItem
          v-for="(pkg, idx) in packages.filter(p => p.featured).slice(0, 6)"
          :key="pkg.id"
          :index="idx"
        >
          <div
            class="group relative overflow-hidden rounded-2xl bg-white bg-card shadow-lg shadow-card hover:shadow-xl hover:shadow-card-hover transition-all hover:-translate-y-2"
          >
            <Link :href="`/packages/${pkg.slug}`">
              <div class="aspect-[16/10] overflow-hidden relative">
                <img
                  :src="pkg.coverImage"
                  :alt="pkg.name"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <span
                  v-if="pkg.featured"
                  class="absolute top-4 left-4 px-3 py-1 text-xs font-semibold rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white gradient-primary"
                >
                  Featured
                </span>
              </div>

              <div class="p-5">
                <div class="flex items-center gap-2 text-sm text-gray-500 text-muted-foreground mb-2">
                  <!-- map pin -->
                  <svg viewBox="0 0 24 24" class="w-4 h-4 text-indigo-600 text-primary" aria-hidden="true">
                    <path
                      fill="currentColor"
                      d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"
                    />
                  </svg>
                  <span>{{ pkg.destinationGroupName }}</span>
                </div>

                <h3 class="text-lg font-semibold text-gray-900 text-foreground mb-3 group-hover:text-indigo-600 group-hover:text-primary transition-colors">
                  {{ pkg.name }}
                </h3>

                <div class="flex items-center gap-4 text-sm text-gray-500 text-muted-foreground mb-4">
                  <span class="flex items-center gap-1">
                    <!-- clock -->
                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 11h-5V7h2v4h3Z"
                      />
                    </svg>
                    {{ pkg.days }} Days
                  </span>

                  <span class="flex items-center gap-1">
                    <!-- users -->
                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3ZM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.92 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"
                      />
                    </svg>
                    Max {{ pkg.maxPassengers }}
                  </span>

                  <span class="flex items-center gap-1">
                    <!-- star -->
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-yellow-500" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
                      />
                    </svg>
                    {{ pkg.rating }}
                  </span>
                </div>

                <div class="flex items-center justify-between">
                  <div>
                    <span class="text-sm text-gray-500 text-muted-foreground">From</span>
                    <p class="text-xl font-bold text-indigo-600 text-primary">
                      {{ formatPrice(pkg.pricingOptions[0].basePrice) }}
                    </p>
                  </div>

                  <span class="flex items-center gap-1 text-indigo-600 text-primary font-medium group-hover:gap-2 transition-all">
                    View Details
                    <svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M13 5l7 7-7 7-1.41-1.41L16.17 13H4v-2h12.17l-4.58-4.59L13 5z"
                      />
                    </svg>
                  </span>
                </div>
              </div>
            </Link>
          </div>
        </StaggerItem>
      </StaggerContainer>
    </div>
  </section>
</template>
