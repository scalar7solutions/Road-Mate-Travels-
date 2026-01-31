<script lang="ts">
import { defineComponent } from 'vue'
import AppLayout from '@/Backend/layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

export default defineComponent({
  components: { AppLayout, Head, Link },
  props: {
    items: { type: Object, required: true },
    groups: { type: Array, required: true },
    filters: { type: Object, required: true },
  },
  data() {
    return {
      q: (this.filters as any).q || '',
      group_id: (this.filters as any).group_id || '',
    }
  },
  methods: {
    search() {
      router.get(
        route('places.index'),
        { q: this.q, group_id: this.group_id || undefined },
        { preserveState: true, replace: true }
      )
    },
    destroyItem(id: number) {
      if (!confirm('Delete this place?')) return
      router.delete(route('places.destroy', id), { preserveScroll: true })
    },
    imgUrl(path: string | null) {
      return path ? `/storage/${path}` : ''
    },
  },
})
</script>

<template>
  <AppLayout>
    <Head title="Places" />

    <div class="p-6 space-y-4">
      <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h1 class="text-2xl font-bold">Places</h1>
          <p class="text-sm text-neutral-500">Places belong to destination groups.</p>
        </div>

        <Link
          :href="route('places.create')"
          class="inline-flex items-center justify-center rounded-full border border-red-500 px-4 py-2 text-sm font-medium text-red-500 hover:bg-red-500 hover:text-white transition"
        >
          + New Place
        </Link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
        <input
          v-model="q"
          @keyup.enter="search"
          class="md:col-span-2 rounded-xl border border-neutral-200 px-4 py-2 outline-none focus:border-red-500"
          placeholder="Search place name..."
        />
        <select v-model="group_id" class="rounded-xl border border-neutral-200 px-4 py-2 outline-none focus:border-red-500">
          <option value="">All Groups</option>
          <option v-for="g in groups" :key="(g as any).id" :value="(g as any).id">
            {{ (g as any).name }}
          </option>
        </select>
      </div>

      <div class="flex gap-2">
        <button
          type="button"
          @click="search"
          class="rounded-xl bg-neutral-900 px-4 py-2 text-sm font-medium text-white"
        >
          Search
        </button>
      </div>

      <div class="rounded-2xl border border-neutral-200 bg-white shadow-sm overflow-hidden">
        <table class="w-full text-sm">
          <thead class="bg-neutral-50 text-neutral-600">
            <tr>
              <th class="p-3 text-left">Image</th>
              <th class="p-3 text-left">Place</th>
              <th class="p-3 text-left">Group</th>
              <th class="p-3 text-left">Status</th>
              <th class="p-3 text-right">Actions</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="r in (items as any).data" :key="r.id" class="border-t">
              <td class="p-3">
                <img v-if="r.image_path" :src="imgUrl(r.image_path)" class="h-10 w-14 rounded-lg object-cover" />
                <div v-else class="h-10 w-14 rounded-lg bg-neutral-200" />
              </td>

              <td class="p-3 font-medium">{{ r.name }}</td>
              <td class="p-3 text-neutral-700">{{ r.group?.name }}</td>

              <td class="p-3">
                <span
                  class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium"
                  :class="r.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-neutral-200 text-neutral-700'"
                >
                  {{ r.status }}
                </span>
              </td>

              <td class="p-3 text-right">
                <Link :href="route('places.edit', r.id)" class="rounded-full border px-3 py-1.5 text-xs font-medium hover:bg-neutral-50">
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
