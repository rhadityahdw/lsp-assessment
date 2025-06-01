<script setup lang="ts">
import ActionButtonsComponent from '@/components/ActionButtonsComponent.vue';
import DataTableComponent from '@/components/DataTableComponent.vue';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import SearchComponent from '@/components/SearchComponent.vue';
import { Card, CardContent } from '@/components/ui/card';
import { TableCell, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { computed, PropType, ref } from 'vue';

interface AssessmentItem {
    id: number;
    name: string;
    type: string;
    scheme: { id: number; name: string; };
    created_by: { id: number; name: string; };
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
    assessments: {
        type: Object as PropType<PaginatedData<AssessmentItem>>,
        required: true,
        default: () => ({ data: [], links: [], meta: {} }) 
    },
});

const assessments = props.assessments.data;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Asesmen',
        href: route('assessments.index'),
    }
];

const searchQuery = ref('');

const filteredAssessments = computed(() => {
    if (!searchQuery.value) return assessments;

    const query = searchQuery.value.toLowerCase();

    return assessments.filter((assessment) => {
        return (
            assessment.name.toLowerCase().includes(query) ||
            assessment.type.toLowerCase().includes(query) ||
            assessment.scheme.name.toLowerCase().includes(query) ||
            assessment.created_by.name.toLowerCase().includes(query)
        );
    });
})

const showEmptySearchMessage = computed(() => {
    return searchQuery.value.length > 0 && filteredAssessments.value.length === 0;
});

const tableHeaders = ['Judul', 'Nama Asesor', 'Skema', 'Tipe', ''];
</script>

<template>
    <Head title="Daftar Asesmen" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Daftar Asesmen" />
                
                    <CardContent>
                        <div class="mb-6 max-w-d">
                            <SearchComponent 
                                v-model="searchQuery"
                                placeholder="Cari asesmen"/>
                        </div>

                        <!-- Tampilkan pesan pencarian kosong -->
                        <div v-if="showEmptySearchMessage" class="text-center py-8 text-gray-500">
                            Tidak ada sertifikat yang cocok dengan pencarian Anda.
                        </div>

                        <DataTableComponent
                            v-else
                            :headers="tableHeaders"
                            :items="filteredAssessments"
                        >
                            <TableRow v-for="assessment in assessments" :key="assessment.id">
                                <TableCell>{{ assessment.name }}</TableCell>
                                <TableCell>{{ assessment.created_by.name }}</TableCell>
                                <TableCell>{{ assessment.scheme.name }}</TableCell>
                                <TableCell>{{ assessment.type }}</TableCell>
                                <TableCell class="text-right">
                                    <ActionButtonsComponent
                                        :show-route="route('assessments.show', assessment.id)"
                                        :edit-route="route('assessments.edit', assessment.id)"
                                        :delete-route="route('assessments.destroy', assessment.id)"
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