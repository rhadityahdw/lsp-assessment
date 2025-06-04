<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Folder, LayoutGrid, User, FilePenLine, CalendarClock, FileText, BookIcon, ClipboardCheck } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { computed } from 'vue';

const page = usePage();
const userRole = computed(() => {
    const auth = page.props.auth as { user?: { roles: string[] } };
    return auth?.user?.roles[0];
});

// Admin navigation items
const adminNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        icon: LayoutGrid,
    },
    {
        title: 'Pengguna',
        href: route('users.index'),
        icon: User,
    },
    {
        title: 'Skema',
        href: route('schemes.index'),
        icon: Folder,
    },
    {
        title: 'Unit',
        href: route('units.index'),
        icon: Folder,
    },
    {
        title: 'Pendaftaran',
        href: route('attempts.index'),
        icon: FilePenLine,
    },
    {
        title: 'Jadwal',
        href: route('schedules.index'),
        icon: CalendarClock,
    },
    {
        title: 'Asesmen',
        href: route('assessments.index'),
        icon: BookOpen,
    },
    {
        title: 'Sertifikat',
        href: route('admin.certificates.index'),
        icon: FileText,
    }
];

// Asesor navigation items
const asesorNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        icon: LayoutGrid,
    },
    {
        title: 'Jadwal Saya',
        href: route('schedules.index'),
        icon: CalendarClock,
    },
    {
        title: 'Penilaian',
        href: route('asesor.grading.index'),
        icon: ClipboardCheck,
    },
    {
        title: 'Asesmen',
        href: route('assessments.index'),
        icon: BookOpen,
    }
];

// Asesi navigation items
const asesiNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
        icon: LayoutGrid,
    },
    {
        title: 'Asesmen Saya',
        href: route('asesi.assessments.index'),
        icon: BookOpen,
    },
    {
        title: 'Sertifikat',
        href: route('asesi.certificates.index'),
        icon: FileText,
    }
];

// Compute navigation items based on user role
const mainNavItems = computed(() => {
    switch (userRole.value) {
        case 'admin':
            return adminNavItems;
        case 'asesor':
            return asesorNavItems;
        case 'asesi':
            return asesiNavItems;
        default:
            return [];
    }
});

const footerNavItems: NavItem[] = [];
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
