<script lang="ts">
import AppLayout from '@/Backend/layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { defineComponent } from 'vue';
import { route } from 'ziggy-js';

export default defineComponent({
    components: { AppLayout, Head, Link },
    props: {
        range: { type: Object, required: true },
        kpis: { type: Object, required: true },
        charts: { type: Object, required: true },
        latest: { type: Array, required: true },
    },
    data() {
        return {
            days: (this.range as any).days || 14,
        };
    },
    computed: {
        bookingsMax(): number {
            const arr = ((this.charts as any).bookingsPerDay || []) as any[];
            return Math.max(1, ...arr.map((x) => Number(x.value || 0)));
        },
        revenueMax(): number {
            const arr = ((this.charts as any).revenuePerDay || []) as any[];
            return Math.max(1, ...arr.map((x) => Number(x.value || 0)));
        },
        topPackagesMax(): number {
            const arr = ((this.charts as any).topPackages || []) as any[];
            return Math.max(1, ...arr.map((x) => Number(x.value || 0)));
        },
    },
    methods: {
        fmtMoney(v: number) {
            try {
                return new Intl.NumberFormat('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                }).format(v || 0);
            } catch {
                return String(v || 0);
            }
        },
        badgeClass(status: string) {
            if (status === 'confirmed') return 'bg-blue-100 text-blue-700';
            if (status === 'completed') return 'bg-green-100 text-green-700';
            if (status === 'cancelled') return 'bg-red-100 text-red-700';
            return 'bg-yellow-100 text-yellow-700'; // pending
        },
        payClass(status: string) {
            if (status === 'paid') return 'bg-green-100 text-green-700';
            if (status === 'partially_paid')
                return 'bg-yellow-100 text-yellow-700';
            return 'bg-neutral-200 text-neutral-700';
        },
        applyRange() {
            router.get(
                route('dashboard'),
                { days: this.days },
                { preserveState: true, replace: true },
            );
        },
    },
});
</script>

<template>
    <AppLayout>
        <Head title="Dashboard" />

        <div class="space-y-6 p-6">
            <!-- Header -->
            <div
                class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between"
            >
                <div>
                    <h1 class="text-2xl font-bold">Dashboard</h1>
                    <p class="text-sm text-neutral-500">
                        Overview for {{ (range as any).from }} →
                        {{ (range as any).to }}
                    </p>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                    <select
                        v-model.number="days"
                        class="rounded-xl border border-neutral-200 px-4 py-2 text-sm"
                    >
                        <option :value="7">Last 7 days</option>
                        <option :value="14">Last 14 days</option>
                        <option :value="30">Last 30 days</option>
                        <option :value="60">Last 60 days</option>
                        <option :value="90">Last 90 days</option>
                    </select>
                    <button
                        type="button"
                        @click="applyRange"
                        class="rounded-xl bg-neutral-900 px-4 py-2 text-sm font-medium text-white"
                    >
                        Apply
                    </button>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-5">
                <div class="rounded-2xl border bg-white p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Total Bookings</p>
                    <p class="mt-2 text-3xl font-bold">
                        {{ (kpis as any).totalBookings }}
                    </p>
                </div>

                <div class="rounded-2xl border bg-white p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Total Revenue</p>
                    <p class="mt-2 text-3xl font-bold">
                        {{ fmtMoney((kpis as any).totalRevenue) }}
                    </p>
                    <p class="mt-1 text-xs text-neutral-400">
                        LKR (or your default currency)
                    </p>
                </div>

                <div class="rounded-2xl border bg-white p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Pending</p>
                    <p class="mt-2 text-3xl font-bold">
                        {{ (kpis as any).pendingCount }}
                    </p>
                </div>

                <div class="rounded-2xl border bg-white p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Cancelled</p>
                    <p class="mt-2 text-3xl font-bold">
                        {{ (kpis as any).cancelledCount }}
                    </p>
                </div>

                <div class="rounded-2xl border bg-white p-5 shadow-sm">
                    <p class="text-sm text-neutral-500">Top Package</p>
                    <p class="mt-2 truncate text-lg font-bold">
                        {{ (kpis as any).topPackageLabel }}
                    </p>
                    <p class="text-sm text-neutral-500">
                        {{ (kpis as any).topPackageCount }} bookings
                    </p>
                </div>
            </div>

            <!-- Charts -->
            <div class="grid grid-cols-1 gap-4 xl:grid-cols-3">
                <!-- Bookings per day -->
                <div
                    class="rounded-2xl border bg-white p-5 shadow-sm xl:col-span-2"
                >
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold">Bookings (per day)</h2>
                        <span class="text-xs text-neutral-500">Bars</span>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div
                            v-for="(p, i) in (charts as any).bookingsPerDay"
                            :key="i"
                            class="flex items-center gap-3"
                        >
                            <div class="w-28 text-xs text-neutral-500">
                                {{ p.date }}
                            </div>
                            <div
                                class="h-3 flex-1 overflow-hidden rounded-full bg-neutral-100"
                            >
                                <div
                                    class="h-3 rounded-full bg-neutral-900"
                                    :style="{
                                        width: `${Math.round((p.value / bookingsMax) * 100)}%`,
                                    }"
                                />
                            </div>
                            <div class="w-10 text-right text-xs font-medium">
                                {{ p.value }}
                            </div>
                        </div>

                        <p
                            v-if="!(charts as any).bookingsPerDay?.length"
                            class="text-sm text-neutral-500"
                        >
                            No data in this range.
                        </p>
                    </div>
                </div>

                <!-- Status breakdown -->
                <div class="rounded-2xl border bg-white p-5 shadow-sm">
                    <h2 class="font-semibold">Booking Status</h2>

                    <div class="mt-4 space-y-2">
                        <div
                            v-for="(s, i) in (charts as any).statusBreakdown"
                            :key="i"
                            class="flex items-center justify-between"
                        >
                            <span class="text-sm capitalize">{{
                                s.status
                            }}</span>
                            <span class="text-sm font-semibold">{{
                                s.value
                            }}</span>
                        </div>
                        <p
                            v-if="!(charts as any).statusBreakdown?.length"
                            class="text-sm text-neutral-500"
                        >
                            No status data.
                        </p>
                    </div>
                </div>

                <!-- Revenue per day -->
                <div
                    class="rounded-2xl border bg-white p-5 shadow-sm xl:col-span-2"
                >
                    <div class="flex items-center justify-between">
                        <h2 class="font-semibold">Revenue (per day)</h2>
                        <span class="text-xs text-neutral-500">Bars</span>
                    </div>

                    <div class="mt-4 space-y-2">
                        <div
                            v-for="(p, i) in (charts as any).revenuePerDay"
                            :key="i"
                            class="flex items-center gap-3"
                        >
                            <div class="w-28 text-xs text-neutral-500">
                                {{ p.date }}
                            </div>
                            <div
                                class="h-3 flex-1 overflow-hidden rounded-full bg-neutral-100"
                            >
                                <div
                                    class="h-3 rounded-full bg-red-500"
                                    :style="{
                                        width: `${Math.round((p.value / revenueMax) * 100)}%`,
                                    }"
                                />
                            </div>
                            <div class="w-20 text-right text-xs font-medium">
                                {{ fmtMoney(p.value) }}
                            </div>
                        </div>

                        <p
                            v-if="!(charts as any).revenuePerDay?.length"
                            class="text-sm text-neutral-500"
                        >
                            No revenue data in this range.
                        </p>
                    </div>
                </div>

                <!-- Top packages -->
                <div class="rounded-2xl border bg-white p-5 shadow-sm">
                    <h2 class="font-semibold">Top Packages</h2>

                    <div class="mt-4 space-y-3">
                        <div
                            v-for="(t, i) in (charts as any).topPackages"
                            :key="i"
                            class="space-y-1"
                        >
                            <div
                                class="flex items-center justify-between gap-2"
                            >
                                <p class="truncate text-sm font-medium">
                                    {{ t.label }}
                                </p>
                                <p class="text-sm font-semibold">
                                    {{ t.value }}
                                </p>
                            </div>
                            <div
                                class="h-2 overflow-hidden rounded-full bg-neutral-100"
                            >
                                <div
                                    class="h-2 rounded-full bg-neutral-900"
                                    :style="{
                                        width: `${Math.round((t.value / topPackagesMax) * 100)}%`,
                                    }"
                                />
                            </div>
                        </div>

                        <p
                            v-if="!(charts as any).topPackages?.length"
                            class="text-sm text-neutral-500"
                        >
                            No package data.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Latest bookings -->
            <div class="overflow-hidden rounded-2xl border bg-white shadow-sm">
                <div class="flex items-center justify-between p-5">
                    <div>
                        <h2 class="font-semibold">Latest Bookings</h2>
                        <p class="text-xs text-neutral-500">
                            Most recent booking requests.
                        </p>
                    </div>
                    <Link
                        :href="route('bookings.index')"
                        class="rounded-full border px-4 py-2 text-sm hover:bg-neutral-50"
                    >
                        View All
                    </Link>
                </div>

                <table class="w-full text-sm">
                    <thead class="bg-neutral-50 text-neutral-600">
                        <tr>
                            <th class="p-3 text-left">Ref</th>
                            <th class="p-3 text-left">Customer</th>
                            <th class="p-3 text-left">Product</th>
                            <th class="p-3 text-left">Start</th>
                            <th class="p-3 text-left">Total</th>
                            <th class="p-3 text-left">Status</th>
                            <th class="p-3 text-left">Payment</th>
                            <th class="p-3 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr
                            v-for="b in latest"
                            :key="(b as any).id"
                            class="border-t"
                        >
                            <td class="p-3 font-medium">
                                {{ (b as any).booking_ref }}
                            </td>
                            <td class="p-3">
                                <div class="font-medium">
                                    {{ (b as any).customer_name }}
                                </div>
                                <div class="text-xs text-neutral-500">
                                    {{ (b as any).customer_phone }}
                                </div>
                            </td>
                            <td class="p-3">
                                <div class="text-xs text-neutral-500 uppercase">
                                    {{ (b as any).booking_type }}
                                </div>
                                <div class="font-medium">
                                    {{ (b as any).product }}
                                </div>
                            </td>
                            <td class="p-3">{{ (b as any).start_date }}</td>
                            <td class="p-3">
                                {{ fmtMoney((b as any).total) }}
                            </td>
                            <td class="p-3">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-xs font-medium capitalize"
                                    :class="badgeClass((b as any).status)"
                                >
                                    {{ (b as any).status }}
                                </span>
                            </td>
                            <td class="p-3">
                                <span
                                    class="inline-flex rounded-full px-3 py-1 text-xs font-medium"
                                    :class="payClass((b as any).payment_status)"
                                >
                                    {{ (b as any).payment_status }}
                                </span>
                            </td>
                            <td class="p-3 text-right">
                                <Link
                                    :href="
                                        route('bookings.show', (b as any).id)
                                    "
                                    class="rounded-full border px-3 py-1.5 text-xs font-medium hover:bg-neutral-50"
                                >
                                    View
                                </Link>
                            </td>
                        </tr>

                        <tr v-if="!latest.length">
                            <td
                                colspan="8"
                                class="p-6 text-center text-neutral-500"
                            >
                                No bookings yet.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>
