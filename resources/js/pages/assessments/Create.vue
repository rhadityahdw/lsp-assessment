<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/AppLayout.vue';
import { Select, SelectTrigger, SelectContent, SelectItem, SelectValue } from '@/components/ui/select';
import { Card, CardAction, CardContent, CardFooter } from '@/components/ui/card';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import { BreadcrumbItem } from '@/types';
import InputError from '@/components/InputError.vue';
import { useToast } from 'vue-toastification';

const toast = useToast();

defineProps<{
    schemes: {
        id: number;
        name: number;
    }[]
}>();

const form = useForm({
    scheme_id: '',
    name: '',
    type: '',
    link: ''
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard')
    },
    {
        title: 'Asesmen',
        href: route('assessments.index')
    },
    {
        title: 'Tambah Asesmen',
        href: route('assessments.create')
    }
];

const submit = () => {
    form.post(route('assessments.store'), {
        onSuccess: () => {
            toast.success('Asesmen berhasil ditambahkan');
            route('assessments.index');
        },
        onError: (errors: any) => {
            toast.error('Terjadi kesalahan saat menambahkan asesmen');
        }
    })
}
</script>

<template>
    <Head title="Tambah Asesmen" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Tambah Asesmen" />
                    <form @submit.prevent="submit">
                        <CardContent>
                            <div class="space-y-6">
                                <div class="space-y-2">
                                    <Label for="scheme">Skema</Label>
                                    <Select id="scheme" v-model="form.scheme_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Pilih skema" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem
                                                v-for="scheme in schemes"
                                                :key="scheme.id"
                                                :value="scheme.id"
                                            >
                                                {{ scheme.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.scheme_id" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="name">Nama Asesmen</Label>
                                    <Input 
                                        id="name"
                                        v-model="form.name"
                                        type="text"
                                        placeholder="Masukan nama asesmen"
                                    />
                                    <InputError :message="form.errors.name" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="type">Skema</Label>
                                    <Select id="type" v-model="form.type">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Pilih tipe" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem value="tulis">Tulis</SelectItem>
                                            <SelectItem value="praktek">Praktek</SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.type" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="link">Link Asesmen</Label>
                                    <Input 
                                        id="link"
                                        v-model="form.link"
                                        type="text"
                                        placeholder="Masukan link asesmen"
                                    />
                                    <InputError :message="form.errors.link" />
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter class="flex items-center justify-end mt-4">
                            <CardAction class="rounded-b-lg flex justify-end gap-2">
                                <Link :href="route('assessments.index')">
                                    <Button type="button" variant="outline">
                                        Kembali
                                    </Button>
                                </Link>
                                <Button type="submit" :disabled="form.processing">
                                    Simpan
                                </Button>
                            </CardAction>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>