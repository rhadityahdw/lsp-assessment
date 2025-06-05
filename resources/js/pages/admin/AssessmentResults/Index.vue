<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import DataTableComponent from '@/components/DataTableComponent.vue';
import { TableCell, TableRow } from '@/components/ui/table';
import { BreadcrumbItem } from '@/types';
import { Eye, Download } from 'lucide-vue-next';
import SearchComponent from '@/components/SearchComponent.vue';
import { ref } from 'vue';

const props = defineProps<{
    schedules: any;
    filters: any;
    statusOptions: Record<string, string>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Hasil Asesmen', href: route('admin.assessment-results.index') }
];

const searchQuery = ref('');

const getStatusColor = (status: string) => {
    switch (status) {
        case 'approved': return 'bg-green-100 text-green-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getGradingProgress = (schedule: any) => {
    const total = schedule.asesi_schedules?.length || 0;
    const graded = schedule.asesi_schedules?.filter((as: any) => as.status !== 'pending').length || 0;
    return { total, graded };
};
</script>

<template>
    <Head title="Hasil Asesmen" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <Card>
                    <PageHeaderComponent 
                        title="Hasil Asesmen" 
                        description="Lihat dan kelola hasil penilaian asesmen"
                    >
                        <Button variant="outline" class="flex items-center gap-2">
                            <Download class="h-4 w-4" />
                            Export Data
                        </Button>
                    </PageHeaderComponent>
                    
                    <CardContent>
                        <!-- Filters -->
                        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <SearchComponent v-model="searchQuery" placeholder="Cari asesmen..." />
                            <!-- Add more filters here -->
                        </div>

                        <!-- Results Table -->
                        <DataTableComponent
                            :headers="['Tanggal', 'Asesmen', 'Asesor', 'Progress', 'Aksi']"
                            :items="schedules.data"
                        >
                            <TableRow v-for="schedule in schedules.data" :key="schedule.id">
                                <TableCell>
                                    {{ new Date(schedule.schedule_time).toLocaleDateString('id-ID') }}
                                </TableCell>
                                <TableCell>
                                    <div>
                                        <p class="font-medium">{{ schedule.assessment.name }}</p>
                                        <p class="text-sm text-gray-500">{{ schedule.assessment.scheme.name }}</p>
                                    </div>
                                </TableCell>
                                <TableCell>{{ schedule.asesor.name }}</TableCell>
                                <TableCell>
                                    <div class="flex items-center gap-2">
                                        <span class="text-sm">
                                            {{ getGradingProgress(schedule).graded }}/{{ getGradingProgress(schedule).total }} dinilai
                                        </span>
                                        <div class="w-16 bg-gray-200 rounded-full h-2">
                                            <div 
                                                class="bg-blue-600 h-2 rounded-full" 
                                                :style="{ width: `${(getGradingProgress(schedule).graded / getGradingProgress(schedule).total) * 100}%` }"
                                            ></div>
                                        </div>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <Link :href="route('admin.assessment-results.show', schedule.id)">
                                        <Button variant="outline" size="sm" class="flex items-center gap-2">
                                            <Eye class="h-4 w-4" />
                                            Detail
                                        </Button>
                                    </Link>
                                </TableCell>
                            </TableRow>
                        </DataTableComponent>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>