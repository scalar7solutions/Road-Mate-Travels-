<script setup lang="ts">
import ScrollReveal from '@/Components/ScrollReveal.vue'
import SectionHeader from '@/Components/SectionHeader.vue'
</script>

<template>
  <section class="section-padding bg-muted/30">
    <div class="container-custom">
      <ScrollReveal>
        <SectionHeader
          title="Explore on the Map"
          subtitle="Visualize your journey across Sri Lanka"
          centered
        />
      </ScrollReveal>

      <ScrollReveal :delay="0.2">
        <div class="aspect-[16/9] max-w-4xl mx-auto rounded-2xl overflow-hidden bg-card shadow-card">
          <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary/5 to-secondary/5">
            <div class="text-center p-8">
              <div class="w-20 h-20 mx-auto mb-4 rounded-full gradient-primary flex items-center justify-center">
                <svg class="w-10 h-10 text-primary-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                  />
                </svg>
              </div>

              <h3 class="text-xl font-semibold text-foreground mb-2">
                Interactive Map Coming Soon
              </h3>
              <p class="text-muted-foreground">
                Explore destinations and plan your route visually
              </p>
            </div>
          </div>
        </div>
      </ScrollReveal>
    </div>
  </section>
</template>
