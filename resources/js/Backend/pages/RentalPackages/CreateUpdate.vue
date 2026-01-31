<script lang="ts">
import AppLayout from '@/Backend/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineComponent } from 'vue';
import { route } from 'ziggy-js';

type PriceRow = {
    service_type: 'self_drive' | 'with_driver' | 'all_inclusive';
    pricing_mode: 'fixed_total' | 'per_day';
    status: 'active' | 'inactive';
    values: any;
};

export default defineComponent({
    components: { AppLayout, Head, Link },
    props: {
        mode: { type: String, required: true },
        pkg: { type: Object, default: null },
        vehicleTypes: { type: Array, required: true },
    },
    data() {
        const p: any = this.pkg || null;

        return {
            form: useForm({
                name: p?.name ?? '',
                vehicle_type_id: p?.vehicle_type_id ?? '',
                passenger_max: p?.passenger_max ?? 1,
                status: p?.status ?? 'draft',
                short_description: p?.short_description ?? '',
                description: p?.description ?? '',

                main_image: null as File | null,
                gallery_images: [] as File[],

                existing_main_image_path: p?.main_image_path ?? '',
                existing_gallery_images: (p?.gallery_images ?? []) as string[],

                prices: (p?.prices?.length
                    ? p.prices
                    : [
                          {
                              service_type: 'self_drive',
                              pricing_mode: 'per_day',
                              status: 'active',
                              values: { price_per_day: 0 },
                          },
                      ]) as PriceRow[],
            }),
        };
    },
    computed: {
        isEdit(): boolean {
            return this.mode === 'edit' && !!(this.pkg as any)?.id;
        },
        existingMainImageUrl(): string {
            const p = (this.form as any).existing_main_image_path;
            return p ? `/storage/${p}` : '';
        },
        existingGalleryUrls(): string[] {
            const arr = (this.form as any).existing_gallery_images || [];
            return arr.map((x: string) => `/storage/${x}`);
        },
    },
    methods: {
        addPrice() {
            (this.form as any).prices.push({
                service_type: 'with_driver',
                pricing_mode: 'per_day',
                status: 'active',
                values: { price_per_day: 0 },
            });
        },
        removePrice(i: number) {
            const list = (this.form as any).prices;
            if (list.length <= 1) return;
            list.splice(i, 1);
        },
        onPricingModeChange(row: any) {
            if (row.pricing_mode === 'fixed_total') row.values = { price: 0 };
            if (row.pricing_mode === 'per_day')
                row.values = { price_per_day: 0 };
        },

        onMainImage(e: Event) {
            const f = (e.target as HTMLInputElement).files?.[0] || null;
            (this.form as any).main_image = f;
        },
        onGalleryImages(e: Event) {
            const files = (e.target as HTMLInputElement).files;
            (this.form as any).gallery_images = files ? Array.from(files) : [];
        },

     submit() {
  const id = (this.pkg as any)?.id

  if (!this.isEdit) {
    ;(this.form as any).post(route('rentalpackages.store'), {
      preserveScroll: true,
      forceFormData: true,
    })
    return
  }

  ;(this.form as any)
    .transform((data: any) => ({
      ...data,
      _method: 'put',
    }))
    .post(route('rentalpackages.update', id), {
      preserveScroll: true,
      forceFormData: true,
    })
}

    },
});
</script>

