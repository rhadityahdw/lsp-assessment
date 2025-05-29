<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Badge } from '@/components/ui/badge/index';
import { Separator } from '@/components/ui/separator';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogTrigger } from '@/components/ui/dialog';
import { ArrowLeft, FileText, Eye, Download, CheckCircle, XCircle, Clock } from 'lucide-vue-next';
import { route } from 'ziggy-js';
import { ref } from 'vue';

type DokumenKey = 'ktp' | 'ijazah' | 'pas_foto' | 'bukti_kerja' | 'portofolio';

interface Attempt {
  id: number;
  status: string;
  ktp: string | null;
  ijazah: string | null;
  pas_foto: string | null;
  bukti_kerja: string | null;
  portofolio: string | null;
  created_at: string;
  user: {
    name: string;
    profile?: {
      nik?: string;
      gender?: string;
      date_of_birth?: string;
      place_of_birth?: string;
      address?: string;
      phone_number?: string;
      education?: string;
      job_title?: string;
      company_name?: string;
      company_address?: string;
      company_phone?: string;
      company_email?: string;
    };
  };
  scheme: {
    name: string;
    code: string;
    units: Array<{
      id: number;
      code: string;
      name: string;
      type: string;
    }>;
  };
  pre_assessment_answers: Array<{
    id: number;
    answer: string;
    pre_assessment: {
      id: number;
      question: string;
    };
  }>;
}

const props = withDefaults(defineProps<{ attempt: Attempt }>(), {
  attempt: () => ({
    id: 0,
    status: '',
    ktp: null,
    ijazah: null,
    pas_foto: null,
    bukti_kerja: null,
    portofolio: null,
    created_at: '',
    user: {
      name: '',
      profile: {},
    },
    scheme: {
      name: '',
      code: '',
      units: [],
    },
    pre_assessment_answers: [],
  }),
});

const selectedDocument = ref<string | null>(null);
const isDocumentModalOpen = ref(false);

const updateStatus = (status: string) => {
  router.post(route('attempts.verify', props.attempt.id), { status }, {
    onSuccess: () => {
      // You can show toast here
    },
    onError: (errors) => {
      console.error(errors);
    }
  });
};

const documentList: { key: DokumenKey; label: string }[] = [
  { key: 'ktp', label: 'KTP' },
  { key: 'ijazah', label: 'Ijazah' },
  { key: 'pas_foto', label: 'Pas Foto' },
  { key: 'bukti_kerja', label: 'Bukti Kerja' },
  { key: 'portofolio', label: 'Portofolio' },
];

const getStatusColor = (status: string) => {
  switch (status) {
    case 'approved':
    case 'diterima':
      return 'bg-green-100 text-green-800 border-green-200';
    case 'rejected':
    case 'ditolak':
      return 'bg-red-100 text-red-800 border-red-200';
    case 'submitted':
      return 'bg-yellow-100 text-yellow-800 border-yellow-200';
    default:
      return 'bg-gray-100 text-gray-800 border-gray-200';
  }
};

const getStatusIcon = (status: string) => {
  switch (status) {
    case 'approved':
    case 'diterima':
      return CheckCircle;
    case 'rejected':
    case 'ditolak':
      return XCircle;
    case 'submitted':
      return Clock;
    default:
      return Clock;
  }
};

const openDocumentModal = (documentUrl: string) => {
  selectedDocument.value = documentUrl;
  isDocumentModalOpen.value = true;
};

const isImageFile = (url: string) => {
  return /\.(jpg|jpeg|png|gif|webp)$/i.test(url);
};

const isPdfFile = (url: string) => {
  return /\.pdf$/i.test(url);
};
</script>

