<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import ScrollReveal from './ScrollReveal.vue'
import StaggerContainer from './StaggerContainer.vue'
import StaggerItem from './StaggerItem.vue'
import SectionHeader from './SectionHeader.vue'

type TourPackageCard = {
  id: number | string
  slug: string
  name: string
  destinationGroupName: string
  placeName: string
  days: number
  maxPassengers: number
  coverImage: string | null
  fromPriceLkr: number
}

type PageProps = {
  tourPackages?: TourPackageCard[]
}

const page = usePage<PageProps>()

const packages = computed<TourPackageCard[]>(() => {
  const list = page.props.tourPackages ?? []
  return Array.isArray(list) ? list : []
})

const formatLkr = (n: number) => {
  const num = Number(n || 0)
  const formatted = new Intl.NumberFormat('en-LK', { maximumFractionDigits: 0 }).format(num)
  return `LKR ${formatted}`
}
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
          v-for="(pkg, idx) in packages.slice(0, 6)"
          :key="pkg.id"
          :index="idx"
        >
          <div
            class="group relative overflow-hidden rounded-2xl bg-white bg-card shadow-lg shadow-card hover:shadow-xl hover:shadow-card-hover transition-all hover:-translate-y-2"
          >
            <Link :href="`/packages/${pkg.slug}`">
              <div class="aspect-[16/10] overflow-hidden relative">
                <img
                  v-if="pkg.coverImage"
                  :src="pkg.coverImage"
                  :alt="pkg.name"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />
                <div
                  v-else
                  class="w-full h-full bg-gradient-to-br from-gray-200 via-gray-100 to-gray-300"
                />
              </div>

              <div class="p-5">
                <!-- destination group (location line) -->
                <div class="flex items-center gap-2 text-sm text-gray-500 text-muted-foreground mb-1">
                  <svg viewBox="0 0 24 24" class="w-4 h-4 text-indigo-600 text-primary" aria-hidden="true">
                    <path
                      fill="currentColor"
                      d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"
                    />
                  </svg>
                  <span>{{ pkg.destinationGroupName || '—' }}</span>
                </div>

                <!-- place name under destination group -->
                <div v-if="pkg.placeName" class="text-sm text-gray-500 text-muted-foreground mb-2">
                  <!-- {{ pkg.placeName }} -->
                </div>

                <h3
                  class="text-lg font-semibold text-gray-900 text-foreground mb-3 group-hover:text-indigo-600 group-hover:text-primary transition-colors"
                >
                  {{ pkg.name }}
                </h3>

                <!-- days + max passengers (ratings removed) -->
                <div class="flex items-center gap-4 text-sm text-gray-500 text-muted-foreground mb-4">
                  <span class="flex items-center gap-1">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 11h-5V7h2v4h3Z"
                      />
                    </svg>
                    {{ pkg.days }} Days
                  </span>

                  <span class="flex items-center gap-1">
                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3ZM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.92 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"
                      />
                    </svg>
                    Max {{ pkg.maxPassengers }}
                  </span>
                </div>

                <div class="flex items-center justify-between">
                  <div>
                    <span class="text-sm text-gray-500 text-muted-foreground">From</span>
                    <p class="text-xl font-bold text-indigo-600 text-primary">
                      {{ formatLkr(pkg.fromPriceLkr) }}
                    </p>
                    <p class="text-xs text-gray-500 text-muted-foreground">per person</p>
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

      <div v-if="packages.length === 0" class="mt-8 text-center text-sm text-gray-500">
        No tour packages available for today.
      </div>
    </div>
  </section>
</template>
