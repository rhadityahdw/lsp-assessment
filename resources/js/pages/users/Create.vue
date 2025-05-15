<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle } from '@/components/ui/card';
import { BreadcrumbItem, Role } from '@/types';
import UserForm from '@/components/UserForm.vue';

const props = defineProps<{
    roles: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Users', href: route('users.index') },
    { title: 'Create', href: route('users.create') },
];

const handleSubmit = (form: any) => {
    form.post(route('users.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <Head title="Create User" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-xl md:text-2xl font-bold">Create User</CardTitle>
                    </CardHeader>
                    <UserForm 
                        :roles="roles"
                        mode="create"
                        cancel-route="users.index"
                        submit-label="Create"
                        @submit="handleSubmit"
                    />
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
