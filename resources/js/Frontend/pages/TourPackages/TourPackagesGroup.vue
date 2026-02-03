<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import PackageFilter from './PackageFilter.vue'
import StaggerContainer from '@/Components/StaggerContainer.vue'
import StaggerItem from '@/Components/StaggerItem.vue'

type DestinationGroup = { id: string; name: string }

type TourPackage = {
  id: string
  slug: string
  name: string
  destinationGroupId: string
  destinationGroupName: string
  days: number
  maxPassengers: number
  rating: number
  featured: boolean
  coverImage: string
  pricingOptions: Array<{ basePrice: number }>
}

const destinationGroups: DestinationGroup[] = [
  { id: 'cultural-triangle', name: 'Cultural Triangle' },
  { id: 'hill-country', name: 'Hill Country' },
  { id: 'southern-coast', name: 'Southern Coast' },
  { id: 'wildlife-safari', name: 'Wildlife & Safari' },
  { id: 'east-coast', name: 'East Coast' },
  { id: 'adventure-trails', name: 'Adventure Trails' },
]

const tourPackages: TourPackage[] = [
  {
    id: '1',
    slug: 'cultural-triangle-explorer',
    name: 'Cultural Triangle Explorer',
    destinationGroupId: 'cultural-triangle',
    destinationGroupName: 'Cultural Triangle',
    days: 5,
    maxPassengers: 6,
    rating: 4.9,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1588598198321-31fd5adb8cfd?w=800&q=80',
    pricingOptions: [{ basePrice: 15000 }],
  },
  {
    id: '2',
    slug: 'hill-country-escape',
    name: 'Hill Country Escape',
    destinationGroupId: 'hill-country',
    destinationGroupName: 'Hill Country',
    days: 4,
    maxPassengers: 4,
    rating: 4.8,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1586183189334-4c0e80c6f0ff?w=800&q=80',
    pricingOptions: [{ basePrice: 25000 }],
  },
  {
    id: '3',
    slug: 'southern-beaches-tour',
    name: 'Southern Beaches & Galle Fort',
    destinationGroupId: 'southern-coast',
    destinationGroupName: 'Southern Coast',
    days: 3,
    maxPassengers: 6,
    rating: 4.7,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80',
    pricingOptions: [{ basePrice: 18000 }],
  },
  {
    id: '4',
    slug: 'wildlife-safari-adventure',
    name: 'Wildlife Safari Adventure',
    destinationGroupId: 'wildlife-safari',
    destinationGroupName: 'Wildlife & Safari',
    days: 3,
    maxPassengers: 4,
    rating: 4.9,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?w=800&q=80',
    pricingOptions: [{ basePrice: 30000 }],
  },
  {
    id: '5',
    slug: 'complete-sri-lanka',
    name: 'Complete Sri Lanka Experience',
    destinationGroupId: 'cultural-triangle',
    destinationGroupName: 'Cultural Triangle',
    days: 10,
    maxPassengers: 6,
    rating: 5.0,
    featured: true,
    coverImage: 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&q=80',
    pricingOptions: [{ basePrice: 45000 }],
  },
  {
    id: '6',
    slug: 'adventure-seekers',
    name: 'Adventure Seekers Package',
    destinationGroupId: 'adventure-trails',
    destinationGroupName: 'Adventure Trails',
    days: 4,
    maxPassengers: 8,
    rating: 4.8,
    featured: false,
    coverImage: 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&q=80',
    pricingOptions: [{ basePrice: 22000 }],
  },
  // extra packages for pagination demo
  {
    id: '7',
    slug: 'east-coast-surf',
    name: 'East Coast Surf & Chill',
    destinationGroupId: 'east-coast',
    destinationGroupName: 'East Coast',
    days: 5,
    maxPassengers: 6,
    rating: 4.6,
    featured: false,
    coverImage: 'https://images.unsplash.com/photo-1509233725247-49e657c54213?w=800&q=80',
    pricingOptions: [{ basePrice: 28000 }],
  },
  {
    id: '8',
    slug: 'tea-country-trails',
    name: 'Tea Country Trails',
    destinationGroupId: 'hill-country',
    destinationGroupName: 'Hill Country',
    days: 7,
    maxPassengers: 6,
    rating: 4.7,
    featured: false,
    coverImage: 'https://images.unsplash.com/photo-1586183189334-4c0e80c6f0ff?w=800&q=80',
    pricingOptions: [{ basePrice: 38000 }],
  },
]

const isFilterOpen = ref(false)
const sortBy = ref<'popular' | 'price-low' | 'price-high' | 'duration' | 'rating'>('popular')
const filters = ref({
  destination: '',
  days: '',
  passengers: '',
  vehicleType: '',
})

const hasActiveFilters = computed(() => Object.values(filters.value).some((v) => v !== ''))

const clearFilters = () => {
  filters.value = { destination: '', days: '', passengers: '', vehicleType: '' }
}

