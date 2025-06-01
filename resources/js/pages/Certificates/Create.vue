<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Card, CardAction, CardContent, CardFooter } from '@/components/ui/card';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import { BreadcrumbItem } from '@/types';
import { Input } from '@/components/ui/input';
import DatePicker from '@/components/DatePicker.vue';
import { Button } from '@/components/ui/button';
import { useToast } from 'vue-toastification';
import { router } from '@inertiajs/vue3';

const toast = useToast();

const props = defineProps({
    users: {
        type: Object,
        required: true,
    },
    schemes: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    user_id: '',
    scheme_id: '',
    certificate_number: '',
    issued_at: '',
    expiry_date: '',
    file: null as File | null,
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard')
    },
    {
        title: 'Sertifikat',
        href: route('admin.certificates.index')
    },
    {
        title: 'Tambah Sertifikat',
        href: route('admin.certificates.create')
    }
];

const submitForm = () => {
    const formData = new FormData();

    formData.append('user_id', form.user_id);
    formData.append('scheme_id', form.scheme_id);
    formData.append('certificate_number', form.certificate_number);
    formData.append('issued_at', form.issued_at);
    formData.append('expiry_date', form.expiry_date);

    if (form.file) {
        formData.append('file_path', form.file);
    }

    router.post(route('admin.certificates.store'), formData, {
        forceFormData: true,
        onSuccess: () => {
            toast.success('Sertifikat berhasil ditambahkan');
            route('admin.certificates.index');
        },
        onError: (errors: any) => {
            toast.error('Terjadi kesalahan saat menambahkan sertifikat');
            console.error('Validation errors:', errors);
        }
    })
};
</script>

<template>
    <Head title="Tambah Sertifikat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Tambah Sertifikat"/>
                    <form @submit.prevent="submitForm">
                        <CardContent>
                            <div class="grid gap-4">
                                <div class="space-y-2">
                                    <Label for="asesi">Penerima Sertifikat</Label>
                                    <Select id="asesi" v-model="form.user_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Pilih penerima sertifikat" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="user in props.users?.data || null" :key="user.id" :value="user.id.toString()">
                                                {{ user.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :messages="form.errors.user_id" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="scheme">Skema</Label>
                                    <Select id="scheme" v-model="form.scheme_id">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Pilih skema" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="scheme in props.schemes?.data || []" :key="scheme.id" :value="scheme.id.toString()">
                                                {{ scheme.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :messages="form.errors.scheme_id" />
                                </div>

                                <div class="space-y-2">
                                    <Label for="certificate_number">Nomor Sertifikat</Label>
                                    <Input type="text" id="certificate_number" v-model="form.certificate_number" class="w-full" placeholder="Masukkan nomor sertifikat" />
                                    <InputError :messages="form.errors.certificate_number" />
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label for="issued_at">Tanggal Terbit</Label>
                                        <DatePicker type="date" id="issued_at" v-model="form.issued_at" class="w-full" />
                                        <InputError :messages="form.errors.issued_at" />
                                    </div>

                                    <div class="space-y-2">
                                        <Label for="expiry_date">Tanggal Kadaluarsa</Label>
                                        <DatePicker type="date" id="expiry_date" v-model="form.expiry_date" class="w-full" />
                                        <InputError :messages="form.errors.expiry_date" />
                                    </div>
                                </div>

                                <div class="space-y-2">
                                    <Label for="file_path">File Sertifikat</Label>
                                    <Input type="file" id="file_path" @change="(e: any) => (form.file = e.target.files?.[0] || null)" access=".pdf" class="w-full" />
                                    <p class="mt-1 text-xs text-gray-500">PDF (max. 2MB)</p>
                                    <InputError :messages="form.errors.file" />
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter class="flex items-center justify-end">
                            <CardAction class="rounded-b-lg flex justify-end gap-2">
                                <Link :href="route('admin.certificates.index')">
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