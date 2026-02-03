<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import ScrollReveal from '@/Components/ScrollReveal.vue'
import StaggerContainer from '@/Components/StaggerContainer.vue'
import StaggerItem from '@/Components/StaggerItem.vue'
import SectionHeader from '@/Components/SectionHeader.vue'

type DestinationGroup = {
  id: string
  slug: string
  name: string
  coverImage: string
  placesCount: number
}

const destinationGroups: DestinationGroup[] = [
  {
    id: '1',
    slug: 'cultural-triangle',
    name: 'Cultural Triangle',
    coverImage: 'https://images.unsplash.com/photo-1588598198321-31fd5adb8cfd?w=800&q=80',
    placesCount: 8,
  },
  {
    id: '2',
    slug: 'hill-country',
    name: 'Hill Country',
    coverImage: 'https://images.unsplash.com/photo-1586183189334-4c0e80c6f0ff?w=800&q=80',
    placesCount: 6,
  },
  {
    id: '3',
    slug: 'southern-coast',
    name: 'Southern Coast',
    coverImage: 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&q=80',
    placesCount: 7,
  },
  {
    id: '4',
    slug: 'wildlife-safari',
    name: 'Wildlife & Safari',
    coverImage: 'https://images.unsplash.com/photo-1564760055775-d63b17a55c44?w=800&q=80',
    placesCount: 5,
  },
  {
    id: '5',
    slug: 'east-coast',
    name: 'East Coast',
    coverImage: 'https://images.unsplash.com/photo-1509233725247-49e657c54213?w=800&q=80',
    placesCount: 4,
  },
  {
    id: '6',
    slug: 'adventure-trails',
    name: 'Adventure Trails',
    coverImage: 'https://images.unsplash.com/photo-1551632811-561732d1e306?w=800&q=80',
    placesCount: 5,
  },
]
</script>

<template>
  <section class="section-padding">
    <!-- ✅ Proper container sizing -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <ScrollReveal>
        <SectionHeader
          title="Destination Groups"
          subtitle="Choose your adventure from our curated destination collections"
          centered
        />
      </ScrollReveal>

      <StaggerContainer className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 lg:gap-8">
        <StaggerItem
          v-for="(d, idx) in destinationGroups"
          :key="d.id"
          :index="idx"
        >
          <div
            class="group relative overflow-hidden rounded-2xl bg-white bg-card shadow-lg shadow-card hover:shadow-xl hover:shadow-card-hover transition-all hover:-translate-y-2"
          >
            <Link :href="`/destinations/${d.slug}`">
              <div class="aspect-[4/3] overflow-hidden relative">
                <img
                  :src="d.coverImage"
                  :alt="d.name"
                  class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
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
              </div>
            </Link>
          </div>
        </StaggerItem>
      </StaggerContainer>
    </div>
  </section>
</template>
