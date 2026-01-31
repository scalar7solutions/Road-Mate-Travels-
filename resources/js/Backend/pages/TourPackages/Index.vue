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
    return { q: (this.filters as any).q || '' }
  },
  methods: {
    search() {
      router.get(route('tourpackages.index'), { q: this.q }, { preserveState: true, replace: true })
    },
    destroyItem(id: number) {
      if (!confirm('Delete this tour package?')) return
      router.delete(route('tourpackages.destroy', id), { preserveScroll: true })
    },
  },
})
</script>

<template>
  <AppLayout>
    <Head title="Tour Packages" />

    <div class="p-6 space-y-4">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold">Tour Packages</h1>
          <p class="text-sm text-neutral-500">Create and manage tour packages with availability and pricing options.</p>
        </div>

        <Link
          :href="route('tourpackages.create')"
          class="inline-flex items-center justify-center rounded-full border border-red-500 px-4 py-2 text-sm font-medium text-red-500 hover:bg-red-500 hover:text-white transition"
        >
          + New Tour Package
        </Link>
      </div>

      <div class="flex gap-2">
        <input
          v-model="q"
          @keyup.enter="search"
          class="w-full rounded-xl border border-neutral-200 px-4 py-2 outline-none focus:border-red-500"
          placeholder="Search by package name..."
        />
        <button type="button" @click="search" class="rounded-xl bg-neutral-900 px-4 py-2 text-sm font-medium text-white">
          Search
        </button>
      </div>

      <div class="rounded-2xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr>
              <th class="p-3 text-left">Name</th>
              <th class="p-3 text-left">Group</th>
              <th class="p-3 text-left">Days</th>
              <th class="p-3 text-left">Status</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="r in (items as any).data" :key="r.id" class="border-t">
              <td class="p-3 font-medium">{{ r.name }}</td>
              <td class="p-3 text-neutral-700">{{ r.destination_group?.name }}</td>
              <td class="p-3">{{ r.days }}</td>
              <td class="p-3">
                <span class="inline-flex rounded-full px-3 py-1 text-xs font-medium"
                  :class="r.status === 'published' ? 'bg-green-100 text-green-700' : (r.status === 'draft' ? 'bg-neutral-200 text-neutral-700' : 'bg-yellow-100 text-yellow-700')"
                >
                  {{ r.status }}
                </span>
              </td>
              <td class="p-3 text-right">
                <Link :href="route('tourpackages.edit', r.id)" class="rounded-full border px-3 py-1.5 text-xs font-medium hover:bg-neutral-50">
                  Edit
                </Link>
                <button
                  type="button"
                  @click="destroyItem(r.id)"
                  class="ml-2 rounded-full border border-red-200 px-3 py-1.5 text-xs font-medium text-red-600 hover:bg-red-50"
                >
                  Delete
                </button>
              </td>
            </tr>
            <tr v-if="(items as any).data.length === 0">
              <td colspan="5" class="p-6 text-center text-neutral-500">No results.</td>
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
