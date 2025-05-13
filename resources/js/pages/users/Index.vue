<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { Card, CardAction, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Table, TableBody, TableCaption, TableCell, TableEmpty, TableFooter, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { BreadcrumbItem, User } from '@/types';

defineProps<{
    users: User[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Users', href: route('users.index') },
];

</script>

<template>
    <Head title="List Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader class="flex justify-between items-center">
                        <CardTitle class="text-xl md:text-2xl font-bold">List Users</CardTitle>
                        <Link :href="route('users.create')">
                            <Button>Create</Button>
                        </Link>
                    </CardHeader>
                    <CardContent>
                        <div v-if="users.length === 0" class="text-center py-8 text-gray-500">
                            Belum ada pengguna yang terdaftar. Silakan tambahkan pengguna baru.
                        </div>
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableCell>Name</TableCell>
                                    <TableCell>Email</TableCell>
                                    <TableCell>Role</TableCell>
                                    <TableCell class="text-right">Action</TableCell>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                <TableRow v-for="user in users" :key="user.id">
                                    <TableCell>{{ user.name }}</TableCell>
                                    <TableCell>{{ user.email }}</TableCell>
                                    <TableCell>{{ user.role.name }}</TableCell>
                                    <TableCell class="text-right flex space-x-3 justify-end">
                                        <Link :href="route('users.edit', user.id)">
                                            <Button variant="outline">Edit</Button>
                                        </Link>
                                        <Link :href="route('users.show', user.id)">
                                            <Button variant="outline">Show</Button>
                                        </Link>
                                    </TableCell>
                                </TableRow>
                            </TableBody>
                        </Table>
                    </CardContent> 
                </Card>
            </div>
        </div>
    </AppLayout>
</template>