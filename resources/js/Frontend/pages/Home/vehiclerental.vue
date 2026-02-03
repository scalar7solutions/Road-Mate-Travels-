<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import ScrollReveal from './ScrollReveal.vue'
import StaggerContainer from './StaggerContainer.vue'
import StaggerItem from './StaggerItem.vue'
import SectionHeader from './SectionHeader.vue'

type RentalPackageCard = {
  id: number | string
  slug: string
  name: string
  vehicleTypeName: string
  passengerMax: number
  coverImage: string | null
  fromPricePerDayLkr: number
}

type PageProps = {
  rentalPackages?: RentalPackageCard[]
}

const page = usePage<PageProps>()

const vehicles = computed<RentalPackageCard[]>(() => {
  const list = page.props.rentalPackages ?? []
  return Array.isArray(list) ? list : []
})

const formatLkr = (n: number) => {
  const num = Number(n || 0)
  const formatted = new Intl.NumberFormat('en-LK', { maximumFractionDigits: 0 }).format(num)
  return `LKR ${formatted}`
}
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
                  v-if="v.coverImage"
                  :src="v.coverImage"
                  :alt="v.name"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <div
                  v-else
                  class="w-full h-full bg-gradient-to-br from-gray-200 via-gray-100 to-gray-300"
                />
              </div>

              <div class="p-5">
                <!-- Vehicle type -->
                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full bg-indigo-600/10 text-indigo-600 bg-secondary/20 text-secondary mb-3">
                  {{ v.vehicleTypeName || 'Vehicle' }}
                </span>

                <!-- Package name -->
                <h3 class="text-lg font-semibold text-gray-900 text-foreground mb-2 group-hover:text-indigo-600 group-hover:text-primary transition-colors">
                  {{ v.name }}
                </h3>

                <!-- Passenger max (rating removed) -->
                <div class="flex items-center gap-4 text-sm text-gray-500 text-muted-foreground mb-4">
                  <span class="flex items-center gap-1">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3ZM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Z"
                      />
                    </svg>
                    {{ v.passengerMax }} Passengers
                  </span>
                </div>

                <div class="flex items-center justify-between">
                  <div>
                    <span class="text-sm text-gray-500 text-muted-foreground">From</span>
                    <p class="text-xl font-bold text-indigo-600 text-primary">
                      {{ formatLkr(v.fromPricePerDayLkr) }}/day
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

      <div v-if="vehicles.length === 0" class="mt-8 text-center text-sm text-gray-500">
        No rental packages available right now.
      </div>
    </div>
  </section>

  
</template>
