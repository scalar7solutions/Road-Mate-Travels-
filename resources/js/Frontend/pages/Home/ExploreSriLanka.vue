<script setup lang="ts">
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import ScrollReveal from './ScrollReveal.vue'
import StaggerContainer from './StaggerContainer.vue'
import StaggerItem from './StaggerItem.vue'
import SectionHeader from './SectionHeader.vue'

type DestinationGroup = {
  id: number | string
  slug: string
  name: string
  description?: string | null
  coverImage?: string | null
  placesCount: number
}

type PageProps = {
  destinationGroups?: DestinationGroup[]
}

const page = usePage<PageProps>()

const destinations = computed<DestinationGroup[]>(() => {
  const list = page.props.destinationGroups ?? []
  return Array.isArray(list) ? list : []
})

const safeCover = (url?: string | null) => {
  if (!url) return null
  return url
}
</script>

<template>
  <section class="py-16 lg:py-20 bg-gray-50/70 bg-muted/30 section-padding">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom">
      <ScrollReveal>
        <SectionHeader
          title="Explore Sri Lanka"
          subtitle="Discover the diverse landscapes and rich culture of the pearl of the Indian Ocean"
          ctaLabel="View All Destinations"
          ctaHref="/destinations"
        />
      </ScrollReveal>

      <StaggerContainer className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <StaggerItem
          v-for="(d, idx) in destinations"
          :key="d.id"
          :index="idx"
        >
          <div
            class="group relative overflow-hidden rounded-2xl bg-white bg-card shadow-lg shadow-card hover:shadow-xl hover:shadow-card-hover transition-all hover:-translate-y-2"
          >
            <Link :href="`/destinations/${d.slug}`">
              <div class="aspect-[4/3] overflow-hidden relative">
                <!-- Image (saved cover image) -->
                <img
                  v-if="safeCover(d.coverImage)"
                  :src="d.coverImage!"
                  :alt="d.name"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                />

                <!-- Fallback when no image yet -->
                <div
                  v-else
                  class="w-full h-full bg-gradient-to-br from-gray-200 via-gray-100 to-gray-300"
                />

                <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent gradient-card" />
              </div>

              <div class="absolute bottom-0 left-0 right-0 p-6">
                <h3 class="text-xl font-bold text-white text-primary-foreground mb-1">
                  {{ d.name }}
                </h3>

                <div class="flex items-center gap-2 text-white/80 text-primary-foreground/80 text-sm">
                  <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                    <path
                      fill="currentColor"
                      d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"
                    />
                  </svg>
                  <span>{{ d.placesCount }} places to explore</span>
                </div>

                <p v-if="d.description" class="mt-2 text-white/80 text-sm line-clamp-2">
                  {{ d.description }}
                </p>
              </div>
            </Link>
          </div>
        </StaggerItem>
      </StaggerContainer>

      <div v-if="destinations.length === 0" class="mt-8 text-center text-sm text-gray-500">
        No destination groups found yet.
      </div>
    </div>
  </section>
</template>
