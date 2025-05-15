<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem, User, Role } from '@/types';
import { UserCircle, Mail, Shield } from 'lucide-vue-next';
import ProfileCard from '@/components/ProfileCard.vue';

const props = defineProps<{
    user: User;
    roles: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Users',
        href: route('users.index'),
    },
    {
        title: props.user.name,
        href: route('users.show', props.user.id),
    }
];

const capitalizeFirstLetter = (string: string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
};

const getRoleBadgeClass = (role: string) => {
    switch (role) {
        case 'admin': return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        case 'asesi': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        case 'asesor': return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }
};

// Prepare data for ProfileCard
const infoSections = [
    {
        icon: Mail,
        label: 'Email Address',
        value: props.user.email
    },
    {
        icon: Shield,
        label: 'User Role',
        value: capitalizeFirstLetter(props.user.role?.name)
    }
];

const detailSections = [
    {
        title: 'Account Information',
        items: [
            {
                label: 'Account ID',
                value: props.user.id
            },
            {
                label: 'Created At',
                value: new Date(props.user.created_at).toLocaleDateString()
            },
            {
                label: 'Last Updated',
                value: new Date(props.user.updated_at).toLocaleDateString()
            }
        ]
    }
];

const actions = [
    {
        label: 'Edit User',
        route: route('users.edit', props.user.id),
        variant: 'outline',
        hoverClass: 'hover:bg-primary-50 hover:text-primary-600 transition-colors'
    },
    {
        label: 'Back to Users',
        route: route('users.index'),
        variant: 'outline',
        hoverClass: 'hover:bg-gray-100 transition-colors'
    }
];
</script>

<template>
    <Head :title="`${props.user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <ProfileCard
                    title="User Details"
                    :entity="props.user"
                    :avatar-icon="UserCircle"
                    :badge-text="capitalizeFirstLetter(props.user.role?.name)"
                    :badge-class="getRoleBadgeClass(props.user.role?.name)"
                    :info-sections="infoSections"
                    :detail-sections="detailSections"
                    :actions="actions"
                />
            </div>
        </div>
    </AppLayout>
</template>