<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Link } from '@inertiajs/vue3';
import { Users, FileText, Calendar, Award, CheckCircle, Clock, AlertCircle } from 'lucide-vue-next';

interface Props {
    dashboardData: any;
    userRole: string;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

const formatDate = (date: string) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    });
};

const getStatusColor = (status: string) => {
    switch (status) {
        case 'scheduled': return 'bg-blue-100 text-blue-800';
        case 'completed': return 'bg-green-100 text-green-800';
        case 'approved': return 'bg-green-100 text-green-800';
        case 'submitted': return 'bg-yellow-100 text-yellow-800';
        case 'rejected': return 'bg-red-100 text-red-800';
        default: return 'bg-gray-100 text-gray-800';
    }
};
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
            <!-- Admin Dashboard -->
            <div v-if="userRole === 'admin'" class="space-y-6">
                <!-- Stats Cards -->
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-5">
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Total Users</CardTitle>
                            <Users class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.totalUsers }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Total Schemes</CardTitle>
                            <FileText class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.totalSchemes }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Total Assessments</CardTitle>
                            <FileText class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.totalAssessments }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Active Schedules</CardTitle>
                            <Calendar class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.activeSchedules }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Certificates Issued</CardTitle>
                            <Award class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.certificatesIssued }}</div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Recent Activities -->
                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Recent Users -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Recent Users</CardTitle>
                            <CardDescription>Latest registered users</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div v-for="user in dashboardData.recentUsers" :key="user.id" class="flex items-center space-x-4">
                                    <div class="flex-1 space-y-1">
                                        <p class="text-sm font-medium">{{ user.name }}</p>
                                        <p class="text-xs text-muted-foreground">{{ user.email }}</p>
                                    </div>
                                    <div class="text-xs text-muted-foreground">
                                        {{ formatDate(user.created_at) }}
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Pending Attempts -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Pending Attempts</CardTitle>
                            <CardDescription>Attempts waiting for verification</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div v-for="attempt in dashboardData.pendingAttempts" :key="attempt.id" class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">{{ attempt.user.name }}</p>
                                        <p class="text-xs text-muted-foreground">{{ attempt.scheme.name }}</p>
                                    </div>
                                    <Badge :class="getStatusColor(attempt.status)">{{ attempt.status }}</Badge>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Asesor Dashboard -->
            <div v-else-if="userRole === 'asesor'" class="space-y-6">
                <!-- Stats Cards -->
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Total Asesmen</CardTitle>
                            <FileText class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.totalAssessments }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Jadwal Aktif</CardTitle>
                            <Calendar class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.activeSchedules }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Selesai Dinilai</CardTitle>
                            <CheckCircle class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.completedSchedules }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card class="border-orange-200 bg-orange-50">
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium text-orange-800">Perlu Dinilai</CardTitle>
                            <AlertCircle class="h-4 w-4 text-orange-600" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold text-orange-800">{{ dashboardData.stats.pendingGrading }}</div>
                            <Link :href="route('asesor.grading.index')" 
                                  class="text-xs text-orange-600 hover:text-orange-800 underline mt-1 block">
                                Lihat semua →
                            </Link>
                        </CardContent>
                    </Card>
                </div>

                <!-- Quick Actions -->
                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Pending Grading -->
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between">
                            <div>
                                <CardTitle>Asesmen Perlu Dinilai</CardTitle>
                                <CardDescription>Jadwal yang sudah selesai dan menunggu penilaian</CardDescription>
                            </div>
                            <Link :href="route('asesor.grading.index')" as-child>
                                <Button variant="outline" size="sm">
                                    Lihat Semua
                                </Button>
                            </Link>
                        </CardHeader>
                        <CardContent>
                            <div v-if="dashboardData.pendingGradingSchedules?.length > 0" class="space-y-3">
                                <div v-for="schedule in dashboardData.pendingGradingSchedules" :key="schedule.id" 
                                     class="flex items-center justify-between p-3 border rounded-lg hover:bg-gray-50">
                                    <div class="flex-1">
                                        <div class="font-medium">{{ schedule.assessment?.name || 'Asesmen' }}</div>
                                        <div class="text-sm text-gray-500">{{ formatDate(schedule.schedule_time) }}</div>
                                        <div class="text-xs text-gray-400">{{ schedule.asesis?.length || 0 }} asesi</div>
                                    </div>
                                    <Link :href="route('asesor.grading.show', schedule.id)" as-child>
                                        <Button size="sm" class="ml-2">
                                            Nilai
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                            <div v-else class="text-center py-4 text-gray-500">
                                Tidak ada asesmen yang perlu dinilai
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Upcoming Schedules -->
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between">
                            <div>
                                <CardTitle>Jadwal Mendatang</CardTitle>
                                <CardDescription>Asesmen yang akan datang</CardDescription>
                            </div>
                            <Link :href="route('schedules.index')" as-child>
                                <Button variant="outline" size="sm">
                                    Lihat Semua
                                </Button>
                            </Link>
                        </CardHeader>
                        <CardContent>
                            <div v-if="dashboardData.upcomingSchedules?.length > 0" class="space-y-3">
                                <div v-for="schedule in dashboardData.upcomingSchedules" :key="schedule.id" 
                                     class="flex items-center justify-between p-3 border rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium">{{ schedule.assessment?.name || 'Asesmen' }}</div>
                                        <div class="text-sm text-gray-500">{{ formatDate(schedule.schedule_time) }}</div>
                                        <div class="text-xs text-gray-400">{{ schedule.location }}</div>
                                    </div>
                                    <Badge :class="getStatusColor(schedule.status)">
                                        {{ schedule.status === 'scheduled' ? 'Terjadwal' : schedule.status }}
                                    </Badge>
                                </div>
                            </div>
                            <div v-else class="text-center py-4 text-gray-500">
                                Tidak ada jadwal mendatang
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Asesi Dashboard -->
            <div v-else-if="userRole === 'asesi'" class="space-y-6">
                <!-- Stats Cards -->
                <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Total Attempts</CardTitle>
                            <FileText class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.totalAttempts }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Approved</CardTitle>
                            <CheckCircle class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.approvedAttempts }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Pending</CardTitle>
                            <Clock class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.pendingAttempts }}</div>
                        </CardContent>
                    </Card>
                    
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                            <CardTitle class="text-sm font-medium">Certificates</CardTitle>
                            <Award class="h-4 w-4 text-muted-foreground" />
                        </CardHeader>
                        <CardContent>
                            <div class="text-2xl font-bold">{{ dashboardData.stats.certificates }}</div>
                        </CardContent>
                    </Card>
                </div>

                <!-- Upcoming Schedules & Recent Attempts -->
                <div class="grid gap-4 md:grid-cols-2">
                    <!-- Upcoming Schedules -->
                    <Card>
                        <CardHeader>
                            <CardTitle>Upcoming Assessments</CardTitle>
                            <CardDescription>Your scheduled assessments</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div v-for="schedule in dashboardData.upcomingSchedules" :key="schedule.id" class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">{{ schedule.assessment.name }}</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(schedule.schedule_time) }}</p>
                                        <p class="text-xs text-muted-foreground">Asesor: {{ schedule.asesor.name }}</p>
                                    </div>
                                    <Link :href="route('asesi.assessments.show', schedule.id)">
                                        <Button variant="outline" size="sm">View</Button>
                                    </Link>
                                </div>
                            </div>
                        </CardContent>
                    </Card>

                    <!-- Recent Certificates -->
                    <Card>
                        <CardHeader>
                            <CardTitle>My Certificates</CardTitle>
                            <CardDescription>Recently issued certificates</CardDescription>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div v-for="certificate in dashboardData.certificates" :key="certificate.id" class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium">{{ certificate.scheme.name }}</p>
                                        <p class="text-xs text-muted-foreground">{{ certificate.certificate_number }}</p>
                                        <p class="text-xs text-muted-foreground">{{ formatDate(certificate.issue_date) }}</p>
                                    </div>
                                    <Link :href="route('certificates.show', certificate.id)">
                                        <Button variant="outline" size="sm">View</Button>
                                    </Link>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