<template>
  <AppLayout>
    <Head :title="`Detail Pendaftaran #${attempt.id}`" />

    <div class="min-h-screen">
      <div class="container py-8 max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div class="bg-white rounded-xl shadow-sm border p-6">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
              <Button variant="outline" size="sm" as-child>
                <a :href="route('attempts.index')">
                  <ArrowLeft class="h-4 w-4 mr-2" />
                  Kembali
                </a>
              </Button>
              <div>
                <h1 class="text-3xl font-bold text-gray-900">Detail Pendaftaran #{{ attempt.id }}</h1>
                <p class="text-gray-600 mt-1">Informasi lengkap pendaftaran sertifikasi</p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <component :is="getStatusIcon(attempt.status)" class="h-5 w-5" />
              <Badge :class="getStatusColor(attempt.status)" class="px-3 py-1 text-sm font-medium">
                {{ attempt.status === 'submitted' ? 'Menunggu Verifikasi' : 
                   attempt.status === 'approved' || attempt.status === 'diterima' ? 'Diterima' : 
                   attempt.status === 'rejected' || attempt.status === 'ditolak' ? 'Ditolak' : attempt.status }}
              </Badge>
            </div>
          </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
          <!-- Left Column -->
          <div class="lg:col-span-2 space-y-6">
            <!-- Info Peserta & Skema -->
            <Card class="shadow-sm bg-white">
              <CardHeader class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-t-lg">
                <CardTitle class="text-xl">Informasi Peserta & Skema</CardTitle>
              </CardHeader>
              <CardContent class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="space-y-2">
                    <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Nama Peserta</h3>
                    <p class="text-lg font-medium text-gray-900">{{ attempt.user.name }}</p>
                  </div>
                  <div class="space-y-2">
                    <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Skema Sertifikasi</h3>
                    <p class="text-lg font-medium text-gray-900">{{ attempt.scheme.name }}</p>
                    <p class="text-sm text-gray-600">Kode: {{ attempt.scheme.code }}</p>
                  </div>
                  <div class="space-y-2">
                    <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Tanggal Daftar</h3>
                    <p class="text-lg font-medium text-gray-900">{{ new Date(attempt.created_at).toLocaleDateString('id-ID', { 
                      weekday: 'long', 
                      year: 'numeric', 
                      month: 'long', 
                      day: 'numeric' 
                    }) }}</p>
                  </div>
                  <div class="space-y-2">
                    <h3 class="font-semibold text-gray-700 text-sm uppercase tracking-wide">Total Unit Kompetensi</h3>
                    <p class="text-lg font-medium text-gray-900">{{ attempt.scheme.units.length }} Unit</p>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Profile -->
            <Card class="shadow-sm bg-white">
              <CardHeader class="bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-t-lg">
                <CardTitle class="text-xl">Profil Peserta</CardTitle>
              </CardHeader>
              <CardContent class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">NIK:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.nik ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Jenis Kelamin:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.gender ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Tempat Lahir:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.place_of_birth ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Tanggal Lahir:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.date_of_birth ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Alamat:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.address ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">No. HP:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.phone_number ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Pendidikan:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.education ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Pekerjaan:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.job_title ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Nama Perusahaan:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.company_name ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Alamat Perusahaan:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.company_address ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Telepon Perusahaan:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.company_phone ?? '-' }}</p>
                  </div>
                  <div class="space-y-1">
                    <span class="text-sm font-medium text-gray-600">Email Perusahaan:</span>
                    <p class="text-gray-900">{{ attempt.user.profile?.company_email ?? '-' }}</p>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Unit Kompetensi -->
            <Card class="shadow-sm border-0 bg-white">
              <CardHeader class="bg-gradient-to-r from-purple-500 to-violet-600 text-white rounded-t-lg">
                <CardTitle class="text-xl">Unit Kompetensi</CardTitle>
              </CardHeader>
              <CardContent class="p-6">
                <div class="space-y-4">
                  <div v-for="(unit, index) in attempt.scheme.units" :key="unit.id" 
                       class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                      <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                          <Badge variant="outline" class="bg-purple-50 text-purple-700 border-purple-200">
                            Unit {{ index + 1 }}
                          </Badge>
                          <Badge variant="secondary" class="bg-gray-100 text-gray-700">
                            {{ unit.type }}
                          </Badge>
                        </div>
                        <h4 class="font-semibold text-gray-900 mb-1">{{ unit.code }}</h4>
                        <p class="text-gray-700">{{ unit.name }}</p>
                      </div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Jawaban Pre Asesmen -->
            <Card class="shadow-sm border-0 bg-white">
              <CardHeader class="bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-t-lg">
                <CardTitle class="text-xl">Jawaban Pre Asesmen</CardTitle>
              </CardHeader>
              <CardContent class="p-6">
                <div class="space-y-4">
                  <div v-for="(answer, index) in attempt.pre_assessment_answers" :key="answer.id" 
                       class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start gap-4">
                      <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center font-semibold text-sm">
                          {{ index + 1 }}
                        </div>
                      </div>
                      <div class="flex-1">
                        <h4 class="font-medium text-gray-900 mb-2">{{ answer.pre_assessment.question }}</h4>
                        <div class="flex items-center gap-2">
                          <Badge :class="answer.answer == '1' ? 'bg-green-100 text-green-800 border-green-200' : 'bg-red-100 text-red-800 border-red-200'">
                            <component :is="answer.answer == '1' ? CheckCircle : XCircle" class="h-3 w-3 mr-1" />
                            {{ answer.answer == '1' ? 'Ya' : 'Tidak' }}
                          </Badge>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          <!-- Right Column -->
          <div class="space-y-6">
            <!-- Dokumen -->
            <Card class="shadow-sm border-0 bg-white">
              <CardHeader class="bg-gradient-to-r from-indigo-500 to-blue-600 text-white rounded-t-lg">
                <CardTitle class="text-xl">Dokumen Pendukung</CardTitle>
              </CardHeader>
              <CardContent class="p-6">
                <div class="space-y-3">
                  <div v-for="doc in documentList" :key="doc.key" 
                       class="border border-gray-200 rounded-lg p-3 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <!-- <component :is="doc.icon" class="h-5 w-5 text-gray-500" /> -->
                        <span class="font-medium text-gray-900">{{ doc.label }}</span>
                      </div>
                      <div v-if="props.attempt[doc.key]" class="flex items-center gap-2">
                        <Button size="sm" variant="outline" @click="openDocumentModal(props.attempt[doc.key])">
                          <Eye class="h-4 w-4 mr-1" />
                          Lihat
                        </Button>
                        <Button size="sm" variant="outline" as-child>
                          <a :href="props.attempt[doc.key]" target="_blank" download>
                            <Download class="h-4 w-4 mr-1" />
                            Unduh
                          </a>
                        </Button>
                      </div>
                      <Badge v-else variant="secondary" class="text-gray-500">
                        Tidak ada
                      </Badge>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>

            <!-- Tombol Aksi -->
            <Card class="shadow-sm border-0 bg-white">
              <CardHeader class="bg-gradient-to-r from-gray-500 to-gray-600 text-white rounded-t-lg">
                <CardTitle class="text-xl">Aksi Verifikasi</CardTitle>
              </CardHeader>
              <CardContent class="p-6">
                <div class="space-y-3">
                  <Button
                    class="w-full bg-green-600 hover:bg-green-700 text-white"
                    @click="updateStatus('approved')"
                    :disabled="attempt.status !== 'submitted'"
                  >
                    <CheckCircle class="h-4 w-4 mr-2" />
                    Terima Pendaftaran
                  </Button>

                  <Button
                    variant="destructive"
                    class="w-full"
                    @click="updateStatus('rejected')"
                    :disabled="attempt.status !== 'submitted'"
                  >
                    <XCircle class="h-4 w-4 mr-2" />
                    Tolak Pendaftaran
                  </Button>
                </div>
                <p class="text-sm text-gray-500 mt-3 text-center">
                  Status saat ini: <span class="font-medium">{{ attempt.status }}</span>
                </p>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>

    <!-- Document Modal -->
    <Dialog v-model:open="isDocumentModalOpen">
      <DialogContent class="max-w-4xl max-h-[90vh] overflow-hidden">
        <DialogHeader>
          <DialogTitle>Preview Dokumen</DialogTitle>
        </DialogHeader>
        <div class="flex-1 overflow-hidden">
          <div v-if="selectedDocument" class="h-[70vh] w-full">
            <!-- Image Preview -->
            <img v-if="isImageFile(selectedDocument)" 
                 :src="selectedDocument" 
                 alt="Document preview" 
                 class="w-full h-full object-contain rounded-lg" />
            
            <!-- PDF Preview -->
            <iframe v-else-if="isPdfFile(selectedDocument)" 
                    :src="selectedDocument" 
                    class="w-full h-full rounded-lg border"
                    frameborder="0">
            </iframe>
            
            <!-- Other file types -->
            <div v-else class="flex items-center justify-center h-full bg-gray-50 rounded-lg">
              <div class="text-center">
                <FileText class="h-16 w-16 text-gray-400 mx-auto mb-4" />
                <p class="text-gray-600 mb-4">Preview tidak tersedia untuk tipe file ini</p>
                <Button as-child>
                  <a :href="selectedDocument" target="_blank" download>
                    <Download class="h-4 w-4 mr-2" />
                    Unduh File
                  </a>
                </Button>
              </div>
            </div>
          </div>
        </div>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
