<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import SearchComponent from '@/components/SearchComponent.vue';
import { BreadcrumbItem } from '@/types';
import { computed, PropType, ref } from 'vue';
import DataTableComponent from '@/components/DataTableComponent.vue';
import { TableCell, TableRow } from '@/components/ui/table';
import ActionButtonsComponent from '@/components/ActionButtonsComponent.vue';

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

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Sertifikat',
        href: route('admin.certificates.index'),
    }
];

const searchQuery = ref('');

const filteredCertificates = computed(() => {
    if (!searchQuery.value) return certificates;

    const query = searchQuery.value.toLowerCase();
    return certificates.filter(certificate => 
        certificate.certificate_number.toLowerCase().includes(query) ||
        certificate.issued_at.toLowerCase().includes(query) ||
        certificate.expiry_date.toLowerCase().includes(query) ||
        certificate.user.name.toLowerCase().includes(query) ||
        certificate.scheme.name.toLowerCase().includes(query)
    )
});

const showEmptySearchMessage = computed(() => {
    return searchQuery.value.length > 0 && filteredCertificates.value.length === 0;
});

const tableHeaders = ['Nama Asesi', 'Skema', 'Nomor Sertifikat', 'Tanggal Terbit', 'Tanggal Kadaluwarsa', ''];
</script>

<template>
    <Head title="Daftar Sertifikat" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Daftar Sertifikat">
                        <Link :href="route('admin.certificates.create')">
                            <Button>Tambah Sertifikat</Button>
                        </Link>
                    </PageHeaderComponent>
                    <CardContent>
                        <div class="mb-6 max-w-d">
                            <SearchComponent 
                                v-model="searchQuery"
                                placeholder="Cari sertifikat" />
                        </div>

                        <!-- Tampilkan pesan pencarian kosong -->
                        <div v-if="showEmptySearchMessage" class="text-center py-8 text-gray-500">
                            Tidak ada sertifikat yang cocok dengan pencarian Anda.
                        </div>

                        <DataTableComponent
                            v-else
                            :headers="tableHeaders"
                            :items="filteredCertificates"
                        >
                            <TableRow v-for="certificate in certificates" :key="certificate.id">
                                <TableCell>{{ certificate.user.name }}</TableCell>
                                <TableCell>{{ certificate.scheme.name }}</TableCell>
                                <TableCell>{{ certificate.certificate_number }}</TableCell>
                                <TableCell>{{ certificate.issued_at }}</TableCell>
                                <TableCell>{{ certificate.expiry_date }}</TableCell>
                                <TableCell class="text-right">
                                    <ActionButtonsComponent 
                                        :show-route="route('admin.certificates.show', certificate.id)"
                                        :edit-route="route('admin.certificates.edit', certificate.id)"
                                        :delete-route="route('admin.certificates.destroy', certificate.id)"
                                        delete-message="Apakah anda yakin ingin menghapus sertifikat ini?"
                                    />
                                </TableCell>
                            </TableRow>
                        </DataTableComponent>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>