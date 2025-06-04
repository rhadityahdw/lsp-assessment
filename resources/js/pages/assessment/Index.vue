<script setup>
import PageHeaderComponent from '@/components/PageHeaderComponent.vue'
import { Card, CardContent } from '@/components/ui/card'
import { Table, TableHead, TableHeader, TableRow, TableBody, TableCell } from '@/components/ui/table'
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

const props = defineProps({
  schedules: Object,
  filters: Object
})

const breadcrumbs = [
  { title: 'Dashboard', href: route('dashboard') },
  { title: 'Penilaian Asesmen', href: route('asesor.grading.index') }
]

const formatDateTime = (dateTime) => {
  return new Date(dateTime).toLocaleString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

const getGradingProgress = (schedule) => {
  const totalAsesi = schedule.asesi_schedules?.length || 0
  const gradedAsesi = schedule.asesi_schedules?.filter(as => as.status !== 'pending').length || 0
  return { gradedAsesi, totalAsesi }
}

const getProgressBadgeClass = (schedule) => {
  const { gradedAsesi, totalAsesi } = getGradingProgress(schedule)
  if (totalAsesi === 0) return 'bg-gray-100 text-gray-800'
  if (gradedAsesi === 0) return 'bg-red-100 text-red-800'
  if (gradedAsesi === totalAsesi) return 'bg-green-100 text-green-800'
  return 'bg-yellow-100 text-yellow-800'
}
</script>

<template>
    <Head title="Penilaian Asesmen" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-4 sm:py-6 md:py-12">
            <div class="mx-auto max-w-full px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Penilaian Asesmen" />
                    <CardContent class="p-4 sm:p-6">
                        <!-- Stats Cards - Responsive Grid -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                            <Card>
                                <CardContent class="flex items-center p-4">
                                    <div class="p-2 bg-blue-100 rounded-lg flex-shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    
                                    <div class="ml-3 sm:ml-4 min-w-0">
                                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Total Asesmen</p>
                                        <p class="text-xl sm:text-2xl font-bold text-gray-900">{{ schedules.data?.length || 0 }}</p>
                                    </div>
                                </CardContent>
                            </Card>

                            <Card>
                                <CardContent class="flex items-center p-4">
                                    <div class="p-2 bg-yellow-100 rounded-lg flex-shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    
                                    <div class="ml-3 sm:ml-4 min-w-0">
                                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Menunggu Penilaian</p>
                                        <p class="text-xl sm:text-2xl font-bold text-gray-900">
                                            {{ schedules.data?.filter(s => s.asesi_schedules?.some(as => as.status === 'pending')).length || 0 }}
                                        </p>
                                    </div>
                                </CardContent>
                            </Card>

                            <Card class="sm:col-span-2 lg:col-span-1">
                                <CardContent class="flex items-center p-4">
                                    <div class="p-2 bg-green-100 rounded-lg flex-shrink-0">
                                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 sm:ml-4 min-w-0">
                                        <p class="text-xs sm:text-sm font-medium text-gray-600 truncate">Selesai Dinilai</p>
                                        <p class="text-xl sm:text-2xl font-bold text-gray-900">
                                            {{ schedules.data?.filter(s => s.asesi_schedules?.every(as => as.status !== 'pending')).length || 0 }}
                                        </p>
                                    </div>
                                </CardContent>
                            </Card>
                        </div>

                        <!-- Table Section -->
                        <Card>
                            <PageHeaderComponent title="Daftar Jadwal Asesmen" />
                            <CardContent class="p-0">
                                <!-- Mobile Card View (Hidden on larger screens) -->
                                <div class="block lg:hidden">
                                    <div v-if="!schedules.data || schedules.data.length === 0" class="p-6 text-center text-gray-500">
                                        Tidak ada jadwal asesmen yang perlu dinilai
                                    </div>
                                    <div v-else class="divide-y divide-gray-200">
                                        <div v-for="schedule in schedules.data" :key="schedule.id" class="p-4 hover:bg-gray-50">
                                            <div class="space-y-3">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ schedule.assessment?.name || 'N/A' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        {{ schedule.assessment?.scheme?.name || 'N/A' }}
                                                    </div>
                                                </div>
                                                
                                                <div class="grid grid-cols-2 gap-3 text-xs">
                                                    <div>
                                                        <span class="text-gray-500">Waktu:</span>
                                                        <div class="text-gray-900 font-medium">{{ formatDateTime(schedule.schedule_time) }}</div>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Lokasi:</span>
                                                        <div class="text-gray-900 font-medium">{{ schedule.location || 'Tidak ditentukan' }}</div>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Asesi:</span>
                                                        <div class="text-gray-900 font-medium">{{ schedule.asesi_schedules?.length || 0 }} orang</div>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-500">Progress:</span>
                                                        <div>
                                                            <span :class="['inline-flex items-center px-2 py-1 rounded-full text-xs font-medium', getProgressBadgeClass(schedule)]">
                                                                {{ getGradingProgress(schedule).gradedAsesi }}/{{ getGradingProgress(schedule).totalAsesi }} dinilai
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="pt-2">
                                                    <Link 
                                                        :href="route('asesor.grading.show', schedule.id)"
                                                        class="inline-flex items-center justify-center w-full px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                    >
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Nilai
                                                    </Link>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Table View (Hidden on mobile) -->
                                <div class="hidden lg:block overflow-x-auto">
                                    <Table>
                                        <TableHeader>
                                            <TableRow>
                                                <TableHead class="text-left">Asesmen</TableHead>
                                                <TableHead class="text-left">Waktu</TableHead>
                                                <TableHead class="text-left">Lokasi</TableHead>
                                                <TableHead class="text-left">Jumlah Asesi</TableHead>
                                                <TableHead class="text-left">Progress</TableHead>
                                                <TableHead class="text-left">Aksi</TableHead>
                                            </TableRow>
                                        </TableHeader>
                                        <TableBody>
                                            <TableRow v-for="schedule in schedules.data" :key="schedule.id" class="hover:bg-gray-50">
                                                <TableCell>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ schedule.assessment?.name || 'N/A' }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ schedule.assessment?.scheme?.name || 'N/A' }}
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <div class="text-sm text-gray-900">
                                                        {{ formatDateTime(schedule.schedule_time) }}
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <div class="text-sm text-gray-900">
                                                        {{ schedule.location || 'Tidak ditentukan' }}
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <div class="text-sm text-gray-900">
                                                        {{ schedule.asesi_schedules?.length || 0 }} orang
                                                    </div>
                                                </TableCell>
                                                <TableCell>
                                                    <span :class="['inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium', getProgressBadgeClass(schedule)]">
                                                        {{ getGradingProgress(schedule).gradedAsesi }}/{{ getGradingProgress(schedule).totalAsesi }} dinilai
                                                    </span>
                                                </TableCell>
                                                <TableCell>
                                                    <Link 
                                                        :href="route('asesor.grading.show', schedule.id)"
                                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                                    >
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                        </svg>
                                                        Nilai
                                                    </Link>
                                                </TableCell>
                                            </TableRow>
                                            <TableRow v-if="!schedules.data || schedules.data.length === 0">
                                                <TableCell colspan="6" class="text-center text-gray-500">
                                                    Tidak ada jadwal asesmen yang perlu dinilai
                                                </TableCell>
                                            </TableRow>
                                        </TableBody>
                                    </Table>
                                </div>
                            </CardContent>
                        </Card>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>