const filteredPackages = computed(() => {
  let result = [...tourPackages]

  if (filters.value.destination) {
    result = result.filter((pkg) => pkg.destinationGroupId === filters.value.destination)
  }

  if (filters.value.days) {
    const [minRaw, maxRaw] = filters.value.days.split('-')
    const min = Number(minRaw)
    const max = maxRaw ? Number(maxRaw) : undefined

    result = result.filter((pkg) => {
      if (max) return pkg.days >= min && pkg.days <= max
      return pkg.days >= min
    })
  }

  if (filters.value.passengers) {
    const passengers = parseInt(filters.value.passengers)
    if (!Number.isNaN(passengers)) {
      result = result.filter((pkg) => pkg.maxPassengers >= passengers)
    }
  }

  switch (sortBy.value) {
    case 'price-low':
      result.sort((a, b) => a.pricingOptions[0].basePrice - b.pricingOptions[0].basePrice)
      break
    case 'price-high':
      result.sort((a, b) => b.pricingOptions[0].basePrice - a.pricingOptions[0].basePrice)
      break
    case 'duration':
      result.sort((a, b) => a.days - b.days)
      break
    case 'rating':
      result.sort((a, b) => b.rating - a.rating)
      break
    default:
      result.sort((a, b) => (b.featured ? 1 : 0) - (a.featured ? 1 : 0))
  }

  return result
})

// pagination 6 per page
const pageSize = 6
const currentPage = ref(1)

watch([filters, sortBy], () => {
  currentPage.value = 1
})

const totalPages = computed(() => Math.max(1, Math.ceil(filteredPackages.value.length / pageSize)))

const pagedPackages = computed(() => {
  const start = (currentPage.value - 1) * pageSize
  return filteredPackages.value.slice(start, start + pageSize)
})

const goToPage = (p: number) => {
  currentPage.value = Math.min(Math.max(1, p), totalPages.value)
}

const formatLkr = (n: number) => {
  const num = Number(n || 0)
  const formatted = new Intl.NumberFormat('en-LK', { maximumFractionDigits: 0 }).format(num)
  return `LKR ${formatted}`
}
</script>

<template>
  <section class="section-padding">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row gap-8">
        <PackageFilter
          v-model:isOpen="isFilterOpen"
          v-model:filters="filters"
          :destinationGroups="destinationGroups"
          :hasActiveFilters="hasActiveFilters"
          @clear="clearFilters"
        />

        <div class="flex-1">
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <p class="text-muted-foreground">
              Showing <span class="font-medium text-foreground">{{ filteredPackages.length }}</span> packages
            </p>
            <div class="flex items-center gap-2">
              <span class="text-sm text-muted-foreground">Sort by:</span>
              <select v-model="sortBy" class="input-field py-2 pr-10">
                <option value="popular">Most Popular</option>
                <option value="price-low">Price: Low to High</option>
                <option value="price-high">Price: High to Low</option>
                <option value="duration">Duration</option>
                <option value="rating">Highest Rated</option>
              </select>
            </div>
          </div>

          <template v-if="pagedPackages.length > 0">
            <StaggerContainer className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
              <StaggerItem v-for="(pkg, idx) in pagedPackages" :key="pkg.id" :index="idx">
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
                      <div class="flex items-center gap-2 text-sm text-muted-foreground mb-2">
                        <svg viewBox="0 0 24 24" class="w-4 h-4 text-indigo-600 text-primary" aria-hidden="true">
                          <path
                            fill="currentColor"
                            d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"
                          />
                        </svg>
                        <span>{{ pkg.destinationGroupName }}</span>
                      </div>

                      <h3 class="text-lg font-semibold text-foreground mb-3 group-hover:text-primary transition-colors">
                        {{ pkg.name }}
                      </h3>

                      <div class="flex items-center gap-4 text-sm text-muted-foreground mb-4">
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
                              d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3ZM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Z"
                            />
                          </svg>
                          Max {{ pkg.maxPassengers }}
                        </span>

                        <span class="flex items-center gap-1">
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
                          <span class="text-sm text-muted-foreground">From</span>
                          <p class="text-xl font-bold text-primary">
                            {{ formatLkr(pkg.pricingOptions[0]?.basePrice || 0) }}
                          </p>
                        </div>

                        <span class="flex items-center gap-1 text-primary font-medium group-hover:gap-2 transition-all">
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

            <!-- Pagination -->
            <div class="mt-10 flex items-center justify-center gap-2">
              <button
                type="button"
                class="px-3 py-2 rounded-xl border border-border hover:bg-muted transition-colors"
                :disabled="currentPage <= 1"
                @click="goToPage(currentPage - 1)"
              >
                Prev
              </button>

              <button
                v-for="p in totalPages"
                :key="p"
                type="button"
                class="px-3 py-2 rounded-xl border border-border transition-colors"
                :class="p === currentPage ? 'bg-muted text-primary border-muted' : 'hover:bg-muted'"
                @click="goToPage(p)"
              >
                {{ p }}
              </button>

              <button
                type="button"
                class="px-3 py-2 rounded-xl border border-border hover:bg-muted transition-colors"
                :disabled="currentPage >= totalPages"
                @click="goToPage(currentPage + 1)"
              >
                Next
              </button>
            </div>
          </template>

          <template v-else>
            <div class="text-center py-16">
              <div class="w-20 h-20 mx-auto mb-4 rounded-full bg-muted flex items-center justify-center">
                <svg viewBox="0 0 24 24" class="w-8 h-8 text-muted-foreground" aria-hidden="true">
                  <path fill="currentColor" d="M3 4h18l-7 8v6l-4 2v-8L3 4z" />
                </svg>
              </div>
              <h3 class="text-xl font-semibold text-foreground mb-2">No packages found</h3>
              <p class="text-muted-foreground mb-4">Try adjusting your filters</p>
              <button type="button" class="btn-primary" @click="clearFilters">
                Clear Filters
              </button>
            </div>
          </template>
        </div>
      </div>
    </div>
  </section>
</template>
