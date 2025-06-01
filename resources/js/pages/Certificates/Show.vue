<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import { BreadcrumbItem } from '@/types';
import { Barcode, CalendarX, CalendarCheck, User, BookOpen, Download, FileText, Eye } from 'lucide-vue-next';

const props = defineProps({
    certificate: {
        type: Object,
        required: true,
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Sertifikat',
        href: route('admin.certificates.index'),
    },
    {
        title: 'Detail Sertifikat',
        href: route('admin.certificates.show', props.certificate?.data.id),
    },
];

const downloadCertificate = () => {
    const fileUrl = props.certificate?.data.file_path;
    if (fileUrl) {
        const link = document.createElement('a');
        link.href = `/storage/${fileUrl}`;
        link.download = `sertifikat-${props.certificate?.data.certificate_number}.pdf`;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
};

const showFile = () => {
    const fileUrl = props.certificate?.data.file_path;
    if (fileUrl) {
        window.open(`/storage/${fileUrl}`, '_blank');
    }
};

const getFileName = (path: string) => {
    path.split('/').pop() || 'Tidak ada file';
    const file = `sertifikat-${props.certificate?.data.certificate_number}.pdf`;
    return file;
};
</script>

<template>
    <Head title="Detail Sertifikat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Detail Sertifikat" />
                    <CardContent>
                        <div class="space-y-2 mb-2">
                            <div class="text-sm text-gray-500">Nama</div>
                                <div class="font-medium flex items-center">
                                    <User class="h-4 w-4 mr-2 text-primary" />
                                    {{ props.certificate?.data.user.name || '' }}
                                </div>
                            </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Skema</div>
                                <div class="font-medium flex items-center">
                                    <BookOpen class="h-4 w-4 mr-2 text-primary" />
                                    {{ props.certificate?.data.scheme.name }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Nomor Sertifikat</div>
                                <div class="font-medium flex items-center">
                                    <Barcode class="h-4 w-4 mr-2 text-primary" />
                                    {{ props.certificate?.data.certificate_number }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Tanggal Terbit</div>
                                <div class="font-medium flex items-center">
                                    <CalendarCheck class="h-4 w-4 mr-2 text-primary" />
                                    {{ props.certificate?.data.issued_at }}
                                </div>
                            </div>

                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Tanggal Kadaluarsa</div>
                                <div class="font-medium flex items-center">
                                    <CalendarX class="h-4 w-4 mr-2 text-primary" />
                                    {{ props.certificate?.data.expiry_date }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 space-y-2">
                            <div class="text-sm text-gray-500">File Sertifikat</div>
                            <div class="flex items-center gap-2 p-4 border rounded-lg">
                                <FileText class="h-5 w-5 text-primary" />
                                <span class="flex-1 font-medium">
                                    {{ getFileName(props.certificate?.data.file_path || '') }}
                                </span>
                                <Button @click="showFile" variant="outline" size="sm" class="flex items-center gap-2">
                                    <Eye class="h-4 w-4" />
                                    Lihat
                                </Button>
                                <Button @click="downloadCertificate" variant="outline" size="sm" class="flex items-center gap-2">
                                    <Download class="h-4 w-4" />
                                    Unduh
                                </Button>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <div class="flex justify-between w-full">
                            <Link :href="route('admin.certificates.index')">
                                <Button variant="outline">Kembali ke Daftar</Button>
                            </Link>
                        </div>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
