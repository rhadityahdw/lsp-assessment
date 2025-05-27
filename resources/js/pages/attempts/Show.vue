<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowLeft } from 'lucide-vue-next';
import { route } from 'ziggy-js';

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

const documentList = [
  { key: 'ktp', label: 'KTP' },
  { key: 'ijazah', label: 'Ijazah' },
  { key: 'pas_foto', label: 'Pas Foto' },
  { key: 'bukti_kerja', label: 'Bukti Kerja' },
  { key: 'portofolio', label: 'Portofolio' },
];
</script>

<template>
  <AppLayout>
    <Head :title="`Detail Pendaftaran #${attempt.id}`" />

    <div class="container py-6 max-w-4xl mx-auto space-y-6">
      <!-- Header -->
      <div class="flex items-center gap-4">
        <Button variant="outline" as-child>
          <a :href="route('attempts.index')">
            <ArrowLeft class="h-4 w-4 mr-2" />
            Kembali
          </a>
        </Button>
        <h1 class="text-2xl font-bold">Detail Pendaftaran #{{ attempt.id }}</h1>
      </div>

      <!-- Info Peserta & Skema -->
      <Card>
        <CardHeader><CardTitle>Informasi Peserta & Skema</CardTitle></CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <h3 class="font-semibold">Nama Peserta</h3>
              <p>{{ attempt.user.name }}</p>
            </div>
            <div>
              <h3 class="font-semibold">Skema Sertifikasi</h3>
              <p>{{ attempt.scheme.name }}</p>
            </div>
            <div>
              <h3 class="font-semibold">Status</h3>
              <p :class="{
                'text-green-600': attempt.status === 'approved',
                'text-red-600': attempt.status === 'rejected',
                'text-yellow-600': attempt.status === 'submitted',
              }" class="font-semibold">{{ attempt.status }}</p>
            </div>
            <div>
              <h3 class="font-semibold">Tanggal Daftar</h3>
              <p>{{ new Date(attempt.created_at).toLocaleDateString() }}</p>
            </div>
          </div>
        </CardContent>
      </Card>

      <!-- Profile -->
      <Card>
        <CardHeader>
            <CardTitle>Profil Peserta</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><strong>NIK:</strong> {{ attempt.user.profile?.nik ?? '-' }}</div>
            <div><strong>Jenis Kelamin:</strong> {{ attempt.user.profile?.gender ?? '-' }}</div>
            <div><strong>Tempat Lahir:</strong> {{ attempt.user.profile?.place_of_birth ?? '-' }}</div>
            <div><strong>Tanggal Lahir:</strong> {{ attempt.user.profile?.date_of_birth ?? '-' }}</div>
            <div><strong>Alamat:</strong> {{ attempt.user.profile?.address ?? '-' }}</div>
            <div><strong>No. HP:</strong> {{ attempt.user.profile?.phone_number ?? '-' }}</div>
            <div><strong>Pendidikan:</strong> {{ attempt.user.profile?.education ?? '-' }}</div>
            <div><strong>Pekerjaan:</strong> {{ attempt.user.profile?.job_title ?? '-' }}</div>
            <div><strong>Nama Perusahaan:</strong> {{ attempt.user.profile?.company_name ?? '-' }}</div>
            <div><strong>Alamat Perusahaan:</strong> {{ attempt.user.profile?.company_address ?? '-' }}</div>
            <div><strong>Telepon Perusahaan:</strong> {{ attempt.user.profile?.company_phone ?? '-' }}</div>
            <div><strong>Email Perusahaan:</strong> {{ attempt.user.profile?.company_email ?? '-' }}</div>
          </div>
        </CardContent>
      </Card>

      <!-- Dokumen -->
      <Card>
        <CardHeader><CardTitle>Dokumen Pendukung</CardTitle></CardHeader>
        <CardContent>
          <ul class="list-disc pl-5 space-y-1">
            <li v-for="doc in documentList" :key="doc.key">
              <span class="font-medium">{{ doc.label }}:</span>
              <span v-if="attempt[doc.key]">
                <a :href="attempt[doc.key]" target="_blank" class="text-blue-600 hover:underline">
                  Lihat Dokumen
                </a>
              </span>
              <span v-else class="text-gray-400 italic">Tidak ada</span>
            </li>
          </ul>
        </CardContent>
      </Card>

      <!-- Unit Kompetensi -->
      <Card>
        <CardHeader><CardTitle>Unit Kompetensi</CardTitle></CardHeader>
        <CardContent>
          <ul class="list-disc pl-5 space-y-1">
            <li v-for="unit in attempt.scheme.units" :key="unit.id">
              <strong>{{ unit.code }}</strong> - {{ unit.name }}
            </li>
          </ul>
        </CardContent>
      </Card>

      <!-- Jawaban Pre Asesmen -->
      <Card>
        <CardHeader><CardTitle>Jawaban Pre Asesmen</CardTitle></CardHeader>
        <CardContent>
          <ul class="list-disc pl-5 space-y-3">
            <li
              v-for="answer in attempt.pre_assessment_answers"
              :key="answer.id"
            >
              <strong>{{ answer.pre_assessment.question }}</strong>
              <p>{{ answer.answer === '1' ? 'yes' : 'no' }}</p>
            </li>
          </ul>
        </CardContent>
      </Card>

      <!-- Tombol Aksi -->
      <div class="flex gap-4 justify-end">
        <Button
          variant="outline"
          class="text-green-600"
          @click="updateStatus('approved')"
          :disabled="attempt.status !== 'submitted'"
        >
          Terima
        </Button>

        <Button
          variant="outline"
          class="text-red-600"
          @click="updateStatus('rejected')"
          :disabled="attempt.status !== 'submitted'"
        >
          Tolak
        </Button>
      </div>
    </div>
  </AppLayout>
</template>
