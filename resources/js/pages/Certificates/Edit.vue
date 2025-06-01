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

const toast = useToast();

const props = defineProps({
    certificate: {
        type: Object,
        required: true,
    },
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
    user_id: props.certificate?.data?.user?.id || '',
    scheme_id: props.certificate?.data?.scheme?.id || '',
    certificate_number: props.certificate?.data?.certificate_number || '',
    issued_at: props.certificate?.data?.issued_at || '',
    expiry_date: props.certificate?.data?.expiry_date || '',
    file_path: null as File | null,
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
        title: `Edit Sertifikat - ${props.certificate?.data?.certificate_number}`,
        href: route('admin.certificates.edit', props.certificate?.data.id)
    }
];

const submitForm = () => {
    const formData = new FormData();

    formData.append('_method', 'PUT');
    formData.append('user_id', form.user_id);
    formData.append('scheme_id', form.scheme_id);
    formData.append('certificate_number', form.certificate_number);
    formData.append('issued_at', form.issued_at);
    formData.append('expiry_date', form.expiry_date);

    if (form.file_path) {
        formData.append('file', form.file_path);
    }

    form.put(route('admin.certificates.update', props.certificate.id), {
        forceFormData: true,
        onSuccess: () => {
            toast.success('Sertifikat berhasil diperbarui');
            window.location.href = route('admin.certificates.index');
        },
        onError: () => {
            toast.error('Terjadi kesalahan saat memperbarui sertifikat');
        }
    });
};
</script>

<template>
    <Head title="Edit Sertifikat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Edit Sertifikat"/>
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
                                            <SelectItem 
                                                v-for="user in props.users?.data" 
                                                :key="user.id" 
                                                :value="user.id"
                                            >
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
                                            <SelectItem 
                                                v-for="scheme in props.schemes?.data" 
                                                :key="scheme.id" 
                                                :value="scheme.id"
                                            >
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
                                    <Input 
                                        type="file" 
                                        id="file_path" 
                                        @change="(e: any) => (form.file_path = e.target.files?.[0] || null)" 
                                        accept=".pdf" 
                                        class="w-full" 
                                    />
                                    <p class="mt-1 text-xs text-gray-500">
                                        PDF (max. 2MB)
                                        <span v-if="props.certificate?.data?.file_path" class="ml-2">
                                            File saat ini: {{ props.certificate.data.file_path.split('/').pop() }}
                                        </span>
                                    </p>
                                    <InputError :messages="form.errors.file_path" />
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