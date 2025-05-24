<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle } from '@/components/ui/card';
import { BreadcrumbItem, User } from '@/types';
import UserForm from '@/components/UserForm.vue';

const props = defineProps<{
    user: User;
    roles: string[];
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
        title: 'Edit',
        href: route('users.edit', props.user.id),
    },
];

const handleUpdateSubmit = (form: any) => {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <Head title="Edit User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex justify-between items-center">
                        <CardTitle class="text-xl md:text-2xl font-bold">Edit User ({{ props.user.name }})</CardTitle>
                    </CardHeader>
                    <UserForm 
                        :roles="roles"
                        mode="edit"
                        :initial-data="user"
                        cancel-route="users.index"
                        submit-label="Update"
                        @submit="handleUpdateSubmit"
                    />
                </Card>
            </div>
        </div>
    </AppLayout>
</template>