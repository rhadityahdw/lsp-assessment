<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { BreadcrumbItem } from '@/types';
import { ArrowLeft, User, Calendar, MapPin } from 'lucide-vue-next';

const props = defineProps<{
    schedule: any;
    statusOptions: Record<string, string>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Hasil Asesmen', href: route('admin.assessment-results.index') },
    { title: 'Detail Hasil', href: '#' }
];

const getStatusColor = (status: string) => {
    switch (status) {
        case 'approved': return 'bg-green-100 text-green-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        case 'pending': return 'bg-yellow-100 text-yellow-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head :title="`Detail Hasil - ${schedule.assessment.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <!-- Back Button -->
                <div class="mb-6">
                    <Link :href="route('admin.assessment-results.index')">
                        <Button variant="outline" class="flex items-center gap-2">
                            <ArrowLeft class="h-4 w-4" />
                            Kembali ke Daftar
                        </Button>
                    </Link>
                </div>

                <!-- Assessment Info -->
                <Card class="mb-6">
                    <CardHeader>
                        <CardTitle>{{ schedule.assessment.name }}</CardTitle>
                        <p class="text-gray-600">{{ schedule.assessment.scheme.name }}</p>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="flex items-center gap-3">
                                <Calendar class="h-5 w-5 text-gray-500" />
                                <div>
                                    <p class="font-medium">Tanggal Asesmen</p>
                                    <p class="text-gray-600">{{ new Date(schedule.schedule_time).toLocaleDateString('id-ID') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <User class="h-5 w-5 text-gray-500" />
                                <div>
                                    <p class="font-medium">Asesor</p>
                                    <p class="text-gray-600">{{ schedule.asesor.name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <MapPin class="h-5 w-5 text-gray-500" />
                                <div>
                                    <p class="font-medium">Lokasi</p>
                                    <p class="text-gray-600">{{ schedule.location }}</p>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Assessment Results -->
                <Card>
                    <CardHeader>
                        <CardTitle>Hasil Penilaian Asesi</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-4">
                            <div 
                                v-for="asesiSchedule in schedule.asesi_schedules" 
                                :key="asesiSchedule.id"
                                class="border rounded-lg p-4"
                            >
                                <div class="flex justify-between items-start mb-3">
                                    <div>
                                        <h3 class="font-semibold text-lg">{{ asesiSchedule.asesi.name }}</h3>
                                        <p class="text-gray-600">{{ asesiSchedule.asesi.profile?.phone || 'No phone' }}</p>
                                    </div>
                                    <Badge :class="getStatusColor(asesiSchedule.status)">
                                        {{ statusOptions[asesiSchedule.status] }}
                                    </Badge>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="font-medium text-gray-700">Skor</p>
                                        <p class="text-2xl font-bold" :class="{
                                            'text-green-600': asesiSchedule.score >= 70,
                                            'text-red-600': asesiSchedule.score < 70 && asesiSchedule.score !== null,
                                            'text-gray-400': asesiSchedule.score === null
                                        }">
                                            {{ asesiSchedule.score || 'Belum dinilai' }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-700">Catatan Asesor</p>
                                        <p class="text-gray-600 mt-1">
                                            {{ asesiSchedule.notes || 'Tidak ada catatan' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div v-if="!schedule.asesi_schedules?.length" class="text-center py-8 text-gray-500">
                            Belum ada hasil penilaian
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>