<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { TableRow, TableCell } from '@/components/ui/table';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import SearchComponent from '@/components/SearchComponent.vue';
import DataTableComponent from '@/components/DataTableComponent.vue';
import { computed, ref } from 'vue';
import { BreadcrumbItem } from '@/types';

const props = defineProps<{
  attempts: Array<{
    id: number;
    user_name: string;
    scheme_name: string;
    status: string;
    created_at: string;
  }>;
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
];

const searchQuery = ref('');

const filteredAttempts = computed(() => {
    if (!searchQuery.value) return props.attempts;
    
    const query = searchQuery.value.toLowerCase();
    return props.attempts.filter(attempt => 
        attempt.user_name.toLowerCase().includes(query) || 
        attempt.scheme_name.toLowerCase().includes(query) ||
        attempt.status.toLowerCase().includes(query)
    );
});

/**
 * Determine if we should show the empty search message
 */
 const showEmptySearchMessage = computed(() => {
    return searchQuery.value.length > 0 && filteredAttempts.value.length === 0;
});

/**
 * Determine if we should show the empty data message
 */
const showEmptyDataMessage = computed(() => {
    return props.attempts.length === 0;
});

const tableHeaders = ['Nama Asesi', 'Skema', 'Status', 'Tanggal Pendaftaran', ''];
</script>

<template>
    <Head title="Daftar Pendaftaran" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Daftar Pendaftaran" description="Kelola pendaftaran yang telah disubmit oleh Asesi" />
                    <CardContent>
                        <div class="mb-6 max-w-d">
                            <SearchComponent 
                                v-model="searchQuery"
                                placeholder="Cari pendaftaran" />
                        </div>

                        <!-- Tampilkan pesan pencarian kosong -->
                        <div v-if="showEmptySearchMessage" class="text-center py-8 text-gray-500">
                            Tidak ada pendaftaran yang cocok dengan pencarian Anda.
                        </div>

                        <DataTableComponent
                            v-else
                            :headers="tableHeaders"
                            :items="props.attempts"
                        >
                            <TableRow v-for="attempt in filteredAttempts" :key="attempt.id">
                                <TableCell>{{ attempt.user_name }}</TableCell>
                                <TableCell>{{ attempt.scheme_name }}</TableCell>
                                <TableCell>
                                    <span
                                        :class="{
                                            'text-green-600 dark:text-green-400': attempt.status === 'approved',
                                            'text-red-600 dark:text-red-400': attempt.status === 'rejected',
                                            'text-yellow-600 dark:text-yellow-400': attempt.status === 'submitted',
                                        }"
                                        class="font-semibold"
                                    >
                                        {{ attempt.status }}
                                    </span>
                                </TableCell>
                                <TableCell>{{ new Date(attempt.created_at).toLocaleDateString() }}</TableCell>
                                <TableCell class="text-right">
                                    <Link :href="route('attempts.show', attempt.id)" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Show">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
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