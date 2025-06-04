<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Navbar from '@/components/Navbar.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { CalendarIcon, MapPinIcon, UserIcon, ExternalLinkIcon, ArrowLeftIcon } from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';

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
    asesis: Array<{
        id: number;
        name: string;
        pivot: {
            score?: number;
            notes?: string;
        };
    }>;
}

const props = defineProps<{
    schedule: Schedule;
    canAccessAssessment: boolean;
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
</script>

<template>
    <Head :title="`Asesmen - ${schedule.assessment.name}`" />
    
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Back Button -->
            <div class="mb-6">
                <Link :href="route('asesi.assessments.index')">
                    <Button variant="outline" class="flex items-center gap-2">
                        <ArrowLeftIcon class="h-4 w-4" />
                        Kembali ke Daftar Asesmen
                    </Button>
                </Link>
            </div>

            <!-- Assessment Details -->
            <Card class="mb-6">
                <CardHeader>
                    <div class="flex justify-between items-start">
                        <div>
                            <CardTitle class="text-2xl font-bold text-gray-900">
                                {{ schedule.assessment.name }}
                            </CardTitle>
                            <p class="text-gray-600 mt-1">Tipe: {{ schedule.assessment.type }}</p>
                        </div>
                        <Badge :class="getStatusColor(schedule.status)">
                            {{ getStatusText(schedule.status) }}
                        </Badge>
                    </div>
                </CardHeader>
                <CardContent class="space-y-6">
                    <!-- Schedule Information -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <CalendarIcon class="h-5 w-5 text-gray-500" />
                                <div>
                                    <p class="font-medium text-gray-900">Waktu Asesmen</p>
                                    <p class="text-gray-600">{{ new Date(schedule.schedule_time).toLocaleString('id-ID', { 
                                        day: 'numeric', 
                                        month: 'long', 
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    }) }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <MapPinIcon class="h-5 w-5 text-gray-500" />
                                <div>
                                    <p class="font-medium text-gray-900">Lokasi</p>
                                    <p class="text-gray-600">{{ schedule.location }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <UserIcon class="h-5 w-5 text-gray-500" />
                                <div>
                                    <p class="font-medium text-gray-900">Asesor</p>
                                    <p class="text-gray-600">{{ schedule.asesor.name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Assessment Access -->
                    <div class="border-t pt-6">
                        <div v-if="canAccessAssessment" class="text-center space-y-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <p class="text-green-800 font-medium">Asesmen sudah dapat diakses!</p>
                                <p class="text-green-600 text-sm mt-1">Klik tombol di bawah untuk memulai asesmen Anda.</p>
                            </div>
                            <a :href="schedule.assessment.link" target="_blank" rel="noopener noreferrer">
                                <Button size="lg" class="flex items-center gap-2">
                                    <ExternalLinkIcon class="h-5 w-5" />
                                    Mulai Asesmen
                                </Button>
                            </a>
                        </div>
                        <div v-else class="text-center space-y-4">
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <p class="text-yellow-800 font-medium">Asesmen belum dapat diakses</p>
                                <p class="text-yellow-600 text-sm mt-1">Silakan tunggu hingga waktu yang telah ditentukan.</p>
                            </div>
                            <Button disabled size="lg" variant="outline">
                                Belum Waktunya
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </div>
</template>