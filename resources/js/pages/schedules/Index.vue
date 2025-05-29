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

interface Schedule {
    id: number;
    asesor_id: number;
    schedule_time: Date;
    location?: string | null;
    status: string;
    created_at: Date;
    updated_at: Date;
}

const props = defineProps<{
    schedules: Schedule[];
}>();

console.log(props.schedules)

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
        schedule.status.toLowerCase().includes(query)
    );
})

const showEmptySearchMessage = computed(() => {
    return searchQuery.value.length > 0 && filteredSchedules.value.length === 0;
})

const showEmptyDataMessage = computed(() => {
    return props.schedules.length === 0;
});

const tableHeaders = ['Nama Jadwal', 'Skema', 'Nama Asesor', 'Tanggal Pelaksanaan', '']
</script>

<template>
    <Head title=""/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Daftar Jadwal" description="Kelola jadwal yang telah">
                        <Link :href="route('schedules.create')">
                            <Button>Tambah Jadwal</Button>
                        </Link>
                    </PageHeaderComponent>
                    <CardContent>
                        <div class="mb-6 max-w-d">
                            <SearchComponent 
                                v-model="searchQuery"
                                placeholder="Cari jadwal" />
                        </div>

                        <div v-if="showEmptySearchMessage" class="text-center py-8 text-gray-500">
                            Tidak ada jadwal yang cocok dengan pencarian Anda.
                        </div>

                        <div v-else-if="showEmptyDataMessage" class="text-center py-8 text-gray-500">
                            Belum ada jadwal yang terdaftar. Silakan tunggu asesi melakukan pendaftaran baru.
                        </div>

                        <DataTableComponent
                            v-else
                            :headers="tableHeaders"
                            :items="filteredSchedules"
                        >
                            <TableRow v-for="schedule in schedules" :key="schedule.id">
                                <TableCell>{{ schedule.id }}</TableCell>
                                <TableCell>{{ schedule.schedule_time }}</TableCell>
                                <TableCell>{{ schedule.asesor_id }}</TableCell>
                                <TableCell>{{ schedule.asesor_id }}</TableCell>
                                <TableCell class="text-right">
                                    <!-- <ActionButtonsComponent 
                                        :show-route="route('schedules.show', schedule.id)"
                                        :edit-route="route('schedules.edit', schedule.id)"
                                        :delete-route="route('schedules.destroy', schedule.id)"
                                        delete-message="Apakah anda yakin ingin menghapus jadwal ini?"
                                    /> -->
                                </TableCell>
                            </TableRow>
                        </DataTableComponent>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>