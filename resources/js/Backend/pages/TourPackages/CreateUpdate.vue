<script lang="ts">
import AppLayout from '@/Backend/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineComponent } from 'vue';
import { route } from 'ziggy-js';

type TierRow = { min: number; max: number; price: number };
type PriceOption = {
    title: string;
    mode: 'fixed_total' | 'per_day' | 'per_person' | 'tiered';
    status: 'active' | 'inactive';
    values: any;
};
type RangeRow = { start_date: string; end_date: string };

export default defineComponent({
    components: { AppLayout, Head, Link },
    props: {
        mode: { type: String, required: true },
        pkg: { type: Object, default: null },
        destinationGroups: { type: Array, required: true },
        places: { type: Array, required: true }, // includes destination_group_id
        vehicleTypes: { type: Array, required: true },
    },
    data() {
        const p: any = this.pkg || null;

        return {
            form: useForm({
                name: p?.name ?? '',
                destination_group_id: p?.destination_group_id ?? '',
                days: p?.days ?? 1,
                passenger_min: p?.passenger_min ?? 1,
                passenger_max: p?.passenger_max ?? 1,
                status: p?.status ?? 'draft',

                place_ids: (p?.place_ids ?? []) as number[],
                vehicle_type_ids: (p?.vehicle_type_ids ?? []) as number[],

                availability_ranges: (p?.availability_ranges?.length
                    ? p.availability_ranges
                    : [{ start_date: '', end_date: '' }]) as RangeRow[],

                limited_km_enabled: !!p?.limited_km_enabled,
                included_km_basis: p?.included_km_basis ?? 'total',
                included_km: p?.included_km ?? '',
                extra_km_price: p?.extra_km_price ?? '',

                short_description: p?.short_description ?? '',
                description: p?.description ?? '',

                // files
                main_image: null as File | null,
                gallery_images: [] as File[],

                // existing images (for UI only)
                existing_main_image_path: p?.main_image_path ?? '',
                existing_gallery_images: (p?.gallery_images ?? []) as string[],

                price_options: (p?.price_options?.length
                    ? p.price_options
                    : [
                          {
                              title: 'Standard',
                              mode: 'fixed_total',
                              status: 'active',
                              values: { price: 0 },
                          },
                      ]) as PriceOption[],
            }),
        };
    },
    computed: {
        isEdit(): boolean {
            return this.mode === 'edit' && !!(this.pkg as any)?.id;
        },
        filteredPlaces(): any[] {
            const gid = (this.form as any).destination_group_id;
            if (!gid) return [];
            return (this.places as any[]).filter(
                (p) => String(p.destination_group_id) === String(gid),
            );
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
    watch: {
        'form.destination_group_id': function () {
            // remove selected places that are not in the selected group
            const valid = new Set(this.filteredPlaces.map((p: any) => p.id));
            (this.form as any).place_ids = (
                (this.form as any).place_ids || []
            ).filter((id: number) => valid.has(id));
        },
    },
    methods: {
        addRange() {
            (this.form as any).availability_ranges.push({
                start_date: '',
                end_date: '',
            });
        },
        removeRange(i: number) {
            const list = (this.form as any).availability_ranges;
            if (list.length <= 1) return;
            list.splice(i, 1);
        },

        addPriceOption() {
            (this.form as any).price_options.push({
                title: 'Option',
                mode: 'fixed_total',
                status: 'active',
                values: { price: 0 },
            });
        },
        removePriceOption(i: number) {
            const list = (this.form as any).price_options;
            if (list.length <= 1) return;
            list.splice(i, 1);
        },
        onModeChange(opt: any) {
            if (opt.mode === 'fixed_total') opt.values = { price: 0 };
            if (opt.mode === 'per_day') opt.values = { price_per_day: 0 };
            if (opt.mode === 'per_person') opt.values = { price_per_person: 0 };
            if (opt.mode === 'tiered')
                opt.values = {
                    tiers: [{ min: 1, max: 2, price: 0 } as TierRow],
                };
        },
        addTier(opt: any) {
            if (!opt.values?.tiers) opt.values.tiers = [];
            opt.values.tiers.push({ min: 1, max: 1, price: 0 });
        },
        removeTier(opt: any, idx: number) {
            if (!opt.values?.tiers) return;
            if (opt.values.tiers.length <= 1) return;
            opt.values.tiers.splice(idx, 1);
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
    (this.form as any).post(route('tourpackages.store'), {
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
    .post(route('tourpackages.update', id), {
      preserveScroll: true,
      forceFormData: true,
    })
}

    },
});
</script>

<template>
    <AppLayout>
        <Head :title="isEdit ? 'Update Tour Package' : 'Create Tour Package'" />

        <div class="space-y-6 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">
                        {{
                            isEdit
                                ? 'Update Tour Package'
                                : 'Create Tour Package'
                        }}
                    </h1>
                    <p class="text-sm text-neutral-500">
                        Use a single pricing mode per option (radio logic).
                    </p>
                </div>
                <Link
                    :href="route('tourpackages.index')"
                    class="rounded-full border px-4 py-2 text-sm hover:bg-neutral-50"
                    >Back</Link
                >
            </div>

            <form
                @submit.prevent="submit"
                class="space-y-8 rounded-2xl border bg-white p-6 shadow-sm"
            >
                <!-- Basic -->
                <section class="space-y-4">
                    <h2 class="text-lg font-semibold">Basic Info</h2>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="md:col-span-2">
                            <label class="text-sm font-medium"
                                >Package Name</label
                            >
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
                            <label class="text-sm font-medium"
                                >Destination Group</label
                            >
                            <select
                                v-model="(form as any).destination_group_id"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                            >
                                <option value="" disabled>Select group</option>
                                <option
                                    v-for="g in destinationGroups"
                                    :key="(g as any).id"
                                    :value="(g as any).id"
                                >
                                    {{ (g as any).name }}
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
                            <label class="text-sm font-medium">Days</label>
                            <input
                                type="number"
                                min="1"
                                v-model.number="(form as any).days"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                            />
                        </div>

                        <div class="grid grid-cols-2 gap-2">
                            <div>
                                <label class="text-sm font-medium"
                                    >Passenger Min</label
                                >
                                <input
                                    type="number"
                                    min="1"
                                    v-model.number="(form as any).passenger_min"
                                    class="mt-1 w-full rounded-xl border px-4 py-2"
                                />
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
                    </div>
                </section>

                <!-- Places -->
                <section class="space-y-4">
                    <h2 class="text-lg font-semibold">
                        Places (Filtered by Group)
                    </h2>

                    <div class="rounded-xl border p-4">
                        <p
                            v-if="!filteredPlaces.length"
                            class="text-sm text-neutral-500"
                        >
                            Select a destination group to see places.
                        </p>

                        <div
                            v-else
                            class="grid grid-cols-1 gap-2 md:grid-cols-3"
                        >
                            <label
                                v-for="pl in filteredPlaces"
                                :key="pl.id"
                                class="flex items-center gap-2 rounded-lg border px-3 py-2"
                            >
                                <input
                                    type="checkbox"
                                    :value="pl.id"
                                    v-model="(form as any).place_ids"
                                />
                                <span class="text-sm">{{ pl.name }}</span>
                            </label>
                        </div>

                        <p
                            v-if="(form as any).errors.place_ids"
                            class="mt-2 text-sm text-red-600"
                        >
                            {{ (form as any).errors.place_ids }}
                        </p>
                    </div>
                </section>

                <!-- Vehicle Types -->
                <section class="space-y-4">
                    <h2 class="text-lg font-semibold">Allowed Vehicle Types</h2>
                    <div class="grid grid-cols-1 gap-2 md:grid-cols-3">
                        <label
                            v-for="v in vehicleTypes"
                            :key="(v as any).id"
                            class="flex items-center gap-2 rounded-lg border px-3 py-2"
                        >
                            <input
                                type="checkbox"
                                :value="(v as any).id"
                                v-model="(form as any).vehicle_type_ids"
                            />
                            <span class="text-sm">{{ (v as any).name }}</span>
                        </label>
                    </div>
                    <p
                        v-if="(form as any).errors.vehicle_type_ids"
                        class="text-sm text-red-600"
                    >
                        {{ (form as any).errors.vehicle_type_ids }}
                    </p>
                </section>

                <!-- Availability -->
                <section class="space-y-4">
                    <h2 class="text-lg font-semibold">Availability Ranges</h2>

                    <div class="space-y-3">
                        <div
                            v-for="(r, i) in (form as any).availability_ranges"
                            :key="i"
                            class="grid grid-cols-1 items-end gap-2 md:grid-cols-3"
                        >
                            <div>
                                <label class="text-sm font-medium"
                                    >Start Date</label
                                >
                                <input
                                    type="date"
                                    v-model="r.start_date"
                                    class="mt-1 w-full rounded-xl border px-4 py-2"
                                />
                            </div>
                            <div>
                                <label class="text-sm font-medium"
                                    >End Date</label
                                >
                                <input
                                    type="date"
                                    v-model="r.end_date"
                                    class="mt-1 w-full rounded-xl border px-4 py-2"
                                />
                            </div>
                            <div class="flex gap-2">
                                <button
                                    type="button"
                                    @click="addRange"
                                    class="rounded-xl border px-3 py-2 text-sm hover:bg-neutral-50"
                                >
                                    + Add
                                </button>
                                <button
                                    type="button"
                                    @click="removeRange(i)"
                                    class="rounded-xl border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Trip Rules -->
                <section class="space-y-4">
                    <h2 class="text-lg font-semibold">Trip Rules</h2>

                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            v-model="(form as any).limited_km_enabled"
                        />
                        <span class="text-sm font-medium"
                            >Enable Limited KM</span
                        >
                    </label>

                    <div
                        v-if="(form as any).limited_km_enabled"
                        class="grid grid-cols-1 gap-4 md:grid-cols-3"
                    >
                        <div>
                            <label class="text-sm font-medium"
                                >Included KM Basis</label
                            >
                            <select
                                v-model="(form as any).included_km_basis"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                            >
                                <option value="total">total</option>
                                <option value="per_day">per_day</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-sm font-medium"
                                >Included KM</label
                            >
                            <input
                                type="number"
                                min="1"
                                v-model.number="(form as any).included_km"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                            />
                        </div>
                        <div>
                            <label class="text-sm font-medium"
                                >Extra KM Price</label
                            >
                            <input
                                type="number"
                                min="0"
                                step="0.01"
                                v-model.number="(form as any).extra_km_price"
                                class="mt-1 w-full rounded-xl border px-4 py-2"
                            />
                        </div>
                    </div>
                </section>

                <!-- Content -->
                <section class="space-y-4">
                    <h2 class="text-lg font-semibold">Content</h2>

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

                <!-- Media -->
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

                <!-- Pricing -->
                <section class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold">Pricing Options</h2>
                        <button
                            type="button"
                            @click="addPriceOption"
                            class="rounded-xl border px-3 py-2 text-sm hover:bg-neutral-50"
                        >
                            + Add Pricing Option
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div
                            v-for="(opt, i) in (form as any).price_options"
                            :key="i"
                            class="space-y-3 rounded-xl border p-4"
                        >
                            <div
                                class="flex items-center justify-between gap-2"
                            >
                                <div class="flex-1">
                                    <label class="text-sm font-medium"
                                        >Option Title</label
                                    >
                                    <input
                                        v-model="opt.title"
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                        placeholder="Standard / Premium"
                                    />
                                </div>
                                <button
                                    type="button"
                                    @click="removePriceOption(i)"
                                    class="rounded-xl border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50"
                                >
                                    Remove
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-3 md:grid-cols-3">
                                <div>
                                    <label class="text-sm font-medium"
                                        >Mode</label
                                    >
                                    <select
                                        v-model="opt.mode"
                                        @change="onModeChange(opt)"
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    >
                                        <option value="fixed_total">
                                            fixed_total
                                        </option>
                                        <option value="per_day">per_day</option>
                                        <option value="per_person">
                                            per_person
                                        </option>
                                        <option value="tiered">tiered</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="text-sm font-medium"
                                        >Status</label
                                    >
                                    <select
                                        v-model="opt.status"
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    >
                                        <option value="active">active</option>
                                        <option value="inactive">
                                            inactive
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Mode fields -->
                            <div
                                v-if="opt.mode === 'fixed_total'"
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
                                        v-model.number="opt.values.price"
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    />
                                </div>
                            </div>

                            <div
                                v-if="opt.mode === 'per_day'"
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
                                            opt.values.price_per_day
                                        "
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    />
                                </div>
                            </div>

                            <div
                                v-if="opt.mode === 'per_person'"
                                class="grid grid-cols-1 gap-3 md:grid-cols-2"
                            >
                                <div>
                                    <label class="text-sm font-medium"
                                        >Price Per Person</label
                                    >
                                    <input
                                        type="number"
                                        min="0"
                                        step="0.01"
                                        v-model.number="
                                            opt.values.price_per_person
                                        "
                                        class="mt-1 w-full rounded-xl border px-4 py-2"
                                    />
                                </div>
                            </div>

                            <div v-if="opt.mode === 'tiered'" class="space-y-2">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium">
                                        Passenger Tiers
                                    </p>
                                    <button
                                        type="button"
                                        @click="addTier(opt)"
                                        class="rounded-xl border px-3 py-1.5 text-sm hover:bg-neutral-50"
                                    >
                                        + Add Tier
                                    </button>
                                </div>

                                <div class="space-y-2">
                                    <div
                                        v-for="(t, ti) in opt.values.tiers"
                                        :key="ti"
                                        class="grid grid-cols-1 items-end gap-2 md:grid-cols-4"
                                    >
                                        <div>
                                            <label
                                                class="text-xs text-neutral-600"
                                                >Min</label
                                            >
                                            <input
                                                type="number"
                                                min="1"
                                                v-model.number="t.min"
                                                class="mt-1 w-full rounded-xl border px-3 py-2"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="text-xs text-neutral-600"
                                                >Max</label
                                            >
                                            <input
                                                type="number"
                                                min="1"
                                                v-model.number="t.max"
                                                class="mt-1 w-full rounded-xl border px-3 py-2"
                                            />
                                        </div>
                                        <div>
                                            <label
                                                class="text-xs text-neutral-600"
                                                >Price</label
                                            >
                                            <input
                                                type="number"
                                                min="0"
                                                step="0.01"
                                                v-model.number="t.price"
                                                class="mt-1 w-full rounded-xl border px-3 py-2"
                                            />
                                        </div>
                                        <div>
                                            <button
                                                type="button"
                                                @click="removeTier(opt, ti)"
                                                class="w-full rounded-xl border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50"
                                            >
                                                Remove
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <p class="text-xs text-neutral-500">
                                Only one mode is active per option. This avoids
                                conflicting checkbox logic.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Submit -->
                <div class="flex justify-end gap-2">
                    <Link
                        :href="route('tourpackages.index')"
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
