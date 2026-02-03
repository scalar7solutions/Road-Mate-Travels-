<!-- HomeQuickSearch.vue -->
<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { reactive } from 'vue'

const filters = reactive({
  destination: '',
  duration: '',
  passengers: '',
  vehicleType: '',
})
</script>

<template>
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 container-custom">
    <div
      class="bg-white rounded-2xl shadow-xl p-6 lg:p-8 border border-gray-100"
      style="animation: fadeUp 0.6s ease-out 0.2s both"
    >
      <h2 class="text-lg font-semibold text-gray-900 mb-6">Find Your Perfect Tour</h2>

      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Destination -->
        <div>
          <label class="block text-sm text-gray-500 mb-2">Destination</label>
          <select v-model="filters.destination" class="input-field">
            <option value="">All Destinations</option>
            <option value="cultural-triangle">Cultural Triangle</option>
            <option value="hill-country">Hill Country</option>
            <option value="southern-coast">Southern Coast</option>
            <option value="wildlife-safari">Wildlife & Safari</option>
          </select>
        </div>

        <!-- Duration -->
        <div>
          <label class="block text-sm text-gray-500 mb-2">Duration</label>
          <select v-model="filters.duration" class="input-field">
            <option value="">Any Duration</option>
            <option value="1-3">1-3 Days</option>
            <option value="4-7">4-7 Days</option>
            <option value="8+">8+ Days</option>
          </select>
        </div>

        <!-- Passengers -->
        <div>
          <label class="block text-sm text-gray-500 mb-2">Passengers</label>
          <div class="relative">
            <!-- users icon -->
            <svg
              viewBox="0 0 24 24"
              class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
              aria-hidden="true"
            >
              <path
                fill="currentColor"
                d="M16 11c1.66 0 3-1.34 3-3S17.66 5 16 5s-3 1.34-3 3 1.34 3 3 3ZM8 11c1.66 0 3-1.34 3-3S9.66 5 8 5 5 6.34 5 8s1.34 3 3 3Zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5C15 14.17 10.33 13 8 13Zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.92 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5Z"
              />
            </svg>

            <input
              v-model="filters.passengers"
              type="number"
              min="1"
              max="20"
              placeholder="How many?"
              class="input-field pl-10"
            />
          </div>
        </div>

        <!-- Vehicle Type -->
        <div>
          <label class="block text-sm text-gray-500 mb-2">Vehicle Type</label>
          <select v-model="filters.vehicleType" class="input-field">
            <option value="">Any Vehicle</option>
            <option value="sedan">Sedan</option>
            <option value="van">Van</option>
            <option value="luxury">Luxury</option>
          </select>
        </div>

        <!-- Search -->
        <div class="flex items-end">
          <Link
            href="/packages"
            class="w-full inline-flex items-center justify-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold
                   bg-indigo-600 hover:bg-indigo-700 text-white transition-colors"
          >
            <!-- search icon -->
            <svg viewBox="0 0 24 24" class="w-5 h-5" aria-hidden="true">
              <path
                fill="currentColor"
                d="M10 2a8 8 0 1 1 5.293 14.01l4.349 4.348-1.414 1.415-4.349-4.35A8 8 0 0 1 10 2Zm0 2a6 6 0 1 0 .001 12.001A6 6 0 0 0 10 4Z"
              />
            </svg>
            Search
          </Link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes fadeUp {
  0% {
    opacity: 0;
    transform: translateY(20px);
  }
  100% {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Same “input-field” feel as TSX */
.input-field {
  width: 100%;
  border-radius: 0.75rem; /* rounded-xl */
  border: 1px solid rgb(229 231 235); /* gray-200 */
  background: white;
  padding: 0.75rem 1rem; /* px-4 py-3 */
  font-size: 0.875rem; /* text-sm */
  color: rgb(17 24 39); /* gray-900 */
  outline: none;
  transition: box-shadow 0.15s ease, border-color 0.15s ease;
}
.input-field:focus {
  border-color: rgb(99 102 241); /* indigo-500 */
  box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.18);
}
</style>
