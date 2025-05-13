<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { Card, CardAction, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectGroup, SelectItem, SelectItemText, SelectLabel, SelectSeparator, SelectTrigger, } from '@/components/ui/select';
import { BreadcrumbItem, Role } from '@/types';

defineProps<{
    roles: Role[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Users', href: route('users.index') },
    { title: 'Create', href: route('users.create') },
];

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role_id: '',
});

const capitalizeFirstLetter = (string: string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

const submit = () => {
    form.post(route('users.store'), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset();
        },
    })
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
                    <form @submit.prevent="submit" class="space-y-6">
                    <CardContent>
                        <CardDescription class="mb-6">
                            Fill in the information below to create a new user account. All fields are required.
                        </CardDescription>
                            <div class="grid gap-2">
                                <Label for="name">Name</Label>
                                <Input v-model="form.name" id="name" class="form-input" placeholder="Enter user's full name" />
                                <InputError :message="form.errors.name"/>
                            </div>
                            <div class="grid gap-2">
                                <Label for="email">Email</Label>
                                <Input v-model="form.email" id="email" type="email" placeholder="user@example.com" autocomplete="email" />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="password">Password</Label>
                                <Input v-model="form.password" id="password" type="password" autocomplete="new-password" placeholder="Enter a strong password" />
                                <InputError :message="form.errors.password"/>
                            </div>
                            <div class="grid gap-2">
                                <Label for="password_confirmation">Confirm Password</Label>
                                <Input v-model="form.password_confirmation" id="password_confirmation" type="password" autocomplete="new-password" placeholder="Confirm your password" />
                                <InputError :message="form.errors.password_confirmation"/>
                            </div>
                            <div class="grid gap-2">
                                <Label for="role">Role</Label>
                                <Select v-model="form.role_id" id="role">
                                    <SelectTrigger class="w-full">
                                        <SelectLabel>Select a role</SelectLabel>
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-if="roles" v-for="role in roles" :key="role.id" :value="role.id">
                                            {{ capitalizeFirstLetter(role.name) }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.role_id"/>
                            </div>
                        </CardContent>
                        <CardFooter class="flex items-center justify-end">
                            <CardAction class="rounded-b-lg flex justify-end space-x-2">
                                <Link :href="route('users.index')">
                                    <Button type="button" variant="outline">Cancel</Button>
                                </Link>
                                <Button type="submit" variant="default" :disabled="form.processing">Submit</Button>
                            </CardAction>
                        </CardFooter>
                    </form>
                </Card>                
            </div>
        </div>
    </AppLayout>
</template>
