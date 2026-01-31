<script lang="ts">
import { defineComponent } from 'vue'
import AppLayout from '@/Backend/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

export default defineComponent({
  components: { AppLayout, Head, Link },
  props: {
    mode: { type: String, required: true },
    place: { type: Object, default: null },
    groups: { type: Array, required: true },
  },
  data() {
    return {
      form: useForm({
        destination_group_id: (this.place as any)?.destination_group_id ?? '',
        name: (this.place as any)?.name ?? '',
        map_url: (this.place as any)?.map_url ?? '',
        lat: (this.place as any)?.lat ?? '',
        lng: (this.place as any)?.lng ?? '',
        status: (this.place as any)?.status ?? 'active',
        image: null as File | null,
      }),
    }
  },
  computed: {
    isEdit(): boolean {
      return this.mode === 'edit' && !!(this.place as any)?.id
    },
    existingImage(): string {
      const p = (this.place as any)?.image_path
      return p ? `/storage/${p}` : ''
    },
  },
  methods: {
    onFile(e: Event) {
      const f = (e.target as HTMLInputElement).files?.[0] || null
      ;(this.form as any).image = f
    },
  submit() {
  if (!this.isEdit) {
    this.form.post(route('places.store'), {
      preserveScroll: true,
      forceFormData: true,
    })
    return
  }

  this.form
    .transform((data: any) => ({
      ...data,
      _method: 'put',
    }))
    .post(route('places.update', (this.place as any).id), {
      preserveScroll: true,
      forceFormData: true,
    })
}

  },
})
</script>

<template>
  <AppLayout>
    <Head :title="isEdit ? 'Update Place' : 'Create Place'" />

    <div class="p-6 space-y-4">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">{{ isEdit ? 'Update Place' : 'Create Place' }}</h1>
          <p class="text-sm text-neutral-500">A place belongs to a destination group.</p>
        </div>
        <Link :href="route('places.index')" class="rounded-full border px-4 py-2 text-sm hover:bg-neutral-50">
          Back
        </Link>
      </div>

      <form @submit.prevent="submit" class="rounded-2xl border bg-white p-6 shadow-sm space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="text-sm font-medium">Destination Group</label>
            <select v-model="(form as any).destination_group_id" class="mt-1 w-full rounded-xl border px-4 py-2">
              <option value="" disabled>Select group</option>
              <option v-for="g in groups" :key="(g as any).id" :value="(g as any).id">
                {{ (g as any).name }}
              </option>
            </select>
            <p v-if="(form as any).errors.destination_group_id" class="text-sm text-red-600 mt-1">
              {{ (form as any).errors.destination_group_id }}
            </p>
          </div>

          <div class="md:col-span-2">
            <label class="text-sm font-medium">Place Name</label>
            <input v-model="(form as any).name" class="mt-1 w-full rounded-xl border px-4 py-2" placeholder="e.g., Gregory Lake" />
            <p v-if="(form as any).errors.name" class="text-sm text-red-600 mt-1">{{ (form as any).errors.name }}</p>
          </div>

          <div>
            <label class="text-sm font-medium">Status</label>
            <select v-model="(form as any).status" class="mt-1 w-full rounded-xl border px-4 py-2">
              <option value="active">active</option>
              <option value="inactive">inactive</option>
            </select>
          </div>

          <div>
            <label class="text-sm font-medium">Map URL (optional)</label>
            <input v-model="(form as any).map_url" class="mt-1 w-full rounded-xl border px-4 py-2" placeholder="Google Maps link" />
          </div>

          <div>
            <label class="text-sm font-medium">Latitude (optional)</label>
            <input v-model="(form as any).lat" class="mt-1 w-full rounded-xl border px-4 py-2" placeholder="6.9497" />
          </div>

          <div>
            <label class="text-sm font-medium">Longitude (optional)</label>
            <input v-model="(form as any).lng" class="mt-1 w-full rounded-xl border px-4 py-2" placeholder="80.7891" />
          </div>

          <div class="md:col-span-2">
            <label class="text-sm font-medium">Image (optional)</label>
            <input type="file" accept="image/*" @change="onFile" class="mt-1 block w-full" />
            <div v-if="existingImage" class="mt-2">
              <img :src="existingImage" class="h-20 w-32 rounded-lg object-cover border" />
            </div>
          </div>
        </div>

        <div class="flex justify-end gap-2">
          <Link :href="route('places.index')" class="rounded-full border px-5 py-2 text-sm hover:bg-neutral-50">
            Cancel
          </Link>
          <button
            type="submit"
            :disabled="(form as any).processing"
            class="rounded-full bg-red-500 px-6 py-2 text-sm font-medium text-white hover:bg-red-600 disabled:opacity-50"
          >
            {{ (form as any).processing ? 'Saving...' : (isEdit ? 'Update' : 'Create') }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>
