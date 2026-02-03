<script setup lang="ts">
import ScrollReveal from './ScrollReveal.vue'
import StaggerContainer from './StaggerContainer.vue'
import StaggerItem from './StaggerItem.vue'
import SectionHeader from './SectionHeader.vue'

type Feature = { title: string; description: string; icon: 'shield' | 'route' | 'money' | 'support' }

const features: Feature[] = [
  { icon: 'shield', title: 'Safe & Reliable', description: 'Experienced drivers with excellent safety records and fully insured vehicles.' },
  { icon: 'route', title: 'Custom Itineraries', description: 'Flexible tour plans tailored to your preferences and travel style.' },
  { icon: 'money', title: 'Transparent Pricing', description: 'No hidden costs. Clear pricing with all inclusions listed upfront.' },
  { icon: 'support', title: '24/7 Support', description: 'Round-the-clock assistance via WhatsApp and phone for peace of mind.' },
]

const iconPath = (k: Feature['icon']) => {
  switch (k) {
    case 'shield':
      return 'M12 2l8 4v6c0 5-3.4 9.4-8 10-4.6-.6-8-5-8-10V6l8-4z'
    case 'route':
      return 'M7 6a3 3 0 106 0 3 3 0 00-6 0zm-4 14l6-6 4 4 8-8'
    case 'money':
      return 'M12 1C6.5 1 2 5.5 2 11s4.5 10 10 10 10-4.5 10-10S17.5 1 12 1zm1 15h-2v-1.2c-1.7-.3-3-1.5-3-3.3h2c0 1 .8 1.5 2 1.5s2-.4 2-1.3c0-1-1.2-1.2-2.5-1.5-1.5-.3-3.5-.8-3.5-3 0-1.7 1.3-2.9 3-3.2V5h2v1.2c1.7.3 3 1.5 3 3.3h-2c0-1-.8-1.5-2-1.5s-2 .4-2 1.3c0 1 1.2 1.2 2.5 1.5 1.5.3 3.5.8 3.5 3 0 1.7-1.3 2.9-3 3.2V16z'
    case 'support':
      return 'M12 1a9 9 0 00-9 9v4a3 3 0 003 3h1v-8H6a6 6 0 0112 0h-1v8h-1v2h2a3 3 0 003-3v-6a9 9 0 00-9-9z'
  }
}
</script>

<template>
  <section class="py-16 lg:py-20 section-padding">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom">
      <ScrollReveal>
        <SectionHeader
          title="Why Choose Road Mate?"
          subtitle="We go above and beyond to make your Sri Lankan adventure unforgettable"
          :centered="true"
        />
      </ScrollReveal>

      <StaggerContainer className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <StaggerItem v-for="(f, idx) in features" :key="idx" :index="idx">
          <div
            class="p-6 rounded-2xl bg-white bg-card shadow-lg shadow-card hover:shadow-xl hover:shadow-card-hover transition-shadow text-center group"
          >
            <div
              class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-600 gradient-primary flex items-center justify-center group-hover:scale-110 transition-transform"
            >
              <svg viewBox="0 0 24 24" class="w-8 h-8 text-white text-primary-foreground" aria-hidden="true">
                <path fill="currentColor" :d="iconPath(f.icon)" />
              </svg>
            </div>

            <h3 class="text-lg font-semibold text-gray-900 text-foreground mb-2">
              {{ f.title }}
            </h3>
            <p class="text-gray-600 text-muted-foreground text-sm">
              {{ f.description }}
            </p>
          </div>
        </StaggerItem>
      </StaggerContainer>
    </div>
  </section>
</template>
