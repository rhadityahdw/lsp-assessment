<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import SearchComponent from '@/components/SearchComponent.vue';
import { BreadcrumbItem } from '@/types';
import { computed, ref } from 'vue';
import DataTableComponent from '@/components/DataTableComponent.vue';
import { TableCell, TableRow } from '@/components/ui/table';
import ActionButtonsComponent from '@/components/ActionButtonsComponent.vue';

const props = defineProps<{
    schedules: {
        assessment: {
            id: number;
            name: string;
        };
        asesi: [];
        asesor: {
            id: number;
            name: string;
        };
        id: number;
        location: string;
        schedule_time: Date;
        status: string;
    }[];
}>();

console.log(props.schedules);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Jadwal',
        href: route('schedules.index'),
    }
];

const searchQuery = ref('');

const filteredSchedules = computed(() => {
    if (!searchQuery.value) return props.schedules;

    const query = searchQuery.value.toLowerCase();
    return props.schedules.filter(schedule =>
        schedule.status.toLowerCase().includes(query) ||
        schedule.assessment.name.toLowerCase().includes(query) ||
        schedule.asesor.name.toLowerCase().includes(query) ||
        schedule.location.toLowerCase().includes(query)
    );
})

const showEmptySearchMessage = computed(() => {
    return searchQuery.value.length > 0 && filteredSchedules.value.length === 0;
})

const tableHeaders = [ 'Tanggal', 'Nama Asesmen', 'Nama Asesor', 'Lokasi', 'Status', '']
</script>

<template>
    <Head title="Daftar Jadwal"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Daftar Jadwal" description="Kelola jadwal yang telah dibuat">
                        <Link :href="route('schedules.create')">
                            <Button>Tambah Jadwal</Button>
                        </Link>
                    </PageHeaderComponent>
                    <CardContent>
                        <div class="mb-6 max-w-md">
                            <SearchComponent 
                                v-model="searchQuery"
                                placeholder="Cari jadwal" />
                        </div>

                        <div v-if="showEmptySearchMessage" class="text-center py-8 text-gray-500">
                            Tidak ada jadwal yang cocok dengan pencarian Anda.
                        </div>

                        <DataTableComponent
                            v-else
                            :headers="tableHeaders"
                            :items="filteredSchedules"
                        >
                            <TableRow v-for="schedule in filteredSchedules" :key="schedule.id">
                                <TableCell>{{ new Date(schedule.schedule_time).toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric'}) }}</TableCell>
                                <TableCell>{{ schedule.assessment.name }}</TableCell>
                                <TableCell>{{ schedule.asesor.name }}</TableCell>
                                <TableCell>{{ schedule.location }}</TableCell>
                                <TableCell>
                                    <span :class="{
                                        'px-2 py-1 rounded-full text-xs font-medium': true,
                                        'bg-yellow-100 text-yellow-800': schedule.status === 'scheduled',
                                        'bg-green-100 text-green-800': schedule.status === 'completed',
                                        'bg-red-100 text-red-800': schedule.status === 'cancelled',
                                        'bg-gray-100 text-gray-800': schedule.status === 'draft'
                                    }">
                                        {{ schedule.status }}
                                    </span>
                                </TableCell>
                                <TableCell class="text-right">
                                    <ActionButtonsComponent 
                                        :show-route="route('schedules.show', schedule.id)"
                                        :edit-route="route('schedules.edit', schedule.id)"
                                        :delete-route="route('schedules.destroy', schedule.id)"
                                        delete-message="Apakah anda yakin ingin menghapus jadwal ini?"
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