<script lang="ts">
import AppLayout from '@/Backend/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineComponent } from 'vue';
import { route } from 'ziggy-js';

export default defineComponent({
    components: { AppLayout, Head, Link },
    props: {
        mode: { type: String, required: true },
        vehicleType: { type: Object, default: null },
    },
    data() {
        return {
            form: useForm({
                name: (this.vehicleType as any)?.name ?? '',
                passenger_min: (this.vehicleType as any)?.passenger_min ?? 1,
                passenger_max: (this.vehicleType as any)?.passenger_max ?? 1,
                status: (this.vehicleType as any)?.status ?? 'active',
                image: null as File | null,
            }),
        };
    },
    computed: {
        isEdit(): boolean {
            return this.mode === 'edit' && !!(this.vehicleType as any)?.id;
        },
    },
    methods: {
        onFile(e: Event) {
            const f = (e.target as HTMLInputElement).files?.[0] || null;
            (this.form as any).image = f;
        },
     submit() {
  if (!this.isEdit) {
    this.form.post(route('vehicletypes.store'), {
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
    .post(route('vehicletypes.update', (this.vehicleType as any).id), {
      preserveScroll: true,
      forceFormData: true,
    })
}

    },
});
</script>

<template>
    <AppLayout>
        <Head :title="isEdit ? 'Update Vehicle Type' : 'Create Vehicle Type'" />

        <div class="space-y-4 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">
                        {{
                            isEdit
                                ? 'Update Vehicle Type'
                                : 'Create Vehicle Type'
                        }}
                    </h1>
                    <p class="text-sm text-neutral-500">
                        Vehicle types are used in tours and rentals.
                    </p>
                </div>

                <Link
                    :href="route('vehicletypes.index')"
                    class="rounded-full border px-4 py-2 text-sm hover:bg-neutral-50"
                >
                    Back
                </Link>
            </div>

            <form
                @submit.prevent="submit"
                class="space-y-4 rounded-2xl border bg-white p-6 shadow-sm"
            >
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium">Name</label>
                        <input
                            v-model="(form as any).name"
                            class="mt-1 w-full rounded-xl border px-4 py-2"
                        />
                        <p
                            v-if="(form as any).errors.name"
                            class="mt-1 text-sm text-red-600"
                        >
                            {{ (form as any).errors.name }}
                        </p>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Status</label>
                        <select
                            v-model="(form as any).status"
                            class="mt-1 w-full rounded-xl border px-4 py-2"
                        >
                            <option value="active">active</option>
                            <option value="inactive">inactive</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium">Passenger Min</label>
                        <input
                            type="number"
                            min="1"
                            v-model.number="(form as any).passenger_min"
                            class="mt-1 w-full rounded-xl border px-4 py-2"
                        />
                    </div>

                    <div>
                        <label class="text-sm font-medium">Passenger Max</label>
                        <input
                            type="number"
                            min="1"
                            v-model.number="(form as any).passenger_max"
                            class="mt-1 w-full rounded-xl border px-4 py-2"
                        />
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Image</label>
                        <input
                            type="file"
                            accept="image/*"
                            @change="onFile"
                            class="mt-1 block w-full"
                        />
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <Link
                        :href="route('vehicletypes.index')"
                        class="rounded-full border px-5 py-2 text-sm hover:bg-neutral-50"
                    >
                        Cancel
                    </Link>
                    <button
                        type="submit"
                        :disabled="(form as any).processing"
                        class="rounded-full bg-red-500 px-6 py-2 text-sm font-medium text-white hover:bg-red-600 disabled:opacity-50"
                    >
                        {{
                            (form as any).processing
                                ? 'Saving...'
                                : isEdit
                                  ? 'Update'
                                  : 'Create'
                        }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
