<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import { format } from 'date-fns'
import { id } from 'date-fns/locale'
import { computed } from 'vue'
import AppLayout from '@/layouts/AppLayout.vue'
import PageHeaderComponent from '@/components/PageHeaderComponent.vue'
import { Card, CardContent } from '@/components/ui/card'
import InputError from '@/components/InputError.vue'

const props = defineProps({
  schedule: Object,
  asesiSchedules: Array,
  statusOptions: Object
})

// Debug props
console.log('Props asesiSchedules:', props.asesiSchedules)
console.log('First item:', props.asesiSchedules[0])

const breadcrumbs = [
  { title: 'Dashboard', href: route('dashboard') },
  { title: 'Penilaian Asesmen', href: route('asesor.grading.index') },
  { title: 'Form Penilaian', href: '#' }
]

const form = useForm({
  results: props.asesiSchedules.map(asesiSchedule => {
    console.log('Processing asesiSchedule:', asesiSchedule) // Debug line
    return {
      id: asesiSchedule.id || asesiSchedule.pivot?.id || null, // Try multiple sources
      score: asesiSchedule.score || asesiSchedule.pivot?.score || 0,
      status: asesiSchedule.status || asesiSchedule.pivot?.status || 'pending',
      notes: asesiSchedule.notes || asesiSchedule.pivot?.notes || '',
      asesi: asesiSchedule.asesi || asesiSchedule
    }
  })
})

// Computed property for processing state
const processing = computed(() => form.processing)

function submitGrading() {
  const formData = {
    results: form.results.map(result => ({
      id: result.id,
      score: result.score,
      status: result.status,
      notes: result.notes
    }))
  }
  
  console.log('Sending data:', formData) // Debug
  
  form.transform(() => formData)
    .patch(route('asesor.grading.update-multiple', props.schedule.id), {
      onSuccess: () => {
        form.reset()
        window.location.href = route('asesor.grading.index')
      },
      onError: (errors) => {
        console.error('Validation errors:', errors)
      }
    })
}

function resetForm() {
  form.reset()
  // Reset to original values
  form.results = props.asesiSchedules.map(asesiSchedule => ({
    id: asesiSchedule.id, 
    score: asesiSchedule.score || 0,
    status: asesiSchedule.status || 'pending',
    notes: asesiSchedule.notes || '',
    asesi: asesiSchedule.asesi
  }))
}

function formatDateTime(dateTime) {
  if (!dateTime) return 'N/A'
  try {
    return format(new Date(dateTime), 'dd MMMM yyyy HH:mm', { locale: id })
  } catch (error) {
    return 'Invalid Date'
  }
}

function getStatusText(status) {
  const statusMap = {
    'pending': 'Menunggu Penilaian',
    'approved': 'Lulus',
    'rejected': 'Tidak Lulus'
  }
  return statusMap[status] || status
}

function getStatusBadgeClass(status) {
  const classMap = {
    'pending': 'bg-yellow-100 text-yellow-800',
    'approved': 'bg-green-100 text-green-800',
    'rejected': 'bg-red-100 text-red-800'
  }
  return classMap[status] || 'bg-gray-100 text-gray-800'
}
</script>

