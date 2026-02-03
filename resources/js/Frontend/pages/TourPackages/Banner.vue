<script setup lang="ts">
import ScrollReveal from '@/Components/ScrollReveal.vue'
</script>

<template>
  <section class="relative py-16 lg:py-24 overflow-hidden">
    <div class="absolute inset-0">
      <img
        src="https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?w=1920&q=80"
        alt="Tour packages"
        class="w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-gradient-to-r from-foreground/80 to-foreground/40" />
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <ScrollReveal>
        <div class="max-w-2xl">
          <h1 class="text-4xl lg:text-5xl font-bold text-primary-foreground mb-4">
            Tour Packages
          </h1>
          <p class="text-lg text-primary-foreground/80">
            Carefully crafted itineraries for every type of traveler.
            From cultural explorations to beach getaways.
          </p>
        </div>
      </ScrollReveal>
    </div>
  </section>
</template>
