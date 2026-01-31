<script lang="ts">
import AppLayout from '@/Backend/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { defineComponent } from 'vue';
import { route } from 'ziggy-js';

export default defineComponent({
    components: { AppLayout, Head, Link },
    props: {
        booking: { type: Object, required: true },
    },
    data() {
        const b: any = this.booking;
        return {
            form: useForm({
                status: b.status,
                payment_status: b.payment_status,
                admin_note: b.admin_note || '',
            }),
        };
    },
    methods: {
        submit() {
            (this.form as any).put(
                route('bookings.updateStatus', (this.booking as any).id),
                { preserveScroll: true },
            );
        },
    },
});
</script>

<template>
    <AppLayout>
        <Head title="Booking Detail" />

        <div class="space-y-4 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Booking Detail</h1>
                    <p class="text-sm text-neutral-500">
                        Ref: {{ (booking as any).booking_ref }}
                    </p>
                </div>

                <Link
                    :href="route('bookings.index')"
                    class="rounded-full border px-4 py-2 text-sm hover:bg-neutral-50"
                    >Back</Link
                >
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div
                    class="space-y-2 rounded-2xl border bg-white p-5 shadow-sm"
                >
                    <h2 class="font-semibold">Customer</h2>
                    <div class="text-sm">
                        <span class="text-neutral-500">Name:</span>
                        {{ (booking as any).customer_name }}
                    </div>
                    <div class="text-sm">
                        <span class="text-neutral-500">Phone:</span>
                        {{ (booking as any).customer_phone }}
                    </div>
                    <div class="text-sm" v-if="(booking as any).customer_email">
                        <span class="text-neutral-500">Email:</span>
                        {{ (booking as any).customer_email }}
                    </div>
                </div>

                <div
                    class="space-y-2 rounded-2xl border bg-white p-5 shadow-sm"
                >
                    <h2 class="font-semibold">Selection</h2>
                    <div class="text-sm">
                        <span class="text-neutral-500">Type:</span>
                        {{ (booking as any).booking_type }}
                    </div>
                    <div class="text-sm">
                        <span class="text-neutral-500">Start:</span>
                        {{ (booking as any).start_date }}
                    </div>
                    <div class="text-sm">
                        <span class="text-neutral-500">Days:</span>
                        {{ (booking as any).days }}
                    </div>
                    <div class="text-sm">
                        <span class="text-neutral-500">Passengers:</span>
                        {{ (booking as any).passengers }}
                    </div>
                </div>

                <div
                    class="space-y-2 rounded-2xl border bg-white p-5 shadow-sm md:col-span-2"
                >
                    <h2 class="font-semibold">Totals</h2>
                    <div class="text-sm">
                        <span class="text-neutral-500">Subtotal:</span>
                        {{ (booking as any).subtotal }}
                    </div>
                    <div class="text-sm">
                        <span class="text-neutral-500">Extra KM Fee:</span>
                        {{ (booking as any).extra_km_fee }}
                    </div>
                    <div class="text-sm font-medium">
                        <span class="text-neutral-500">Total:</span>
                        {{ (booking as any).total }}
                    </div>
                </div>
            </div>

            <form
                @submit.prevent="submit"
                class="space-y-4 rounded-2xl border bg-white p-6 shadow-sm"
            >
                <h2 class="text-lg font-semibold">Admin Update</h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm font-medium"
                            >Booking Status</label
                        >
                        <select
                            v-model="(form as any).status"
                            class="mt-1 w-full rounded-xl border px-4 py-2"
                        >
                            <option value="pending">pending</option>
                            <option value="confirmed">confirmed</option>
                            <option value="completed">completed</option>
                            <option value="cancelled">cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-sm font-medium"
                            >Payment Status</label
                        >
                        <select
                            v-model="(form as any).payment_status"
                            class="mt-1 w-full rounded-xl border px-4 py-2"
                        >
                            <option value="unpaid">unpaid</option>
                            <option value="partially_paid">
                                partially_paid
                            </option>
                            <option value="paid">paid</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="text-sm font-medium">Admin Note</label>
                        <textarea
                            v-model="(form as any).admin_note"
                            class="mt-1 w-full rounded-xl border px-4 py-2"
                            rows="4"
                        />
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        :disabled="(form as any).processing"
                        class="rounded-full bg-red-500 px-6 py-2 text-sm font-medium text-white hover:bg-red-600 disabled:opacity-50"
                    >
                        {{
                            (form as any).processing
                                ? 'Saving...'
                                : 'Save Changes'
                        }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
