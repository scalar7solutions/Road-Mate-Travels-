<script setup lang="ts">
import ScrollReveal from './ScrollReveal.vue'
import StaggerContainer from './StaggerContainer.vue'
import StaggerItem from './StaggerItem.vue'
import SectionHeader from './SectionHeader.vue'

type Testimonial = {
  id: string
  name: string
  country: string
  avatar: string
  rating: number
  text: string
  packageName?: string
}

const testimonials: Testimonial[] = [
  {
    id: '1',
    name: 'Sarah Johnson',
    country: 'United Kingdom',
    avatar: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&q=80',
    rating: 5,
    text: 'Absolutely incredible experience! Our driver was knowledgeable, friendly, and made our tour unforgettable.',
    packageName: 'Cultural Triangle Explorer',
  },
  {
    id: '2',
    name: 'Michael Chen',
    country: 'Australia',
    avatar: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&q=80',
    rating: 5,
    text: 'The Hill Country Escape was magical. Professional service from start to finish.',
    packageName: 'Hill Country Escape',
  },
  {
    id: '3',
    name: 'Emma Wilson',
    country: 'Canada',
    avatar: 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=150&q=80',
    rating: 5,
    text: "We saw wildlife on our first safari! Perfectly organized and stress-free.",
    packageName: 'Wildlife Safari Adventure',
  },
  {
    id: '4',
    name: 'Hans Mueller',
    country: 'Germany',
    avatar: 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=150&q=80',
    rating: 5,
    text: 'Excellent vehicle rental service. Driver was punctual and the van was spotless.',
  },
  {
    id: '5',
    name: 'Priya Sharma',
    country: 'India',
    avatar: 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=150&q=80',
    rating: 5,
    text: 'Custom tour was exactly what we wanted. They accommodated all our requests.',
  },
  {
    id: '6',
    name: 'James Anderson',
    country: 'USA',
    avatar: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=150&q=80',
    rating: 4,
    text: 'Great overall experience. Beaches were stunning and everything was smooth.',
    packageName: 'Southern Beaches & Galle Fort',
  },
]
</script>

<template>
  <section class="py-16 lg:py-20 bg-gray-50/70 bg-muted/30 section-padding">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom">
      <ScrollReveal>
        <SectionHeader
          title="What Our Travelers Say"
          subtitle="Real experiences from real adventurers"
          :centered="true"
        />
      </ScrollReveal>

      <StaggerContainer className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <StaggerItem v-for="(t, idx) in testimonials.slice(0, 6)" :key="t.id" :index="idx">
          <div
            class="p-6 rounded-2xl bg-white bg-card shadow-lg shadow-card hover:shadow-xl hover:shadow-card-hover transition-shadow"
          >
            <div class="flex items-center gap-4 mb-4">
              <img
                :src="t.avatar"
                :alt="t.name"
                class="w-14 h-14 rounded-full object-cover border-2 border-indigo-600/20 border-primary/20"
              />
              <div>
                <h4 class="font-semibold text-gray-900 text-foreground">{{ t.name }}</h4>
                <p class="text-sm text-gray-600 text-muted-foreground">{{ t.country }}</p>
              </div>
            </div>

            <div class="flex gap-1 mb-3">
              <svg
                v-for="i in 5"
                :key="i"
                viewBox="0 0 24 24"
                class="w-4 h-4"
                :class="i <= t.rating ? 'text-yellow-500' : 'text-gray-300 text-muted'"
                aria-hidden="true"
              >
                <path
                  fill="currentColor"
                  d="M12 17.27 18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"
                />
              </svg>
            </div>

            <p class="text-gray-600 text-muted-foreground text-sm leading-relaxed mb-3">
              "{{ t.text }}"
            </p>

            <span v-if="t.packageName" class="text-xs text-indigo-600 text-primary font-medium">
              {{ t.packageName }}
            </span>
          </div>
        </StaggerItem>
      </StaggerContainer>
    </div>
  </section>
</template>
