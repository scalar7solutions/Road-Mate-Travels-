<script lang="ts">
import { defineComponent } from 'vue'
import AppLayout from '@/Backend/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

export default defineComponent({
  components: { AppLayout, Head, Link },
  props: {
    items: { type: Object, required: true },
    filters: { type: Object, required: true },
  },
  data() {
    return {
      q: (this.filters as any).q || '',
      status: (this.filters as any).status || '',
    }
  },
  methods: {
    search() {
      router.get(route('bookings.index'), { q: this.q, status: this.status || undefined }, { preserveState: true, replace: true })
    },
  },
})
</script>

<template>
  <AppLayout>
    <Head title="Bookings" />

    <div class="p-6 space-y-4">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">Bookings</h1>
          <p class="text-sm text-neutral-500">View booking requests and manage statuses.</p>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <input v-model="q" @keyup.enter="search" class="md:col-span-2 rounded-xl border px-4 py-2" placeholder="Search ref / name / phone..." />
        <select v-model="status" class="rounded-xl border px-4 py-2">
          <option value="">All Status</option>
          <option value="pending">pending</option>
          <option value="confirmed">confirmed</option>
          <option value="completed">completed</option>
          <option value="cancelled">cancelled</option>
        </select>
      </div>

      <button type="button" @click="search" class="rounded-xl bg-neutral-900 px-4 py-2 text-sm font-medium text-white">
        Search
      </button>

      <div class="rounded-2xl border bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr>
              <th class="p-3 text-left">Ref</th>
              <th class="p-3 text-left">Customer</th>
              <th class="p-3 text-left">Type</th>
              <th class="p-3 text-left">Start</th>
              <th class="p-3 text-left">Total</th>
              <th class="p-3 text-left">Status</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="b in (items as any).data" :key="b.id" class="border-t">
              <td class="p-3 font-medium">{{ b.booking_ref }}</td>
              <td class="p-3">
                <div class="font-medium">{{ b.customer_name }}</div>
                <div class="text-xs text-neutral-500">{{ b.customer_phone }}</div>
              </td>
              <td class="p-3">{{ b.booking_type }}</td>
              <td class="p-3">{{ b.start_date }}</td>
              <td class="p-3">{{ b.total }}</td>
              <td class="p-3">{{ b.status }}</td>
              <td class="p-3 text-right">
                <Link :href="route('bookings.show', b.id)" class="rounded-full border px-3 py-1.5 text-xs font-medium hover:bg-neutral-50">
                  View
                </Link>
              </td>
            </tr>

            <tr v-if="(items as any).data.length === 0">
              <td colspan="7" class="p-6 text-center text-neutral-500">No results.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="flex flex-wrap gap-2">
        <Link
          v-for="l in (items as any).links"
          :key="l.label"
          :href="l.url || ''"
          preserve-scroll
          preserve-state
          class="px-3 py-1.5 rounded-xl border text-sm"
          :class="l.active ? 'bg-neutral-900 text-white border-neutral-900' : 'bg-white text-neutral-700 border-neutral-200'"
          v-html="l.label"
        />
      </div>
    </div>
  </AppLayout>
</template>
