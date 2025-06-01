<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardFooter, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import { Download, Eye, FileText, Award, Calendar, CalendarX, ArrowLeft } from 'lucide-vue-next';

const props = defineProps({
    certificate: {
        type: Object,
        required: true,
    },
});

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};

const downloadCertificate = () => {
    const fileUrl = props.certificate?.data.file_path;
    if (fileUrl) {
        const link = document.createElement('a');
        link.href = `/storage/${fileUrl}`;
        link.download = `sertifikat-${props.certificate.data.certificate_number}.pdf`;
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
    <Head :title="`Sertifikat - ${props.certificate?.data.title || 'Detail'}`" />

    <Navbar />

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <Link :href="route('asesi.certificates.index')" class="text-primary hover:text-primary/80 flex items-center gap-2">
                    <ArrowLeft class="h-4 w-4" />
                    Kembali ke Daftar Sertifikat
                </Link>
            </div>

            <Card v-if="props.certificate.data" class="shadow-lg">
                <CardHeader class="border-b border-gray-100 pb-8">
                    <div class="flex items-center gap-4">
                        <div class="p-3 bg-primary/10 rounded-lg">
                            <Award class="h-8 w-8 text-primary" />
                        </div>
                        <div>
                            <CardTitle class="text-2xl font-bold mb-2">Detail Sertifikat</CardTitle>
                            <CardDescription>Informasi lengkap tentang sertifikat kompetensi Anda</CardDescription>
                        </div>
                    </div>
                </CardHeader>

                <CardContent class="pt-8">
                    <div class="grid gap-8">
                        <div class="grid sm:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Nomor Sertifikat</p>
                                <p class="text-lg font-semibold text-primary">{{ props.certificate.data.certificate_number }}</p>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Skema Sertifikasi</p>
                                <p class="text-lg font-medium">{{ props.certificate.data.scheme.name }}</p>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Tanggal Diterbitkan</p>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <Calendar class="h-5 w-5 text-primary" />
                                    <p class="text-base">{{ formatDate(props.certificate.data.issued_at) }}</p>
                                </div>
                            </div>

                            <div class="space-y-2">
                                <p class="text-sm font-medium text-gray-500">Tanggal Kadaluwarsa</p>
                                <div class="flex items-center gap-2 text-gray-700">
                                    <CalendarX class="h-5 w-5 text-primary" />
                                    <p class="text-base">{{ formatDate(props.certificate.data.expiry_date) }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-gray-100 pt-6">
                            <p class="text-sm font-medium text-gray-500 mb-4">File Sertifikat</p>
                            <div class="bg-gray-50 p-6 rounded-lg border border-gray-100">
                                <div class="flex items-center gap-4">
                                    <div class="p-3 bg-primary/10 rounded-lg">
                                        <FileText class="h-6 w-6 text-primary" />
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">{{ getFileName(props.certificate?.data.file_path || '') }}</p>
                                        <p class="text-sm text-gray-500 mt-1">Format PDF</p>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <Button @click="showFile" variant="outline" size="lg" class="flex items-center gap-2">
                                            <Eye class="h-4 w-4" />
                                            Lihat
                                        </Button>
                                        <Button @click="downloadCertificate" variant="default" size="lg" class="flex items-center gap-2">
                                            <Download class="h-4 w-4" />
                                            Unduh
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card v-else class="text-center py-12">
                <CardHeader>
                    <div class="mx-auto w-12 h-12 rounded-full bg-red-100 flex items-center justify-center mb-4">
                        <XCircle class="h-6 w-6 text-red-600" />
                    </div>
                    <CardTitle class="text-2xl font-bold text-gray-900">Sertifikat Tidak Ditemukan</CardTitle>
                    <CardDescription class="mt-2">Maaf, sertifikat yang Anda cari tidak tersedia dalam sistem.</CardDescription>
                </CardHeader>
                <CardContent>
                    <Link :href="route('asesi.certificates.index')" class="mt-6 inline-block">
                        <Button variant="default" size="lg" class="flex items-center gap-2">
                            <ArrowLeft class="h-4 w-4" />
                            Kembali ke Daftar Sertifikat
                        </Button>
                    </Link>
                </CardContent>
            </Card>
        </div>
    </div>

    <Footer />
</template>