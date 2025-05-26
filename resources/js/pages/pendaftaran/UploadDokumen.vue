<script setup lang="ts">
import { useFormStore } from '@/pages/stores/formStore';
import { Card, CardContent } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import { Input } from '@/components/ui/input';
import { Scheme } from '@/types';

type DocumentKey = 'ktp' | 'ijazah' | 'pas_foto' | 'bukti_kerja' | 'portofolio';

const formStore = useFormStore();

const documentRequirements = [
  {
    label: 'Pas Foto 3x4 (Background Merah) *',
    key: 'pas_foto' as DocumentKey,
    accept: 'image/*',
    required: true
  },
  {
    label: 'Identitas Pribadi (KTP/Kartu Pelajar) *',
    key: 'ktp' as DocumentKey,
    accept: 'image/*,.pdf',
    required: true
  },
  {
    label: 'Bukti Pendidikan (Ijazah/Transkrip) *',
    key: 'ijazah' as DocumentKey,
    accept: '.pdf,.jpg,.jpeg,.png',
    required: true,
    description: 'Strata 1 bidang Ilmu Komputer, Teknik Informatika, atau terkait'
  },
  {
    label: 'Bukti Pengalaman Kerja (SK/CV)',
    key: 'bukti_kerja' as DocumentKey,
    accept: '.pdf,.jpg,.jpeg,.png',
    description: 'Minimal 1 tahun pengalaman di bidang Big Data Scientist'
  },
  {
    label: 'Portofolio (Laporan Project/Sertifikat Kompetensi)',
    key: 'portofolio' as DocumentKey,
    accept: '.pdf,.zip',
    description: 'Contoh project terkait unit kompetensi Big Data Scientist'
  }
];

const handleFileUpload = (key: DocumentKey, files: FileList) => {
  formStore.documents[key] = files[0];
};

const getCompetencyUnits = () => {
  // Ambil unit kompetensi dari skema yang dipilih di formStore
  return (formStore.selectedScheme as Scheme)?.units || [];
};
</script>

<template>
  <Card>
    <CardContent class="space-y-6">
      <!-- Bagian Persyaratan Administratif -->
      <div>
        <h2 class="text-lg font-semibold mb-4">Persyaratan Administratif</h2>
        <div class="space-y-4">
          <div v-for="doc in documentRequirements" :key="doc.key" class="space-y-2">
            <Label class="block">{{ doc.label }}</Label>
            <Input
              type="file"
              :accept="doc.accept"
              @change="(e: any) => handleFileUpload(doc.key, e.target.files)"
              :required="doc.required"
            />
            <p v-if="doc.description" class="text-sm text-muted-foreground">
              {{ doc.description }}
            </p>
          </div>
        </div>
      </div>

      <!-- Bagian Unit Kompetensi -->
      <div v-if="getCompetencyUnits().length > 0">
        <h2 class="text-lg font-semibold mb-4">Unit Kompetensi {{ formStore.selectedScheme?.name }}</h2>
        <ul class="list-disc pl-6 space-y-2">
          <li v-for="(unit, index) in getCompetencyUnits()" :key="index">
            {{ unit.code }} - {{ unit.name }}
          </li>
        </ul>
      </div>

      <!-- Informasi Upload -->
      <div class="bg-blue-50 p-4 rounded-lg">
        <h3 class="font-medium mb-2">Ketentuan Upload:</h3>
        <ul class="list-disc pl-6 space-y-1 text-sm">
          <li>Ukuran maksimal per file: 2MB</li>
          <li>Format file yang diterima: JPG, PNG, PDF, ZIP</li>
          <li>Untuk multiple file, kompres dalam format ZIP</li>
          <li>Pastikan dokumen terbaca dengan jelas</li>
        </ul>
      </div>
    </CardContent>
  </Card>
</template>