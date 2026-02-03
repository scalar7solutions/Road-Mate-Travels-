<script setup lang="ts">
import { Link, usePage, router } from '@inertiajs/vue3'
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue'

type NavItem = { label: string; href: string }

const page = usePage()

const isMobileMenuOpen = ref(false)
const isUserMenuOpen = ref(false)

const authModalOpen = ref(false)
const authMode = ref<'login' | 'register'>('login')

/**
 * Safe route helper:
 * - Uses Ziggy `route()` when available + route exists
 * - Falls back to plain paths otherwise
 */
// eslint-disable-next-line @typescript-eslint/no-explicit-any
const safeRoute = (name: string, params: any, fallback: string) => {
  try {
    // eslint-disable-next-line @typescript-eslint/no-explicit-any
    return (route as any)(name as any, params as any) as string
  } catch {
    return fallback
  }
}

const currentPath = computed(() => {
  const url = (page.url as string) || '/'
  return url.split('?')[0] || '/'
})

// Auth/user (adjust these keys if your app exposes user differently)
const user = computed(() => {
  // eslint-disable-next-line @typescript-eslint/no-explicit-any
  const props = page.props as any
  return props?.auth?.user ?? props?.user ?? null
})
const isAuthenticated = computed(() => !!user.value)

const navLinks = computed<NavItem[]>(() => [
  { label: 'Home', href: safeRoute('frontend.root', {}, '/') },
  { label: 'Destinations', href: '/destinations' },
  { label: 'Tour Packages', href: '/packages' },
  { label: 'Vehicle Rentals', href: '/rentals' },
  { label: 'About', href: '/about' },
  { label: 'Contact', href: '/contact' },
])

const isActive = (href: string) => {
  const path = currentPath.value
  const normalizedHref = href === '' ? '/' : href

  if (normalizedHref === '/' || normalizedHref === safeRoute('frontend.root', {}, '/')) {
    return path === '/' || path === safeRoute('frontend.root', {}, '/')
  }
  return path === normalizedHref || path.startsWith(normalizedHref + '/')
}

const openAuthModal = (mode: 'login' | 'register') => {
  authMode.value = mode
  authModalOpen.value = true
  isMobileMenuOpen.value = false
}

const closeAuthModal = () => {
  authModalOpen.value = false
}

const loginHref = computed(() => safeRoute('login', {}, '/login'))
const registerHref = computed(() => safeRoute('register', {}, '/register'))
const accountHref = computed(() => '/account')
const bookingsHref = computed(() => '/account/bookings')

const handleLogout = () => {
  isUserMenuOpen.value = false
  isMobileMenuOpen.value = false

  // Prefer Laravel default named route if available
  try {
    router.post(safeRoute('logout', {}, '/logout'))
  } catch {
    router.post('/logout')
  }
}

const whatsappHref = 'https://wa.me/94771234567'

const onKeydown = (e: KeyboardEvent) => {
  if (e.key === 'Escape') {
    isMobileMenuOpen.value = false
    isUserMenuOpen.value = false
    authModalOpen.value = false
  }
}

const onDocClick = (e: MouseEvent) => {
  const target = e.target as HTMLElement | null
  if (!target) return

  // Close user dropdown when clicking outside anything marked with data-user-menu
  const insideUserMenu = !!target.closest('[data-user-menu]')
  if (!insideUserMenu) isUserMenuOpen.value = false
}

watch(
  () => page.url,
  () => {
    // close menus on navigation
    isMobileMenuOpen.value = false
    isUserMenuOpen.value = false
  }
)

onMounted(() => {
  document.addEventListener('keydown', onKeydown)
  document.addEventListener('click', onDocClick)
})
onBeforeUnmount(() => {
  document.removeEventListener('keydown', onKeydown)
  document.removeEventListener('click', onDocClick)
})
</script>

