<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import { BreadcrumbItem } from '@/types';
import { CalendarIcon, MapPinIcon, UserIcon, UsersIcon } from 'lucide-vue-next';
import { computed } from 'vue';

const props = defineProps<{
    schedule: {
        data: {
            id: number;
        assessment: {
            id: number;
            name: string;
            type: string;
        };
        asesor: {
            id: number;
            name: string;
        };
        asesis: {
            id: number;
            name: string;
            pivot?: {
                score?: number;
                notes?: string;
            };
        }[];
        schedule_time: string;
        location: string;
        status: string;
        created_at: string;
        updated_at: string;
        }
    };
}>();

console.log(props.schedule.data); // Add this line to check the props data in the console

const schedule = computed(() => props.schedule.data);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard')
    },
    {
        title: 'Jadwal',
        href: route('schedules.index')
    },
    {
        title: 'Detail Jadwal',
        href: route('schedules.show', props.schedule.data.id)
    }
];

const getStatusColor = (status: string) => {
    switch (status) {
        case 'scheduled':
            return 'bg-yellow-100 text-yellow-800';
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'cancelled':
            return 'bg-red-100 text-red-800';
        case 'draft':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    });
};
</script>

<template>
    <Head :title="`Detail Jadwal - ${schedule.assessment.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <div class="space-y-6">
                    <!-- Header -->
                    <Card>
                        <PageHeaderComponent 
                            :title="`Detail Jadwal - ${schedule.assessment.name}`" 
                            description="Informasi lengkap tentang jadwal asesmen"
                        >
                            <div class="flex space-x-2">
                                <Link :href="route('schedules.edit', schedule.id)">
                                    <Button variant="outline">Edit Jadwal</Button>
                                </Link>
                                <Link :href="route('schedules.index')">
                                    <Button>Kembali</Button>
                                </Link>
                            </div>
                        </PageHeaderComponent>
                    </Card>

                    <!-- Schedule Information -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Basic Information -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center space-x-2">
                                    <CalendarIcon class="h-5 w-5" />
                                    <span>Informasi Jadwal</span>
                                </CardTitle>
                            </CardHeader>
                            <CardContent class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm font-medium text-gray-500">Status:</span>
                                    <Badge :class="getStatusColor(schedule.status)">
                                        {{ schedule.status }}
                                    </Badge>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-500">Waktu Pelaksanaan:</span>
                                    <span class="text-sm text-right">{{ formatDateTime(schedule.schedule_time) }}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-500 flex items-center">
                                        <MapPinIcon class="h-4 w-4 mr-1" />
                                        Lokasi:
                                    </span>
                                    <span class="text-sm text-right">{{ schedule.location }}</span>
                                </div>
                                <div class="flex justify-between items-start">
                                    <span class="text-sm font-medium text-gray-500">Tipe Asesmen:</span>
                                    <span class="text-sm text-right">{{ schedule.assessment.type }}</span>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Assessor Information -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center space-x-2">
                                    <UserIcon class="h-5 w-5" />
                                    <span>Asesor</span>
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="flex items-center space-x-3">
                                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <UserIcon class="h-5 w-5 text-blue-600" />
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ schedule.asesor.name }}</p>
                                        <p class="text-sm text-gray-500">Asesor</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Asesi List -->
                    <Card>
                        <CardHeader>
                            <CardTitle class="flex items-center space-x-2">
                                <UsersIcon class="h-5 w-5" />
                                <span>Daftar Asesi ({{ schedule.asesis.length }})</span>
                            </CardTitle>
                        </CardHeader>
                        <CardContent>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <div 
                                    v-for="asesi in schedule.asesis" 
                                    :key="asesi.id"
                                    class="p-4 border rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    <div class="flex items-center space-x-3">
                                        <div class="h-8 w-8 rounded-full bg-green-100 flex items-center justify-center">
                                            <UserIcon class="h-4 w-4 text-green-600" />
                                        </div>
                                        <div class="flex-1">
                                            <p class="font-medium text-sm">{{ asesi.name }}</p>
                                            <div v-if="asesi.pivot?.score" class="mt-1">
                                                <p class="text-xs text-gray-500">Skor: {{ asesi.pivot.score }}</p>
                                            </div>
                                            <div v-if="asesi.pivot?.notes" class="mt-1">
                                                <p class="text-xs text-gray-500">Catatan: {{ asesi.pivot.notes }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="schedule.asesis.length === 0" class="text-center py-8 text-gray-500">
                                Belum ada asesi yang terdaftar
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Timestamps -->
                    <Card>
                        <CardContent class="pt-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-500">
                                <div>
                                    <span class="font-medium">Dibuat pada:</span>
                                    {{ formatDateTime(schedule.created_at) }}
                                </div>
                                <div>
                                    <span class="font-medium">Terakhir diperbarui:</span>
                                    {{ formatDateTime(schedule.updated_at) }}
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
