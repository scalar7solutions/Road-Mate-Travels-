<script setup lang="ts">
import NavFooter from '@/Backend/components/NavFooter.vue';
import NavMain from '@/Backend/components/NavMain.vue';
import NavUser from '@/Backend/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/Backend/components/ui/sidebar';
import { NavItemWithSubmenu, type NavItem } from '@/Backend/types';
import { Link } from '@inertiajs/vue3';
import {
    BookOpen,
    Briefcase,
    Car,
    ClipboardList,
    LayoutDashboard,
    MapPinned,
    Truck,
} from 'lucide-vue-next';
import { route } from 'ziggy-js';
import AppLogo from './AppLogo.vue';

const mainNavItems: NavItemWithSubmenu[] = [
    { title: 'Dashboard', href: route('dashboard'), icon: LayoutDashboard },

    { title: 'Vehicle Types', href: route('vehicletypes.index'), icon: Car },

    {
        title: 'Destinations',
        href: route('destinationgroups.index'),
        icon: MapPinned,
        submenu: [
            {
                title: 'Destination Groups',
                href: route('destinationgroups.index'),
            },
            { title: 'Places', href: route('places.index') },
        ],
    },

    {
        title: 'Tour Packages',
        href: route('tourpackages.index'),
        icon: Briefcase,
    },
    {
        title: 'Rental Packages',
        href: route('rentalpackages.index'),
        icon: Truck,
    },

    { title: 'Bookings', href: route('bookings.index'), icon: ClipboardList },
];

const footerNavItems: NavItem[] = [
    { title: 'Privacy Policy', href: route('privacypolicy'), icon: BookOpen },
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>

    <slot />
</template>
