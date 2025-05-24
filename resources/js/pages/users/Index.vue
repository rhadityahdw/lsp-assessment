<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import { TableCell, TableRow } from '@/components/ui/table';
import { BreadcrumbItem, User } from '@/types';
import ActionButtonsComponent from '@/components/ActionButtonsComponent.vue';
import DataTableComponent from '@/components/DataTableComponent.vue';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import SearchComponent from '@/components/SearchComponent.vue';
import { ref, computed } from 'vue';

const props = defineProps<{
    users: User[];
}>();

console.log(props.users)

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Users', href: route('users.index') },
];

const searchQuery = ref('');

/**
 * Filter users by search query.
 */
const filteredUsers = computed(() => {
    if (!searchQuery.value) return props.users;
    
    const query = searchQuery.value.toLowerCase();
    return props.users.filter(user => 
        user.name.toLowerCase().includes(query) || 
        user.email.toLowerCase().includes(query) ||
        user.roles[0].name.toLowerCase().includes(query)
    );
});

/**
 * Determine if we should show the empty search message
 */
const showEmptySearchMessage = computed(() => {
    return searchQuery.value.length > 0 && filteredUsers.value.length === 0;
});

/**
 * Determine if we should show the empty data message
 */
const showEmptyDataMessage = computed(() => {
    return props.users.length === 0;
});

/**
 * Capitalize first letter of string.
 */
const capitalizeFirstLetter = (string: string) => {
    return string.charAt(0).toUpperCase() + string.slice(1);
};

/**
 * Badge role for user roles.
 */
const getBadgeRole = (role: string) => {
    switch (role) {
        case 'admin': return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        case 'asesi': return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        case 'asesor': return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        default: return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';
    }
}

/**
 * const tableHeaders = Table headers for users table.
 */
const tableHeaders = ['Name', 'Email', 'Role', ''];
</script>

<template>
    <Head title="Daftar Pengguna" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Daftar Pengguna">
                        <Link :href="route('users.create')">
                            <Button>Tambah User</Button>
                        </Link>
                    </PageHeaderComponent>
                    <CardContent>
                        <div class="mb-6 max-w-md">
                            <SearchComponent 
                                v-model="searchQuery"
                                placeholder="Cari berdasarkan nama, email, atau role..."
                            />
                        </div>

                        <!-- Tampilkan pesan pencarian kosong -->
                        <div v-if="showEmptySearchMessage" class="text-center py-8 text-gray-500">
                            Tidak ada pengguna yang cocok dengan pencarian Anda.
                        </div>

                        <!-- Tampilkan pesan data kosong -->
                        <div v-else-if="showEmptyDataMessage" class="text-center py-8 text-gray-500">
                            Belum ada pengguna yang terdaftar. Silakan tambahkan pengguna baru.
                        </div>

                        <!-- Tampilkan tabel jika ada data -->
                        <DataTableComponent 
                            v-else
                            :headers="tableHeaders" 
                            :items="filteredUsers"
                        >
                            <TableRow v-for="user in filteredUsers" :key="user.id">
                                <TableCell>{{ user.name }}</TableCell>
                                <TableCell>{{ user.email }}</TableCell>
                                <TableCell>
                                    <span :class="['px-2 py-1 text-xs font-medium rounded-full', getBadgeRole(user.roles[0].name)]">
                                        {{ capitalizeFirstLetter(user.roles[0].name) }}
                                    </span>
                                </TableCell>
                                <TableCell class="text-right">
                                    <ActionButtonsComponent 
                                        :edit-route="route('users.edit', user.id)" 
                                        :show-route="route('users.show', user.id)" 
                                        :delete-route="route('users.destroy', user.id)" 
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