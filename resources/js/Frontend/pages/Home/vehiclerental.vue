<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import ScrollReveal from './ScrollReveal.vue'
import StaggerContainer from './StaggerContainer.vue'
import StaggerItem from './StaggerItem.vue'
import SectionHeader from './SectionHeader.vue'

type ServiceType = { pricePerDay: number }
type Vehicle = {
  id: string
  slug: string
  name: string
  type: string
  capacity: number
  rating: number
  coverImage: string
  serviceTypes: ServiceType[]
}

const vehicles: Vehicle[] = [
  {
    id: '1',
    slug: 'toyota-prius',
    name: 'Toyota Prius',
    type: 'Sedan',
    capacity: 3,
    rating: 4.7,
    coverImage: 'https://images.unsplash.com/photo-1550355291-bbee04a92027?w=800&q=80',
    serviceTypes: [{ pricePerDay: 50 }],
  },
  {
    id: '2',
    slug: 'toyota-corolla',
    name: 'Toyota Corolla',
    type: 'Sedan',
    capacity: 4,
    rating: 4.8,
    coverImage: 'https://images.unsplash.com/photo-1623869675781-80aa31012a5a?w=800&q=80',
    serviceTypes: [{ pricePerDay: 60 }],
  },
  {
    id: '3',
    slug: 'toyota-kdh-van',
    name: 'Toyota KDH Van',
    type: 'Van',
    capacity: 8,
    rating: 4.9,
    coverImage: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=800&q=80',
    serviceTypes: [{ pricePerDay: 80 }],
  },
  {
    id: '4',
    slug: 'toyota-hiace',
    name: 'Toyota Hiace',
    type: 'Van',
    capacity: 12,
    rating: 4.8,
    coverImage: 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?w=800&q=80',
    serviceTypes: [{ pricePerDay: 120 }],
  },
]

const formatPrice = (n: number) => `$${Math.round(n)}`
</script>

<template>
  <section class="py-16 lg:py-20 bg-gray-50/70 bg-muted/30 section-padding">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom">
      <ScrollReveal>
        <SectionHeader
          title="Vehicle Rentals"
          subtitle="Premium fleet of vehicles for every journey - with or without driver"
          ctaLabel="Explore Rentals"
          ctaHref="/rentals"
        />
      </ScrollReveal>

      <StaggerContainer className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <StaggerItem v-for="(v, idx) in vehicles.slice(0, 4)" :key="v.id" :index="idx">
          <div
            class="group relative overflow-hidden rounded-2xl bg-white bg-card shadow-lg shadow-card hover:shadow-xl hover:shadow-card-hover transition-all hover:-translate-y-2"
          >
            <Link :href="`/rentals/${v.slug}`">
              <div class="aspect-[16/10] overflow-hidden">
                <img
                  :src="v.coverImage"
                  :alt="v.name"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
              </div>

              <div class="p-5">
                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full bg-indigo-600/10 text-indigo-600 bg-secondary/20 text-secondary mb-3">
                  {{ v.type }}
                </span>

                <h3 class="text-lg font-semibold text-gray-900 text-foreground mb-2 group-hover:text-indigo-600 group-hover:text-primary transition-colors">
                  {{ v.name }}
                </h3>

                <div class="flex items-center gap-4 text-sm text-gray-500 text-muted-foreground mb-4">
                  <span class="flex items-center gap-1">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3ZM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Z"
                      />
                    </svg>
                    {{ v.capacity }} Passengers
                  </span>

                  <span class="flex items-center gap-1">
                    <svg viewBox="0 0 24 24" class="w-4 h-4 text-yellow-500" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
                      />
                    </svg>
                    {{ v.rating }}
                  </span>
                </div>

                <div class="flex items-center justify-between">
                  <div>
                    <span class="text-sm text-gray-500 text-muted-foreground">From</span>
                    <p class="text-xl font-bold text-indigo-600 text-primary">
                      {{ formatPrice(v.serviceTypes[0].pricePerDay) }}/day
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