<template>

    <!-- Header -->
    <header
      class="sticky top-0 z-40 border-b backdrop-blur-md
             bg-white/95 border-gray-200
             bg-background/95 border-border"
    >
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom">
        <div class="flex items-center justify-between h-16 lg:h-20">
          <!-- Logo -->
          <Link :href="safeRoute('frontend.root', {}, '/')" class="flex items-center gap-2">
            <div
              class="w-10 h-10 rounded-xl flex items-center justify-center
                     bg-gradient-to-br from-indigo-600 to-purple-600
                     gradient-primary"
            >
              <span class="text-xl font-bold text-white text-primary-foreground">R</span>
            </div>

            <div class="hidden sm:block">
              <span class="font-bold text-lg text-gray-900 text-foreground">Road Mate</span>
              <span class="block text-xs text-gray-500 text-muted-foreground -mt-0.5">Tour & Travel</span>
            </div>
          </Link>

          <!-- Desktop Navigation -->
          <nav class="hidden lg:flex items-center gap-1">
            <Link
              v-for="link in navLinks"
              :key="link.href"
              :href="link.href"
              class="px-4 py-2 text-sm font-medium rounded-lg transition-colors
                     text-gray-900 hover:text-indigo-600 hover:bg-gray-100
                     text-foreground hover:text-primary hover:bg-muted"
              :class="isActive(link.href) ? 'bg-gray-100 text-indigo-600 bg-muted text-primary' : ''"
            >
              {{ link.label }}
            </Link>
          </nav>

          <!-- Desktop Actions -->
          <div class="hidden lg:flex items-center gap-3">
            <a
              :href="whatsappHref"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition-colors
                     bg-green-500 hover:bg-green-600 text-white
                     btn-whatsapp"
            >
              <!-- WhatsApp icon -->
              <svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M20.52 3.48A11.78 11.78 0 0 0 12.03 0C5.4 0 .01 5.38.01 12c0 2.11.55 4.17 1.6 6.01L0 24l6.17-1.62A11.92 11.92 0 0 0 12.03 24C18.66 24 24 18.62 24 12c0-3.2-1.25-6.2-3.48-8.52Zm-8.49 18.5c-1.89 0-3.74-.5-5.37-1.45l-.38-.23-3.66.96.98-3.57-.25-.37A9.9 9.9 0 0 1 2.1 12c0-5.47 4.45-9.92 9.93-9.92 2.65 0 5.14 1.03 7.02 2.9A9.86 9.86 0 0 1 21.96 12c0 5.46-4.46 9.98-9.93 9.98Zm5.44-7.42c-.3-.15-1.77-.87-2.04-.97-.27-.1-.47-.15-.67.15-.2.3-.77.97-.95 1.17-.17.2-.35.22-.65.07-.3-.15-1.26-.46-2.4-1.47-.89-.79-1.5-1.77-1.67-2.07-.17-.3-.02-.46.13-.61.13-.13.3-.35.45-.52.15-.17.2-.3.3-.5.1-.2.05-.37-.02-.52-.07-.15-.67-1.61-.92-2.2-.24-.58-.49-.5-.67-.5l-.57-.01c-.2 0-.52.07-.8.37-.27.3-1.04 1.02-1.04 2.5 0 1.47 1.07 2.9 1.22 3.1.15.2 2.1 3.2 5.08 4.49.71.31 1.26.49 1.7.63.71.23 1.36.2 1.87.12.57-.09 1.77-.72 2.02-1.42.25-.7.25-1.3.17-1.42-.07-.12-.27-.2-.57-.35Z"
                />
              </svg>
              WhatsApp
            </a>

            <!-- Auth / User Menu -->
            <div v-if="isAuthenticated && user" class="relative" data-user-menu>
              <button
                type="button"
                class="flex items-center gap-2 px-3 py-2 rounded-xl transition-colors
                       hover:bg-gray-100 hover:bg-muted"
                @click="isUserMenuOpen = !isUserMenuOpen"
                :aria-expanded="isUserMenuOpen ? 'true' : 'false'"
              >
                <img
                  :src="user?.avatar_url || user?.avatar || 'https://ui-avatars.com/api/?name=User&background=ddd&color=111'"
                  :alt="user?.name || 'User'"
                  class="w-8 h-8 rounded-full"
                />
                <span class="text-sm font-medium text-gray-900 text-foreground">
                  {{ (user?.name || 'User').split(' ')[0] }}
                </span>

                <!-- Chevron -->
                <svg
                  viewBox="0 0 20 20"
                  class="w-4 h-4 transition-transform text-gray-700 text-foreground"
                  :class="isUserMenuOpen ? 'rotate-180' : ''"
                  aria-hidden="true"
                >
                  <path
                    fill="currentColor"
                    fill-rule="evenodd"
                    d="M5.23 7.21a.75.75 0 0 1 1.06.02L10 11.17l3.71-3.94a.75.75 0 1 1 1.08 1.04l-4.25 4.5a.75.75 0 0 1-1.08 0l-4.25-4.5a.75.75 0 0 1 .02-1.06Z"
                    clip-rule="evenodd"
                  />
                </svg>
              </button>

              <Transition
                enter-active-class="transition ease-out duration-150"
                enter-from-class="opacity-0 translate-y-2"
                enter-to-class="opacity-100 translate-y-0"
                leave-active-class="transition ease-in duration-150"
                leave-from-class="opacity-100 translate-y-0"
                leave-to-class="opacity-0 translate-y-2"
              >
                <div
                  v-if="isUserMenuOpen"
                  class="absolute right-0 mt-2 w-48 rounded-xl shadow-xl overflow-hidden
                         bg-white border border-gray-200
                         bg-card border-border"
                >
                  <Link
                    :href="accountHref"
                    class="flex items-center gap-3 px-4 py-3 text-sm transition-colors
                           text-gray-900 hover:bg-gray-100
                           text-foreground hover:bg-muted"
                    @click="isUserMenuOpen = false"
                  >
                    <!-- User icon -->
                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M12 12a4.5 4.5 0 1 0-4.5-4.5A4.5 4.5 0 0 0 12 12Zm0 2c-4.42 0-8 2.24-8 5v1h16v-1c0-2.76-3.58-5-8-5Z"
                      />
                    </svg>
                    My Profile
                  </Link>

                  <Link
                    :href="bookingsHref"
                    class="flex items-center gap-3 px-4 py-3 text-sm transition-colors
                           text-gray-900 hover:bg-gray-100
                           text-foreground hover:bg-muted"
                    @click="isUserMenuOpen = false"
                  >
                    <!-- Book icon -->
                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M6 2h11a3 3 0 0 1 3 3v16a1 1 0 0 1-1 1H7a3 3 0 0 1-3-3V3a1 1 0 0 1 1-1Zm1 2v15a1 1 0 0 0 1 1h10V5a1 1 0 0 0-1-1H7Zm2 3h6v2H9V7Zm0 4h6v2H9v-2Z"
                      />
                    </svg>
                    My Bookings
                  </Link>

                  <button
                    type="button"
                    class="w-full flex items-center gap-3 px-4 py-3 text-sm transition-colors
                           text-red-600 hover:bg-gray-100
                           text-destructive hover:bg-muted"
                    @click="handleLogout"
                  >
                    <!-- Logout icon -->
                    <svg viewBox="0 0 24 24" class="w-4 h-4" aria-hidden="true">
                      <path
                        fill="currentColor"
                        d="M10 2h8a2 2 0 0 1 2 2v16a2 2 0 0 1-2 2h-8v-2h8V4h-8V2Zm-1.5 6.5 1.41 1.41L8.83 11H16v2H8.83l1.08 1.09-1.41 1.41L5 12l3.5-3.5Z"
                      />
                    </svg>
                    Logout
                  </button>
                </div>
              </Transition>
            </div>

            <template v-else>
              <button
                type="button"
                class="px-4 py-2 text-sm font-medium transition-colors
                       text-gray-900 hover:text-indigo-600
                       text-foreground hover:text-primary"
                @click="openAuthModal('login')"
              >
                Login
              </button>

              <button
                type="button"
                class="inline-flex items-center justify-center rounded-xl px-4 py-2 text-sm font-semibold transition-colors
                       bg-indigo-600 hover:bg-indigo-700 text-white
                       btn-primary"
                @click="openAuthModal('register')"
              >
                Register
              </button>
            </template>
          </div>

          <!-- Mobile Menu Button -->
          <button
            type="button"
            class="lg:hidden p-2 rounded-lg transition-colors
                   hover:bg-gray-100 hover:bg-muted"
            aria-label="Open menu"
            @click="isMobileMenuOpen = true"
          >
            <!-- Menu icon -->
            <svg viewBox="0 0 24 24" class="w-6 h-6 text-gray-900 text-foreground" aria-hidden="true">
              <path fill="currentColor" d="M4 6h16v2H4V6Zm0 5h16v2H4v-2Zm0 5h16v2H4v-2Z" />
            </svg>
          </button>
        </div>
      </div>
    </header>

    <!-- Mobile Menu -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-opacity duration-150"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-if="isMobileMenuOpen"
          class="fixed inset-0 z-50 lg:hidden bg-black/50 bg-foreground/50"
          @click="isMobileMenuOpen = false"
        />
      </Transition>

      <Transition
        enter-active-class="transition-transform duration-200 ease-out"
        enter-from-class="translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transition-transform duration-200 ease-in"
        leave-from-class="translate-x-0"
        leave-to-class="translate-x-full"
      >
        <aside
          v-if="isMobileMenuOpen"
          class="fixed top-0 right-0 bottom-0 w-80 z-50 lg:hidden shadow-2xl
                 bg-white
                 bg-background"
          @click.stop
        >
          <div class="flex items-center justify-between p-4 border-b border-gray-200 border-border">
            <span class="font-bold text-lg text-gray-900 text-foreground">Menu</span>
            <button
              type="button"
              class="p-2 rounded-lg transition-colors hover:bg-gray-100 hover:bg-muted"
              aria-label="Close menu"
              @click="isMobileMenuOpen = false"
            >
              <!-- X icon -->
              <svg viewBox="0 0 24 24" class="w-6 h-6 text-gray-900 text-foreground" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M18.3 5.71 12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.29 19.71 2.88 18.29 9.17 12 2.88 5.71 4.29 4.29l6.3 6.3 6.3-6.3 1.41 1.42Z"
                />
              </svg>
            </button>
          </div>

          <nav class="p-4 space-y-1">
            <Link
              v-for="link in navLinks"
              :key="link.href"
              :href="link.href"
              class="block px-4 py-3 font-medium rounded-xl transition-colors
                     text-gray-900 hover:bg-gray-100
                     text-foreground hover:bg-muted"
              :class="isActive(link.href) ? 'bg-gray-100 bg-muted text-indigo-600 text-primary' : ''"
              @click="isMobileMenuOpen = false"
            >
              {{ link.label }}
            </Link>
          </nav>

          <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 border-border space-y-3">
            <a
              :href="whatsappHref"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex w-full items-center justify-center gap-2 rounded-xl px-4 py-3 text-sm font-semibold transition-colors
                     bg-green-500 hover:bg-green-600 text-white
                     btn-whatsapp"
            >
              <svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M20.52 3.48A11.78 11.78 0 0 0 12.03 0C5.4 0 .01 5.38.01 12c0 2.11.55 4.17 1.6 6.01L0 24l6.17-1.62A11.92 11.92 0 0 0 12.03 24C18.66 24 24 18.62 24 12c0-3.2-1.25-6.2-3.48-8.52Zm-8.49 18.5c-1.89 0-3.74-.5-5.37-1.45l-.38-.23-3.66.96.98-3.57-.25-.37A9.9 9.9 0 0 1 2.1 12c0-5.47 4.45-9.92 9.93-9.92 2.65 0 5.14 1.03 7.02 2.9A9.86 9.86 0 0 1 21.96 12c0 5.46-4.46 9.98-9.93 9.98Z"
                />
              </svg>
              WhatsApp Us
            </a>

            <template v-if="isAuthenticated && user">
              <Link
                :href="accountHref"
                class="inline-flex w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition-colors
                       bg-gray-100 hover:bg-gray-200 text-gray-900
                       btn-secondary hover:bg-muted text-foreground"
                @click="isMobileMenuOpen = false"
              >
                My Account
              </Link>

              <button
                type="button"
                class="w-full py-3 font-medium text-red-600 text-destructive"
                @click="handleLogout"
              >
                Logout
              </button>
            </template>

            <template v-else>
              <div class="flex gap-3">
                <button
                  type="button"
                  class="inline-flex flex-1 items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition-colors
                         bg-gray-100 hover:bg-gray-200 text-gray-900
                         btn-secondary hover:bg-muted text-foreground"
                  @click="openAuthModal('login')"
                >
                  Login
                </button>

                <button
                  type="button"
                  class="inline-flex flex-1 items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition-colors
                         bg-indigo-600 hover:bg-indigo-700 text-white
                         btn-primary"
                  @click="openAuthModal('register')"
                >
                  Register
                </button>
              </div>
            </template>
          </div>
        </aside>
      </Transition>
    </Teleport>

    <!-- Auth Modal (simple Vue version to match the TSX behavior/structure) -->
    <Teleport to="body">
      <Transition
        enter-active-class="transition-opacity duration-150"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="transition-opacity duration-150"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div v-if="authModalOpen" class="fixed inset-0 z-[60] flex items-center justify-center px-4">
          <div class="absolute inset-0 bg-black/50" @click="closeAuthModal" />

          <div
            class="relative w-full max-w-md rounded-2xl shadow-2xl border
                   bg-white border-gray-200
                   bg-card border-border"
          >
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-200 border-border">
              <div class="flex items-center gap-2">
                <button
                  type="button"
                  class="text-sm font-semibold px-3 py-1.5 rounded-lg transition-colors"
                  :class="authMode === 'login'
                    ? 'bg-gray-100 text-indigo-600 bg-muted text-primary'
                    : 'text-gray-700 text-foreground hover:bg-gray-100 hover:bg-muted'"
                  @click="authMode = 'login'"
                >
                  Login
                </button>
                <button
                  type="button"
                  class="text-sm font-semibold px-3 py-1.5 rounded-lg transition-colors"
                  :class="authMode === 'register'
                    ? 'bg-gray-100 text-indigo-600 bg-muted text-primary'
                    : 'text-gray-700 text-foreground hover:bg-gray-100 hover:bg-muted'"
                  @click="authMode = 'register'"
                >
                  Register
                </button>
              </div>

              <button
                type="button"
                class="p-2 rounded-lg transition-colors hover:bg-gray-100 hover:bg-muted"
                aria-label="Close"
                @click="closeAuthModal"
              >
                <svg viewBox="0 0 24 24" class="w-5 h-5 text-gray-900 text-foreground" aria-hidden="true">
                  <path
                    fill="currentColor"
                    d="M18.3 5.71 12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.29 19.71 2.88 18.29 9.17 12 2.88 5.71 4.29 4.29l6.3 6.3 6.3-6.3 1.41 1.42Z"
                  />
                </svg>
              </button>
            </div>

            <div class="px-5 py-5 space-y-3">
              <p class="text-sm text-gray-600 text-muted-foreground">
                Continue to the {{ authMode === 'login' ? 'Login' : 'Register' }} page.
              </p>

              <Link
                v-if="authMode === 'login'"
                :href="loginHref"
                class="inline-flex w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition-colors
                       bg-indigo-600 hover:bg-indigo-700 text-white
                       btn-primary"
                @click="closeAuthModal"
              >
                Go to Login
              </Link>

              <Link
                v-else
                :href="registerHref"
                class="inline-flex w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition-colors
                       bg-indigo-600 hover:bg-indigo-700 text-white
                       btn-primary"
                @click="closeAuthModal"
              >
                Go to Register
              </Link>

              <button
                type="button"
                class="inline-flex w-full items-center justify-center rounded-xl px-4 py-3 text-sm font-semibold transition-colors
                       bg-gray-100 hover:bg-gray-200 text-gray-900
                       btn-secondary hover:bg-muted text-foreground"
                @click="closeAuthModal"
              >
                Cancel
              </button>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>

</template>

<style scoped>
/* If your project doesn’t define container-custom, this keeps layout identical */
.container-custom {
  width: 100%;
}
</style>
