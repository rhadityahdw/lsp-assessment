<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle, CardDescription } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import Navbar from '@/components/Navbar.vue';
import Footer from '@/components/Footer.vue';
import { PropType } from 'vue';
import { CalendarCheck, CalendarX, Download, Eye, ArrowLeft, FileText } from 'lucide-vue-next';

interface CertificateItem {
    id: number;
    certificate_number: string;
    issued_at: string;
    expiry_date: string;
    file_path: string;
    user: { id: number; name: string; };
    scheme: { id: number; name: string; };
}

interface PaginatedData<T> {
    data: T[];
    links: { 
        url: string | null;
        label: string;
        active: boolean;
    }[];
    meta: {
        current_page: number;
        from: number;
        last_page: number;
        path: string;
        per_page: number;
        to: number;
        total: number;
    };
}

const props = defineProps({
    certificates: {
        type: Object as PropType<PaginatedData<CertificateItem>>, // Ini akan memberikan type safety penuh
        required: true,
        default: () => ({ data: [], links: [], meta: {} }) // Default value yang sesuai dengan tipe
    },
});

const certificates = props.certificates.data;

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Daftar Sertifikat" />

    <Navbar />

    <div class="min-h-screen bg-gradient-to-b from-gray-50 to-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-12">
                <h1 class="text-4xl font-bold text-gray-900 mb-4">Daftar Sertifikat Anda</h1>
                <p class="text-lg text-gray-600">Kelola dan lihat semua sertifikat kompetensi Anda di satu tempat</p>
            </div>

            <div v-if="certificates && certificates.length > 0" 
                 class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <Card v-for="certificate in certificates" 
                      :key="certificate.id"
                      class="transform transition-all duration-300 hover:scale-105 hover:shadow-lg">
                    <CardHeader class="space-y-2">
                        <div class="flex items-center justify-between">
                            <CardTitle class="text-xl font-bold text-primary">
                                {{ certificate.certificate_number }}
                            </CardTitle>
                            <div class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm font-medium">
                                Aktif
                            </div>
                        </div>
                        <CardDescription class="text-base text-gray-700 font-medium">
                            {{ certificate.scheme.name }}
                        </CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div class="flex items-center text-gray-600">
                                <CalendarCheck class="h-5 w-5 mr-2" />
                                <span>Diterbitkan: {{ formatDate(certificate.issued_at) }}</span>
                            </div>
                            <div v-if="certificate.expiry_date" class="flex items-center text-gray-600">
                                <CalendarX class="h-5 w-5 mr-2" />
                                <span>Kadaluwarsa: {{ formatDate(certificate.expiry_date) }}</span>
                            </div>

                            <div class="pt-4 flex flex-col sm:flex-row gap-3">
                                <Link :href="route('asesi.certificates.show', certificate.id)" 
                                      class="flex-1">
                                    <Button size="lg" 
                                            variant="default" 
                                            class="w-full flex items-center justify-center gap-2">
                                        <Eye class="h-4 w-4" />
                                        Lihat Detail
                                    </Button>
                                </Link>
                                <Link v-if="certificate.file_path" 
                                      :href="route('asesi.certificates.download', certificate.id)" 
                                      target="_blank"
                                      class="flex-1">
                                    <Button size="lg" 
                                            variant="outline" 
                                            class="w-full flex items-center justify-center gap-2">
                                        <Download class="h-4 w-4" />
                                        Unduh File
                                    </Button>
                                </Link>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div v-else class="text-center py-16 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <FileText class="mx-auto h-12 w-12 text-gray-400" />
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Sertifikat</h3>
                <p class="mt-2 text-gray-600">Anda belum memiliki sertifikat yang terdaftar dalam sistem.</p>
                <Link :href="route('home')" class="mt-6 inline-block">
                    <Button variant="default" size="lg" class="flex items-center gap-2">
                        <ArrowLeft class="h-4 w-4" />
                        Kembali ke Beranda
                    </Button>
                </Link>
            </div>

            <div v-if="props.certificates.links.length > 3" 
                 class="flex justify-center mt-12">
                <nav class="flex items-center gap-2">
                    <template v-for="(link, key) in props.certificates.links" :key="key">
                        <div v-if="link.url === null" 
                             class="px-4 py-2 text-sm text-gray-500 bg-gray-100 rounded-md"
                             v-html="link.label" />
                        <Link v-else
                              class="px-4 py-2 text-sm rounded-md transition-colors"
                              :class="{
                                'bg-primary text-white': link.active,
                                'bg-gray-100 text-gray-700 hover:bg-gray-200': !link.active
                              }"
                              :href="link.url!"
                              v-html="link.label" />
                    </template>
                </nav>
            </div>
        </div>
    </div>

    <Footer />
</template>