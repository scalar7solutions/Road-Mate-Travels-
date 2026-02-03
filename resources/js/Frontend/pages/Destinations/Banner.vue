<script setup lang="ts">
import ScrollReveal from '@/Components/ScrollReveal.vue'
</script>

<template>
  <section class="relative py-20 lg:py-28 overflow-hidden">
    <div class="absolute inset-0">
      <img
        src="https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920&q=80"
        alt="Sri Lanka landscapes"
        class="w-full h-full object-cover"
      />
      <div class="absolute inset-0 bg-gradient-to-r from-foreground/80 to-foreground/40" />
    </div>

    <!-- ✅ Use real container sizing here -->
    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <ScrollReveal>
        <div class="max-w-2xl">
          <h1 class="text-4xl lg:text-5xl xl:text-6xl font-bold text-primary-foreground mb-6">
            Discover Sri Lanka's Treasures
          </h1>
          <p class="text-lg text-primary-foreground/80">
            From ancient ruins to pristine beaches, misty mountains to wildlife sanctuaries -
            explore the incredible diversity of the pearl of the Indian Ocean.
          </p>
        </div>
      </ScrollReveal>
    </div>
  </section>
</template>
