<script setup lang="ts">
import ScrollReveal from './ScrollReveal.vue'
import SectionHeader from './SectionHeader.vue'
import FAQAccordion from './FAQAccordion.vue'

type FAQ = { id: string; question: string; answer: string }

const faqs: FAQ[] = [
  {
    id: '1',
    question: 'What is included in tour package prices?',
    answer:
      'Packages typically include transportation, a professional driver/guide, accommodation, daily breakfast, and key entrance fees. Exact inclusions are listed per package.',
  },
  {
    id: '2',
    question: 'Can I customize a tour package?',
    answer:
      'Yes. We can tailor the itinerary based on your preferences, timeline, and travel style.',
  },
  {
    id: '3',
    question: 'What documents do I need for vehicle rental?',
    answer:
      'For self-drive rentals, an international driving license and passport are typically required. For rentals with driver, only a passport copy is usually needed.',
  },
  {
    id: '4',
    question: 'What is your cancellation policy?',
    answer:
      'Policies depend on the package and season. We aim to keep cancellation terms clear and fair—details are provided during booking.',
  },
  {
    id: '5',
    question: 'Are your drivers English-speaking?',
    answer:
      'Yes, drivers are English-speaking and experienced with travelers.',
  },
]
</script>

<template>
  <section class="py-16 lg:py-20 section-padding">
    <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8 container-custom">
      <ScrollReveal>
        <SectionHeader
          title="Frequently Asked Questions"
          subtitle="Quick answers to common questions"
          ctaLabel="View All FAQs"
          ctaHref="/faq"
          :centered="true"
        />
      </ScrollReveal>

      <ScrollReveal :delay="0.2">
        <FAQAccordion :items="faqs" />
      </ScrollReveal>
    </div>
  </section>
</template>
