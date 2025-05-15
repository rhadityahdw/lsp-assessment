<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import InputError from '@/components/InputError.vue';
import { CardAction, CardContent, CardDescription, CardFooter } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Role, User } from '@/types';

const props = defineProps<{
    roles: Role[];
    submitLabel?: string;
    cancelRoute?: string;
    initialData?: User;
    mode: 'create' | 'edit';
}>();

const emit = defineEmits<{
    (e: 'submit', form: any): void;
}>();

// Initialize form with data based on mode
const form = useForm({
    name: props.initialData?.name || '',
    email: props.initialData?.email || '',
    password: '',
    password_confirmation: '',
    role_id: props.initialData?.role_id || '',
});

const capitalizeFirstLetter = (string: string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

const submit = () => {
    emit('submit', form);
};
</script>

<template>
    <form @submit.prevent="submit" class="space-y-6">
        <CardContent class="space-y-6">
            <CardDescription class="mb-6" v-if="mode === 'create'">
                Fill in the information below to create a new user account. All fields are required.
            </CardDescription>
            <CardDescription class="mb-6" v-else>
                Update the user information below. Leave password fields empty to keep the current password.
            </CardDescription>
            <div class="grid gap-2">
                <Label for="name">Name</Label>
                <Input v-model="form.name" id="name" class="form-input" placeholder="Enter user's full name" tabindex="1" />
                <InputError :message="form.errors.name"/>
            </div>
            <div class="grid gap-2">
                <Label for="email">Email</Label>
                <Input v-model="form.email" id="email" type="email" placeholder="user@example.com" autocomplete="email" tabindex="2" />
                <InputError :message="form.errors.email" />
            </div>
            <div class="grid gap-2">
                <Label for="password">Password</Label>
                <Input 
                    v-model="form.password" 
                    id="password" 
                    type="password" 
                    autocomplete="new-password" 
                    :placeholder="mode === 'create' ? 'Enter a strong password' : 'Leave empty to keep current password'" 
                    tabindex="3" 
                />
                <InputError :message="form.errors.password"/>
            </div>
            <div class="grid gap-2">
                <Label for="password_confirmation">Confirm Password</Label>
                <Input 
                    v-model="form.password_confirmation" 
                    id="password_confirmation" 
                    type="password" 
                    autocomplete="new-password" 
                    :placeholder="mode === 'create' ? 'Confirm your password' : 'Leave empty to keep current password'" 
                    tabindex="4" 
                />
                <InputError :message="form.errors.password_confirmation"/>
            </div>
            <div class="grid gap-2">
                <Label for="role">Role</Label>
                <Select v-model="form.role_id" id="role">
                    <SelectTrigger class="w-full" tabindex="5">
                        <SelectValue placeholder="Select a role" />
                    </SelectTrigger>
                    <SelectContent>
                        <SelectItem v-for="role in roles" :key="role.id" :value="role.id">
                            {{ capitalizeFirstLetter(role.name) }}
                        </SelectItem>
                    </SelectContent>
                </Select>
                <InputError :message="form.errors.role_id"/>
            </div>
        </CardContent>
        <CardFooter class="flex items-center justify-end">
            <CardAction class="rounded-b-lg flex justify-end gap-2">
                <Link v-if="cancelRoute" :href="route(cancelRoute)">
                    <Button type="button" variant="outline" tabindex="6">Cancel</Button>
                </Link>
                <Button type="submit" variant="default" :disabled="form.processing" tabindex="7">
                    {{ submitLabel || (mode === 'create' ? 'Create' : 'Update') }}
                </Button>
            </CardAction>
        </CardFooter>
    </form>
</template>