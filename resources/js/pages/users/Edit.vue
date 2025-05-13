<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { Card, CardAction, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
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
        title: 'Edit',
        href: route('users.edit', props.user.id),
    },
];

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
    role_id: props.user.role_id,
})

const submit = () => {
    form.post(route('users.update', props.user.id), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        },
    })
}
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
                    <CardContent>
                        <CardDescription class="mb-6">
                            Edit the user's details.
                        </CardDescription>
                        <form @submit.prevent="submit" class="space-y-6">
                            <div class="grid gap-2">
                                <Label for="name">Name</Label>
                                <Input v-model="form.name" id="name" class="form-input" name="name" />
                                <InputError :message="form.errors.name" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="email">Email</Label>
                                <Input v-model="form.email" id="email" class="form-input" name="email" />
                                <InputError :message="form.errors.email" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="password">Password</Label>
                                <Input v-model="form.password" id="password" type="password" name="password" placeholder="Leave blank if you won't change current password"/>
                                <InputError :message="form.errors.password" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="password_confirmation">Confirm Password</Label>
                                <Input v-model="form.password_confirmation" id="password_confirmation" type="password" name="password_confirmation" />
                                <InputError :message="form.errors.password_confirmation" />
                            </div>
                            <div class="grid gap-2">
                                <Label for="role">Role</Label>
                                <Select v-model="form.role_id" id="role" >
                                    <SelectTrigger class="w-full">
                                        <SelectValue>{{ props.roles.find(role => role.id === form.role_id)?.name }}</SelectValue>
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="role in props.roles" :value="role.id" :key="role.id">
                                            {{ role.name }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <InputError for="role_id" class="mt-2" />
                            </div>
                        </form>
                    </CardContent>
                    <CardFooter class="flex items-center justify-end">
                        <CardAction class="rounded-b-lg flex justify-end space-x-2">
                            <Link :href="route('users.index')">
                                <Button type="button" variant="outline">Cancel</Button>
                            </Link>
                            <Button type="submit" variant="default" :disabled="form.processing">Submit</Button>
                        </CardAction>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
    <div>
        <h1>EDIT USER</h1>
    </div>
</template>