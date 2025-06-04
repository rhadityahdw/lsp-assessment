<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { CalendarIcon, MapPinIcon, UserIcon, ClockIcon } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Schedule {
    id: number;
    schedule_time: string;
    location: string;
    status: string;
    assessment: {
        id: number;
        name: string;
        type: string;
        link: string;
    };
    asesor: {
        id: number;
        name: string;
    };
}

const props = defineProps<{
    schedules: Schedule[];
}>();

const getStatusColor = (status: string) => {
    switch (status) {
        case 'scheduled': return 'bg-blue-100 text-blue-800';
        case 'completed': return 'bg-green-100 text-green-800';
        case 'cancelled': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};

const getStatusText = (status: string) => {
    switch (status) {
        case 'scheduled': return 'Terjadwal';
        case 'completed': return 'Selesai';
        case 'cancelled': return 'Dibatalkan';
        default: return status;
    }
};

const canAccessAssessment = (scheduleTime: string) => {
    return new Date() >= new Date(scheduleTime);
};
</script>

<template>
    <Head title="Asesmen Saya" />
    
    <Navbar />

    <div class="bg-gradient-to-r from-cyan-700 to-blue-900 text-white py-8 md:py-12">
        <div class="container mx-auto px-4">
            <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-2 md:mb-4">Asesmen Saya</h1>
            <p class="text-base md:text-lg max-w-2xl opacity-90">Daftar asesmen yang telah dijadwalkan untuk Anda.</p>
        </div>
    </div>
    
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            

            <!-- Assessment Cards -->
            <div v-if="schedules.length > 0" class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <Card v-for="schedule in schedules" :key="schedule.id" class="hover:shadow-lg transition-shadow">
                    <CardHeader>
                        <div class="flex justify-between items-start">
                            <CardTitle class="text-lg font-semibold text-gray-900">
                                {{ schedule.assessment.name }}
                            </CardTitle>
                            <Badge :class="getStatusColor(schedule.status)">
                                {{ getStatusText(schedule.status) }}
                            </Badge>
                        </div>
                    </CardHeader>
                    <CardContent class="space-y-4">
                        <!-- Assessment Details -->
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center gap-2">
                                <CalendarIcon class="h-4 w-4" />
                                <span>{{ new Date(schedule.schedule_time).toLocaleString('id-ID', { 
                                    day: 'numeric', 
                                    month: 'long', 
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                }) }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <MapPinIcon class="h-4 w-4" />
                                <span>{{ schedule.location }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <UserIcon class="h-4 w-4" />
                                <span>Asesor: {{ schedule.asesor.name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <ClockIcon class="h-4 w-4" />
                                <span>Tipe: {{ schedule.assessment.type }}</span>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <div class="pt-4">
                            <Link :href="route('asesi.assessments.show', schedule.id)">
                                <Button 
                                    :disabled="!canAccessAssessment(schedule.schedule_time) && schedule.status === 'scheduled'"
                                    class="w-full"
                                    :variant="canAccessAssessment(schedule.schedule_time) ? 'default' : 'outline'"
                                >
                                    <span v-if="canAccessAssessment(schedule.schedule_time)">
                                        Akses Asesmen
                                    </span>
                                    <span v-else>
                                        Belum Waktunya
                                    </span>
                                </Button>
                            </Link>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
                <div class="mx-auto h-24 w-24 text-gray-400">
                    <CalendarIcon class="h-full w-full" />
                </div>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Asesmen</h3>
                <p class="mt-2 text-gray-500">Anda belum memiliki jadwal asesmen yang terdaftar.</p>
            </div>
        </div>
    </div>
</template>