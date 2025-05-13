<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { Card, CardAction, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { BreadcrumbItem, User, Role } from '@/types';

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
        title: 'Edit ',
        href: route('users.edit', props.user.id),
    }
];
</script>

<template>
    <Head :title="`User: ${props.user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex justify-between items-center">
                        <CardTitle class="text-xl md:text-2xl font-bold">User Details</CardTitle>
                        <div class="flex space-x-2">
                            <Link :href="route('users.edit', props.user.id)">
                                <Button variant="outline">Edit User</Button>
                            </Link>
                            <Link :href="route('users.index')">
                                <Button variant="outline">Back to Users</Button>
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <Label for="name">Name</Label>
                                <p class="font-reguler">{{ props.user.name }}</p>
                            </div>
                            <div class="space-y-2">
                                <Label for="email">Email</Label>
                                <p class="font-reguler">{{ props.user.email }}</p>
                            </div>
                            <div class="space-y-2">
                                <Label for="role">Role</Label>
                                <p class="font-reguler">{{ props.user.role?.name }}</p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>