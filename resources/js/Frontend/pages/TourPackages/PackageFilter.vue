<script setup lang="ts">
import { computed } from 'vue'

type DestinationGroup = { id: string; name: string }

const props = defineProps<{
  destinationGroups: DestinationGroup[]
  hasActiveFilters: boolean
}>()

const isOpen = defineModel<boolean>('isOpen', { default: false })

const filters = defineModel<{
  destination: string
  days: string
  passengers: string
  vehicleType: string
}>('filters', {
  default: () => ({
    destination: '',
    days: '',
    passengers: '',
    vehicleType: '',
  }),
})

const emit = defineEmits<{
  (e: 'clear'): void
}>()

const open = () => (isOpen.value = true)
const close = () => (isOpen.value = false)

const clear = () => emit('clear')

const showBadge = computed(() => props.hasActiveFilters)

// ✅ Input styles to match your screenshot exactly
const inputClass =
  'w-full rounded-xl border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-900 ' +
  'placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-300'
</script>

<template>
  <div class="w-full lg:w-auto">
    <!-- Mobile Filter Button (same as TSX) -->
    <button
      type="button"
      @click="open"
      class="lg:hidden flex items-center gap-2 px-4 py-3 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors"
    >
      <svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true">
        <path fill="currentColor" d="M3 4h18l-7 8v6l-4 2v-8L3 4z" />
      </svg>
      Filters
      <span
        v-if="showBadge"
        class="w-5 h-5 rounded-full bg-gradient-to-r from-indigo-600 to-purple-600 text-xs text-white flex items-center justify-center"
      >
        !
      </span>
    </button>

    <!-- Desktop Sidebar -->
    <aside class="hidden lg:block w-72 flex-shrink-0">
      <!-- ✅ Sticky exactly like TSX -->
      <div class="sticky top-24">
        <!-- ✅ Card look like screenshot -->
        <div class="rounded-3xl bg-white shadow-xl p-8">
          <div class="flex items-center justify-between mb-8">
            <h3 class="text-base font-semibold text-gray-900">Filters</h3>

            <button
              v-if="hasActiveFilters"
              type="button"
              class="text-sm font-medium text-indigo-600 hover:underline"
              @click="clear"
            >
              Clear All
            </button>
          </div>

          <div class="space-y-7">
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-3">Destination</label>
              <select v-model="filters.destination" :class="inputClass">
                <option value="">All Destinations</option>
                <option v-for="d in destinationGroups" :key="d.id" :value="d.id">
                  {{ d.name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-3">Duration</label>
              <select v-model="filters.days" :class="inputClass">
                <option value="">Any Duration</option>
                <option value="1-3">1-3 Days</option>
                <option value="4-7">4-7 Days</option>
                <option value="8-14">8-14 Days</option>
                <option value="15">15+ Days</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-3">Passengers</label>
              <input
                v-model="filters.passengers"
                type="number"
                min="1"
                max="20"
                placeholder="Number of travelers"
                :class="inputClass"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-3">Vehicle Type</label>
              <select v-model="filters.vehicleType" :class="inputClass">
                <option value="">Any Vehicle</option>
                <option value="sedan">Sedan</option>
                <option value="van">Van</option>
                <option value="luxury">Luxury</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </aside>

    <!-- Mobile Drawer -->
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
          v-if="isOpen"
          class="fixed inset-0 bg-black/50 z-50 lg:hidden"
          @click="close"
        />
      </Transition>

      <Transition
        enter-active-class="transition-transform duration-200 ease-out"
        enter-from-class="-translate-x-full"
        enter-to-class="translate-x-0"
        leave-active-class="transition-transform duration-200 ease-in"
        leave-from-class="translate-x-0"
        leave-to-class="-translate-x-full"
      >
        <div
          v-if="isOpen"
          class="fixed top-0 left-0 bottom-0 w-80 bg-white z-50 lg:hidden shadow-2xl overflow-y-auto"
          @click.stop
        >
          <div class="p-4 border-b border-gray-200 flex items-center justify-between">
            <h3 class="font-semibold text-lg text-gray-900">Filters</h3>
            <button type="button" class="p-2 hover:bg-gray-100 rounded-lg" @click="close" aria-label="Close">
              <svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true">
                <path
                  fill="currentColor"
                  d="M18.3 5.71 12 12l6.3 6.29-1.41 1.42L10.59 13.4 4.29 19.71 2.88 18.29 9.17 12 2.88 5.71 4.29 4.29l6.3 6.3 6.3-6.3 1.41 1.42Z"
                />
              </svg>
            </button>
          </div>

          <div class="p-6 space-y-7">
            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-3">Destination</label>
              <select v-model="filters.destination" :class="inputClass">
                <option value="">All Destinations</option>
                <option v-for="d in destinationGroups" :key="d.id" :value="d.id">
                  {{ d.name }}
                </option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-3">Duration</label>
              <select v-model="filters.days" :class="inputClass">
                <option value="">Any Duration</option>
                <option value="1-3">1-3 Days</option>
                <option value="4-7">4-7 Days</option>
                <option value="8-14">8-14 Days</option>
                <option value="15">15+ Days</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-3">Passengers</label>
              <input
                v-model="filters.passengers"
                type="number"
                min="1"
                max="20"
                placeholder="Number of travelers"
                :class="inputClass"
              />
            </div>

            <div>
              <label class="block text-sm font-semibold text-gray-900 mb-3">Vehicle Type</label>
              <select v-model="filters.vehicleType" :class="inputClass">
                <option value="">Any Vehicle</option>
                <option value="sedan">Sedan</option>
                <option value="van">Van</option>
                <option value="luxury">Luxury</option>
              </select>
            </div>
          </div>

          <div class="p-4 border-t border-gray-200 flex gap-3">
            <button type="button" class="btn-secondary flex-1" @click="clear">
              Clear
            </button>
            <button type="button" class="btn-primary flex-1" @click="close">
              Apply
            </button>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>
