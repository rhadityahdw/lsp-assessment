<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { TableRow, TableCell } from '@/components/ui/table';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import SearchComponent from '@/components/SearchComponent.vue';
import ActionButtonsComponent from '@/components/ActionButtonsComponent.vue';
import { BreadcrumbItem, Scheme } from '@/types';
import { computed, ref } from 'vue';
import DataTableComponent from '@/components/DataTableComponent.vue';

const props = defineProps<{
    schemes: Scheme[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Skema',
        href: route('schemes.index'),
    }
]

const searchQuery = ref('');

const filteredSchemes = computed(() => {
    if (!searchQuery.value) return props.schemes;
    
    const query = searchQuery.value.toLowerCase();
    return props.schemes.filter(scheme => 
        scheme.code.toLowerCase().includes(query) || 
        scheme.name.toLowerCase().includes(query)
    );
});

/**
 * Determine if we should show the empty search message
 */
 const showEmptySearchMessage = computed(() => {
    return searchQuery.value.length > 0 && filteredSchemes.value.length === 0;
});

/**
 * Determine if we should show the empty data message
 */
const showEmptyDataMessage = computed(() => {
    return props.schemes.length === 0;
});

const tableHeaders = ['Nama Skema', 'Kode', 'Tipe', 'Jumlah Unit', ''];
</script>

<template>
    <Head title="Daftar Unit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Daftar Skema" description="Kelola skema sertifikasi">
                        <Link :href="route('schemes.create')">
                            <Button>Tambah Skema</Button>
                        </Link>
                    </PageHeaderComponent>
                    <CardContent>
                        <div class="mb-6 max-w-d">
                            <SearchComponent 
                                v-model="searchQuery"
                                placeholder="Cari skema" />
                        </div>

                        <!-- Tampilkan pesan pencarian kosong -->
                        <div v-if="showEmptySearchMessage" class="text-center py-8 text-gray-500">
                            Tidak ada skema yang cocok dengan pencarian Anda.
                        </div>

                        <!-- Tampilkan pesan data kosong -->
                        <div v-else-if="showEmptyDataMessage" class="text-center py-8 text-gray-500">
                            Belum ada skema yang terdaftar. Silakan tambahkan skema baru.
                        </div>

                        <DataTableComponent
                            v-else
                            :headers="tableHeaders"
                            :items="filteredSchemes"
                        >
                            <TableRow v-for="scheme in filteredSchemes" :key="scheme.id">
                                <TableCell>{{ scheme.code }}</TableCell>
                                <TableCell>{{ scheme.name }}</TableCell>
                                <TableCell>{{ scheme.type }}</TableCell>
                                <TableCell>{{ (scheme as any).units .length }}</TableCell>
                                <TableCell class="text-right">
                                    <ActionButtonsComponent
                                        :show-route="route('schemes.show', scheme.id)"
                                        :edit-route="route('schemes.edit', scheme.id)"
                                        :delete-route="route('schemes.destroy', scheme.id)"
                                        delete-message="Apakah Anda yakin ingin menghapus unit ini?"
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