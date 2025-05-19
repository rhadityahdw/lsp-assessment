<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { TableCell, TableRow } from '@/components/ui/table';
import { BreadcrumbItem, Unit } from '@/types';
import ActionButtonsComponent from '@/components/ActionButtonsComponent.vue';
import DataTableComponent from '@/components/DataTableComponent.vue';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import SearchComponent from '@/components/SearchComponent.vue';
import { ref, computed } from 'vue';

const props = defineProps<{
    units: Unit[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Unit', href: route('units.index') },
];

const searchQuery = ref('');

/**
 * Filter units by search query.
 */
const filteredUnits = computed(() => {
    if (!searchQuery.value) return props.units;
    
    const query = searchQuery.value.toLowerCase();
    return props.units.filter(unit => 
        unit.code.toLowerCase().includes(query) || 
        unit.name.toLowerCase().includes(query)
    );
});

/**
 * Determine if we should show the empty search message
 */
const showEmptySearchMessage = computed(() => {
    return searchQuery.value.length > 0 && filteredUnits.value.length === 0;
});

/**
 * Determine if we should show the empty data message
 */
const showEmptyDataMessage = computed(() => {
    return props.units.length === 0;
});

const tableHeaders = ['Kode', 'Nama Unit', 'Jumlah Pertanyaan', ''];
</script>

<template>
    <Head title="Daftar Unit"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Daftar Unit" description="Kelola unit kompetensi">
                        <Link :href="route('units.create')">
                            <Button>Tambah Unit</Button>
                        </Link>
                    </PageHeaderComponent>
                    <CardContent>
                        <div class="mb-6 max-w-d">
                            <SearchComponent 
                                v-model="searchQuery" 
                                placeholder="Cari unit..." />
                        </div>

                        <!-- Tampilkan pesan pencarian kosong -->
                        <div v-if="showEmptySearchMessage" class="text-center py-8 text-gray-500">
                            Tidak ada unit yang cocok dengan pencarian Anda.
                        </div>

                        <!-- Tampilkan pesan data kosong -->
                        <div v-else-if="showEmptyDataMessage" class="text-center py-8 text-gray-500">
                            Belum ada unit yang terdaftar. Silakan tambahkan unit baru.
                        </div>

                        <DataTableComponent 
                            v-else
                            :headers="tableHeaders"
                            :items="filteredUnits"
                        >
                            <TableRow v-for="unit in filteredUnits" :key="unit.id">
                                <TableCell>{{ unit.code }}</TableCell>
                                <TableCell>{{ unit.name }}</TableCell>
                                <TableCell>{{ unit.pre_assessments ? unit.pre_assessments.length : 0 }}</TableCell>
                                <TableCell class="text-right">
                                    <ActionButtonsComponent
                                        :show-route="route('units.show', unit.id)"
                                        :edit-route="route('units.edit', unit.id)"
                                        :delete-route="route('units.destroy', unit.id)"
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