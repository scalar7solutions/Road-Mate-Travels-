<!-- Footer.vue -->
<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

type NavItem = { label: string; href: string }
type SocialItem = { label: string; href: string }

const quickLinks: NavItem[] = [
  { label: 'Home', href: '/' },
  { label: 'Destinations', href: '/destinations' },
  { label: 'Tour Packages', href: '/packages' },
  { label: 'Vehicle Rentals', href: '/rentals' },
  { label: 'About Us', href: '/about' },
  { label: 'Contact', href: '/contact' },
]

const supportLinks: NavItem[] = [
  { label: 'FAQ', href: '/faq' },
  { label: 'Custom Tour Request', href: '/custom-tour' },
  { label: 'Terms of Service', href: '/legal' },
  { label: 'Privacy Policy', href: '/legal' },
  { label: 'Cancellation Policy', href: '/policies/cancellation' },
]

const socialLinks: SocialItem[] = [
  { label: 'Facebook', href: '#' },
  { label: 'Instagram', href: '#' },
  { label: 'Twitter', href: '#' },
  { label: 'YouTube', href: '#' },
  { label: 'TripAdvisor', href: '#' },
]

const year = new Date().getFullYear()

const whatsappHref = 'https://wa.me/94771234567'
</script>

<template>
  <footer class="bg-gray-900 text-white bg-foreground text-primary-foreground">
    <!-- Main Footer -->
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom py-16">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
        <!-- Brand -->
        <div class="lg:col-span-1">
          <Link href="/" class="flex items-center gap-2 mb-6">
            <div
              class="w-12 h-12 rounded-xl flex items-center justify-center
                     bg-gradient-to-br from-indigo-600 to-purple-600 gradient-primary"
            >
              <span class="text-2xl font-bold text-white text-primary-foreground">R</span>
            </div>

            <div>
              <span class="font-bold text-xl">Road Mate</span>
              <span class="block text-sm text-white/70 text-primary-foreground/70 -mt-0.5">
                Tour &amp; Travel
              </span>
            </div>
          </Link>

          <p class="text-white/70 text-primary-foreground/70 text-sm leading-relaxed mb-6">
            Your trusted travel partner in Sri Lanka. Discover the beauty of the pearl of the Indian Ocean
            with our curated tours and premium vehicle rentals.
          </p>

          <div class="flex gap-3">
            <!-- Facebook -->
            <a
              :href="socialLinks[0].href"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Facebook"
              class="w-10 h-10 rounded-full bg-white/10 bg-primary-foreground/10 hover:bg-indigo-600/80 hover:bg-primary/80
                     flex items-center justify-center transition-colors"
            >
              <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M13.5 22v-8h2.6l.4-3h-3V9.1c0-.9.2-1.5 1.5-1.5H16.6V5.1c-.3 0-1.4-.1-2.7-.1-2.7 0-4.4 1.6-4.4 4.6V11H7v3h2.5v8h4z"
                />
              </svg>
            </a>

            <!-- Instagram -->
            <a
              :href="socialLinks[1].href"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Instagram"
              class="w-10 h-10 rounded-full bg-white/10 bg-primary-foreground/10 hover:bg-indigo-600/80 hover:bg-primary/80
                     flex items-center justify-center transition-colors"
            >
              <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5Zm10 2H7a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3Zm-5 4.5A5.5 5.5 0 1 1 6.5 14 5.5 5.5 0 0 1 12 8.5Zm0 2A3.5 3.5 0 1 0 15.5 14 3.5 3.5 0 0 0 12 10.5ZM18 6.7a.9.9 0 1 1-.9.9.9.9 0 0 1 .9-.9Z"
                />
              </svg>
            </a>

            <!-- Twitter -->
            <a
              :href="socialLinks[2].href"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="Twitter"
              class="w-10 h-10 rounded-full bg-white/10 bg-primary-foreground/10 hover:bg-indigo-600/80 hover:bg-primary/80
                     flex items-center justify-center transition-colors"
            >
              <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M22 5.8c-.7.3-1.4.6-2.2.7.8-.5 1.3-1.2 1.6-2.1-.7.4-1.5.8-2.4.9A3.6 3.6 0 0 0 12.8 8c0 .3 0 .6.1.9-3-.2-5.7-1.6-7.5-3.9-.3.6-.5 1.2-.5 1.9 0 1.3.7 2.5 1.7 3.2-.6 0-1.2-.2-1.7-.5v.1c0 1.9 1.3 3.4 3.1 3.8-.3.1-.7.1-1 .1-.2 0-.5 0-.7-.1.5 1.6 2 2.7 3.7 2.7A7.2 7.2 0 0 1 2 18.4 10.2 10.2 0 0 0 7.5 20c6.6 0 10.3-5.6 10.3-10.4v-.5c.7-.5 1.3-1.2 1.9-2Z"
                />
              </svg>
            </a>

            <!-- YouTube -->
            <a
              :href="socialLinks[3].href"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="YouTube"
              class="w-10 h-10 rounded-full bg-white/10 bg-primary-foreground/10 hover:bg-indigo-600/80 hover:bg-primary/80
                     flex items-center justify-center transition-colors"
            >
              <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M21.6 7.2a3 3 0 0 0-2.1-2.1C17.6 4.6 12 4.6 12 4.6s-5.6 0-7.5.5A3 3 0 0 0 2.4 7.2 31.6 31.6 0 0 0 2 12a31.6 31.6 0 0 0 .4 4.8 3 3 0 0 0 2.1 2.1c1.9.5 7.5.5 7.5.5s5.6 0 7.5-.5a3 3 0 0 0 2.1-2.1A31.6 31.6 0 0 0 22 12a31.6 31.6 0 0 0-.4-4.8ZM10 15.5v-7l6 3.5-6 3.5Z"
                />
              </svg>
            </a>

            <!-- TripAdvisor -->
            <a
              :href="socialLinks[4].href"
              target="_blank"
              rel="noopener noreferrer"
              aria-label="TripAdvisor"
              class="w-10 h-10 rounded-full bg-white/10 bg-primary-foreground/10 hover:bg-indigo-600/80 hover:bg-primary/80
                     flex items-center justify-center transition-colors"
            >
              <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M12 3c2.1 0 4 .6 5.6 1.6h2.3l-1.2 1.2c.8 1 1.3 2.2 1.3 3.6 0 3.3-2.7 6-6 6-1.5 0-2.8-.5-3.9-1.4L12 16.5l1.9-2.5c-1.1.9-2.4 1.4-3.9 1.4-3.3 0-6-2.7-6-6 0-1.4.5-2.6 1.3-3.6L4.1 4.6h2.3C8 3.6 9.9 3 12 3Zm-3 8.5A2.5 2.5 0 1 0 6.5 9 2.5 2.5 0 0 0 9 11.5Zm6 0A2.5 2.5 0 1 0 14.5 9 2.5 2.5 0 0 0 15 11.5Z"
                />
              </svg>
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="font-semibold text-lg mb-6">Quick Links</h4>
          <ul class="space-y-3">
            <li v-for="l in quickLinks" :key="l.href">
              <Link
                :href="l.href"
                class="text-white/70 text-primary-foreground/70 hover:text-white hover:text-primary-foreground hover:pl-1 transition-all text-sm"
              >
                {{ l.label }}
              </Link>
            </li>
          </ul>
        </div>

        <!-- Support -->
        <div>
          <h4 class="font-semibold text-lg mb-6">Support</h4>
          <ul class="space-y-3">
            <li v-for="l in supportLinks" :key="l.label">
              <Link
                :href="l.href"
                class="text-white/70 text-primary-foreground/70 hover:text-white hover:text-primary-foreground hover:pl-1 transition-all text-sm"
              >
                {{ l.label }}
              </Link>
            </li>
          </ul>
        </div>

        <!-- Contact -->
        <div>
          <h4 class="font-semibold text-lg mb-6">Contact Us</h4>
          <ul class="space-y-4">
            <li class="flex items-start gap-3">
              <!-- Map Pin -->
              <svg viewBox="0 0 24 24" class="w-5 h-5 text-indigo-400 text-primary mt-0.5 flex-shrink-0" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5Z"
                />
              </svg>
              <span class="text-sm text-white/70 text-primary-foreground/70">
                123 Galle Road, Colombo 03,<br />Sri Lanka
              </span>
            </li>

            <li class="flex items-center gap-3">
              <!-- Phone -->
              <svg viewBox="0 0 24 24" class="w-5 h-5 text-indigo-400 text-primary flex-shrink-0" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M6.6 10.8c1.5 3 3.9 5.4 6.9 6.9l2.3-2.3c.3-.3.8-.4 1.2-.2 1 .4 2.1.6 3.2.6.7 0 1.3.6 1.3 1.3V20c0 .7-.6 1.3-1.3 1.3C10.4 21.3 2.7 13.6 2.7 4.3 2.7 3.6 3.3 3 4 3h3.2c.7 0 1.3.6 1.3 1.3 0 1.1.2 2.2.6 3.2.1.4 0 .9-.2 1.2L6.6 10.8Z"
                />
              </svg>
              <a
                href="tel:+94771234567"
                class="text-sm text-white/70 text-primary-foreground/70 hover:text-white hover:text-primary-foreground transition-colors"
              >
                +94 77 123 4567
              </a>
            </li>

            <li class="flex items-center gap-3">
              <!-- WhatsApp -->
              <svg viewBox="0 0 24 24" class="w-5 h-5 text-indigo-400 text-primary flex-shrink-0" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M20.52 3.48A11.78 11.78 0 0 0 12.03 0C5.4 0 .01 5.38.01 12c0 2.11.55 4.17 1.6 6.01L0 24l6.17-1.62A11.92 11.92 0 0 0 12.03 24C18.66 24 24 18.62 24 12c0-3.2-1.25-6.2-3.48-8.52Z"
                />
              </svg>
              <a
                :href="whatsappHref"
                target="_blank"
                rel="noopener noreferrer"
                class="text-sm text-white/70 text-primary-foreground/70 hover:text-white hover:text-primary-foreground transition-colors"
              >
                +94 77 123 4567
              </a>
            </li>

            <li class="flex items-center gap-3">
              <!-- Mail -->
              <svg viewBox="0 0 24 24" class="w-5 h-5 text-indigo-400 text-primary flex-shrink-0" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2Zm0 4-8 5L4 8V6l8 5 8-5v2Z"
                />
              </svg>
              <a
                href="mailto:info@roadmate.lk"
                class="text-sm text-white/70 text-primary-foreground/70 hover:text-white hover:text-primary-foreground transition-colors"
              >
                info@roadmate.lk
              </a>
            </li>

            <li class="flex items-start gap-3">
              <!-- Clock -->
              <svg viewBox="0 0 24 24" class="w-5 h-5 text-indigo-400 text-primary mt-0.5 flex-shrink-0" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2Zm1 11h-5V7h2v4h3Z"
                />
              </svg>
              <span class="text-sm text-white/70 text-primary-foreground/70">
                Mon - Sun: 8:00 AM - 8:00 PM<br />
                24/7 WhatsApp Support
              </span>
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Bottom Bar -->
    <div class="border-t border-white/10 border-primary-foreground/10">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom py-6">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 text-sm text-white/60 text-primary-foreground/60">
          <p>© {{ year }} Road Mate Tour &amp; Travel. All rights reserved.</p>
          <div class="flex gap-6">
            <Link href="/legal" class="hover:text-white hover:text-primary-foreground transition-colors">Terms</Link>
            <Link href="/legal" class="hover:text-white hover:text-primary-foreground transition-colors">Privacy</Link>
            <Link href="/policies/cancellation" class="hover:text-white hover:text-primary-foreground transition-colors">
              Cancellation
            </Link>
          </div>
        </div>
      </div>
    </div>
  </footer>
</template>