<template>
    <AppLayout>
        <Head
            :title="isEdit ? 'Update Rental Package' : 'Create Rental Package'"
        />

        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">
                        {{
                            isEdit
                                ? 'Update Rental Package'
                                : 'Create Rental Package'
                        }}
                    </h1>
                    <p class="text-sm text-neutral-500">
                        Define service types (self drive / with driver / all
                        inclusive) and pricing.
                    </p>
                </div>
                <Link
                    :href="route('rentalpackages.index')"
                    class="rounded-full border px-4 py-2 text-sm hover:bg-neutral-50"
                    >Back</Link
                >
            </div>

            <form
                @submit.prevent="submit"
                class="space-y-8 rounded-2xl border bg-white p-6 shadow-sm"
            >
                <section class="space-y-4">
                    <h2 class="text-lg font-semibold">Basic Info</h2>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium">Name</label>
                            <input
                                v-model="(form as any).name"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                            />
                        </div>

                        <div>
                            <label class="text-sm font-medium"
                                >Vehicle Type</label
                            >
                            <select
                                v-model="(form as any).vehicle_type_id"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                            >
                                <option value="" disabled>
                                    Select vehicle
                                </option>
                                <option
                                    v-for="v in vehicleTypes"
                                    :key="(v as any).id"
                                    :value="(v as any).id"
                                >
                                    {{ (v as any).name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium">Status</label>
                            <select
                                v-model="(form as any).status"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                            >
                                <option value="draft">draft</option>
                                <option value="published">published</option>
                                <option value="hidden">hidden</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-sm font-medium"
                                >Passenger Max</label
                            >
                            <input
                                type="number"
                                min="1"
                                v-model.number="(form as any).passenger_max"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                            />
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 class="text-lg font-semibold">Descriptions</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="text-sm font-medium"
                                >Short Description</label
                            >
                            <textarea
                                v-model="(form as any).short_description"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                                rows="2"
                            />
                        </div>
                        <div>
                            <label class="text-sm font-medium"
                                >Full Description</label
                            >
                            <textarea
                                v-model="(form as any).description"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                                rows="5"
                            />
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <h2 class="text-lg font-semibold">Media</h2>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium"
                                >Main Image</label
                            >
                            <input
                                type="file"
                                accept="image/*"
                                @change="onMainImage"
                                class="mt-1 block w-full"
                            />
                            <div v-if="existingMainImageUrl" class="mt-2">
                                <img
                                    :src="existingMainImageUrl"
                                    class="h-24 w-40 rounded-lg border object-cover"
                                />
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium"
                                >Gallery Images</label
                            >
                            <input
                                type="file"
                                accept="image/*"
                                multiple
                                @change="onGalleryImages"
                                class="mt-1 block w-full"
                            />
                            <div
                                v-if="existingGalleryUrls.length"
                                class="mt-2 flex flex-wrap gap-2"
                            >
                                <img
                                    v-for="(u, idx) in existingGalleryUrls"
                                    :key="idx"
                                    :src="u"
                                    class="h-16 w-24 rounded-lg border object-cover"
                                />
                            </div>
                        </div>
                    </div>
                </section>

                <section class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold">
                            Service Type Pricing
                        </h2>
                        <button
                            type="button"
                            @click="addPrice"
                            class="rounded-xl border px-3 py-2 text-sm hover:bg-neutral-50"
                        >
                            + Add Price
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="(row, i) in (form as any).prices"
                            :key="i"
                            class="space-y-3 rounded-xl border p-4"
                        >
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-medium">Price Row</p>
                                <button
                                    type="button"
                                    @click="removePrice(i)"
                                    class="rounded-xl border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    Remove
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                                <div>
                                    <label class="text-sm font-medium"
                                        >Service Type</label
                                    >
                                    <select
                                        v-model="row.service_type"
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    >
                                        <option value="self_drive">
                                            self_drive
                                        </option>
                                        <option value="with_driver">
                                            with_driver
                                        </option>
                                        <option value="all_inclusive">
                                            all_inclusive
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm font-medium"
                                        >Pricing Mode</label
                                    >
                                    <select
                                        v-model="row.pricing_mode"
                                        @change="onPricingModeChange(row)"
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    >
                                        <option value="per_day">per_day</option>
                                        <option value="fixed_total">
                                            fixed_total
                                        </option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm font-medium"
                                        >Status</label
                                    >
                                    <select
                                        v-model="row.status"
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    >
                                        <option value="active">active</option>
                                        <option value="inactive">
                                            inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div
                                v-if="row.pricing_mode === 'per_day'"
                                class="grid grid-cols-1 gap-3 md:grid-cols-2"
                            >
                                <div>
                                    <label class="text-sm font-medium"
                                        >Price Per Day</label
                                    >
                                    <input
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        v-model.number="
                                            row.values.price_per_day
                                        "
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    />
                                </div>
                            </div>

                            <div
                                v-if="row.pricing_mode === 'fixed_total'"
                                class="grid grid-cols-1 gap-3 md:grid-cols-2"
                            >
                                <div>
                                    <label class="text-sm font-medium"
                                        >Fixed Total Price</label
                                    >
                                    <input
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        v-model.number="row.values.price"
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="flex justify-end gap-2">
                    <Link
                        :href="route('rentalpackages.index')"
                        class="rounded-full border px-5 py-2 text-sm hover:bg-neutral-50"
                        >Cancel</Link
                    >
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
