<script lang="ts">
import { defineComponent } from 'vue'
import AppLayout from '@/Backend/layouts/AppLayout.vue'
import { Head, Link, useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

export default defineComponent({
  components: { AppLayout, Head, Link },
  props: {
    mode: { type: String, required: true },
    group: { type: Object, default: null },
  },
  data() {
    return {
      form: useForm({
        name: (this.group as any)?.name ?? '',
        status: (this.group as any)?.status ?? 'active',
        sort_order: (this.group as any)?.sort_order ?? 0,
      }),
    }
  },
  computed: {
    isEdit(): boolean {
      return this.mode === 'edit' && !!(this.group as any)?.id
    },
  },
  methods: {
    submit() {
      if (!this.isEdit) {
        ;(this.form as any).post(route('destinationgroups.store'), { preserveScroll: true })
        return
      }

      ;(this.form as any).put(route('destinationgroups.update', (this.group as any).id), { preserveScroll: true })
    },
  },
})
</script>

<template>
  <AppLayout>
    <Head :title="isEdit ? 'Update Destination Group' : 'Create Destination Group'" />

    <div class="p-6 space-y-4">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold">{{ isEdit ? 'Update Destination Group' : 'Create Destination Group' }}</h1>
          <p class="text-sm text-neutral-500">Groups are used to organize places and packages.</p>
        </div>
        <Link :href="route('destinationgroups.index')" class="rounded-full border px-4 py-2 text-sm hover:bg-neutral-50">
          Back
        </Link>
      </div>

      <form @submit.prevent="submit" class="rounded-2xl border bg-white p-6 shadow-sm space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="text-sm font-medium">Group Name</label>
            <input v-model="(form as any).name" class="mt-1 w-full rounded-xl border px-4 py-2" placeholder="e.g., Down South" />
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
            <label class="text-sm font-medium">Sort Order</label>
            <input type="number" min="0" v-model.number="(form as any).sort_order" class="mt-1 w-full rounded-xl border px-4 py-2" />
          </div>
        </div>

        <div class="flex justify-end gap-2">
          <Link :href="route('destinationgroups.index')" class="rounded-full border px-5 py-2 text-sm hover:bg-neutral-50">
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