<template>
  <Head title="Form Penilaian Asesmen" />
  
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-4 sm:py-6 md:py-12">
      <div class="mx-auto max-w-full px-4 sm:px-6 lg:max-w-7xl lg:px-8">
        <Card>
          <PageHeaderComponent title="Form Penilaian Asesmen" />
          <CardContent class="p-4 sm:p-6">
            <!-- Header Info -->
            <div class="mb-6">
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                  <h2 class="text-xl sm:text-2xl font-semibold">{{ schedule.assessment?.name || 'N/A' }}</h2>
                  <p class="text-gray-600 mt-1 text-sm sm:text-base">Penilaian Asesmen</p>
                </div>
                <Link :href="route('asesor.grading.index')" 
                      class="inline-flex items-center justify-center px-4 py-2 bg-gray-500 hover:bg-gray-700 text-white font-medium rounded-md text-sm transition-colors">
                  Kembali
                </Link>
              </div>
              
              <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-sm">
                <div class="bg-gray-50 p-3 rounded-lg">
                  <span class="font-medium text-gray-700">Waktu:</span>
                  <div class="text-gray-900 mt-1">{{ formatDateTime(schedule.schedule_time) }}</div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg">
                  <span class="font-medium text-gray-700">Lokasi:</span>
                  <div class="text-gray-900 mt-1">{{ schedule.location || 'Tidak ditentukan' }}</div>
                </div>
                <div class="bg-gray-50 p-3 rounded-lg sm:col-span-2 lg:col-span-1">
                  <span class="font-medium text-gray-700">Asesor:</span>
                  <div class="text-gray-900 mt-1">{{ schedule.asesor?.name || 'N/A' }}</div>
                </div>
              </div>
            </div>

            <!-- Form Penilaian -->
            <form @submit.prevent="submitGrading" class="space-y-6">
              <!-- Global Error Display -->
              <div v-if="form.hasErrors" class="bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan validasi</h3>
                    <div class="mt-2 text-sm text-red-700">
                      <p>Silakan periksa kembali data yang Anda masukkan.</p>
                    </div>
                  </div>
                </div>
              </div>

              <div class="space-y-6">
                <div v-for="(asesiSchedule, index) in form.results" :key="asesiSchedule.id" 
                     class="border border-gray-200 rounded-lg p-4 sm:p-6">
                  <!-- Hidden ID field -->
                  <input type="hidden" v-model="form.results[index].id" />
                  
                  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ asesiSchedule.asesi?.name || 'N/A' }}</h3>
                    <span :class="[getStatusBadgeClass(asesiSchedule.status), 'px-3 py-1 text-xs font-semibold rounded-full']">
                      {{ getStatusText(asesiSchedule.status) }}
                    </span>
                  </div>
                  
                  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                    <!-- Score -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nilai (0-100) <span class="text-red-500">*</span>
                      </label>
                      <input v-model.number="form.results[index].score" 
                             type="number" 
                             min="0" 
                             max="100" 
                             step="0"
                             required
                             class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                             :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': form.errors[`results.${index}.score`] }">
                      <div v-if="form.errors[`results.${index}.score`]" class="text-red-500 text-xs mt-1">
                        {{ form.errors[`results.${index}.score`] }}
                      </div>
                      <InputError :message="form.errors[`results.${index}.score`]" />
                    </div>
                    
                    <!-- Status -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                      </label>
                      <select v-model="form.results[index].status"
                              class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                              :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': form.errors[`results.${index}.status`] }">
                        <option value="pending">Menunggu Penilaian</option>
                        <option value="approved">Lulus</option>
                        <option value="rejected">Tidak Lulus</option>
                      </select>
                      <div v-if="form.errors[`results.${index}.status`]" class="text-red-500 text-xs mt-1">
                        {{ form.errors[`results.${index}.status`] }}
                      </div>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                      <label class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan
                      </label>
                      <textarea v-model="form.results[index].notes" 
                                rows="3"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 resize-vertical"
                                :class="{ 'border-red-500 focus:ring-red-500 focus:border-red-500': form.errors[`results.${index}.notes`] }"
                                placeholder="Catatan penilaian (opsional)..."></textarea>
                      <div v-if="form.errors[`results.${index}.notes`]" class="text-red-500 text-xs mt-1">
                        {{ form.errors[`results.${index}.notes`] }}
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              
              <div class="flex flex-col sm:flex-row justify-end gap-3 pt-6 border-t border-gray-200">
                <button type="button" 
                        @click="resetForm"
                        :disabled="processing"
                        class="w-full sm:w-auto px-4 py-2 bg-gray-500 hover:bg-gray-600 disabled:bg-gray-400 text-white font-medium rounded-md transition-colors">
                  Reset
                </button>
                <button type="submit" 
                        :disabled="processing"
                        class="w-full sm:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-md transition-colors flex items-center justify-center">
                  <svg v-if="processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ processing ? 'Menyimpan...' : 'Simpan Penilaian' }}
                </button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>