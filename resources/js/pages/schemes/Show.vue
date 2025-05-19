<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowLeft, Edit, FileText } from 'lucide-vue-next';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';

const props = defineProps({
  scheme: {
    type: Object,
    required: true,
  },
});
</script>

<template>
  <AppLayout>
    <Head :title="'Skema - ' + scheme.name" />

    <div class="container py-6 space-y-6">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <Button variant="outline" :href="route('schemes.index')" as-child>
            <a>
              <ArrowLeft class="h-4 w-4 mr-2" />
              Kembali
            </a>
          </Button>
          <h1 class="text-2xl font-bold">Detail Skema</h1>
        </div>
        <Link :href="route('schemes.edit', scheme.id)">
          <Button>
            <Edit class="h-4 w-4 mr-2" />
            Edit Skema
          </Button>
        </Link>
      </div>

      <Card>
        <CardHeader>
          <CardTitle>Informasi Skema</CardTitle>
        </CardHeader>
        <CardContent>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="space-y-2">
              <div class="text-sm text-gray-500">Kode Skema</div>
              <div class="font-medium flex items-center">
                <FileText class="h-4 w-4 mr-2 text-primary" />
                {{ scheme.code }}
              </div>
            </div>
            <div class="space-y-2">
              <div class="text-sm text-gray-500">Nama Skema</div>
              <div class="font-medium">{{ scheme.name }}</div>
            </div>
            <div class="space-y-2">
              <div class="text-sm text-gray-500">Tipe Skema</div>
              <div class="font-medium">{{ scheme.type }}</div>
            </div>
            <div class="space-y-2 md:col-span-3">
              <div class="text-sm text-gray-500">Ringkasan</div>
              <div class="font-medium">{{ scheme.summary }}</div>
            </div>
            <div v-if="scheme.document_path" class="space-y-2 md:col-span-3">
              <div class="text-sm text-gray-500">Dokumen</div>
              <div class="font-medium">{{ scheme.document_path }}</div>
            </div>
          </div>
        </CardContent>
      </Card>

      <Card>
        <CardHeader>
          <CardTitle>Unit Kompetensi</CardTitle>
        </CardHeader>
        <CardContent>
          <Table v-if="scheme.units && scheme.units.length > 0">
            <TableHeader>
              <TableRow>
                <TableHead>Kode Unit</TableHead>
                <TableHead>Nama Unit</TableHead>
                <TableHead>Tipe</TableHead>
                <TableHead class="text-right">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="unit in scheme.units" :key="unit.id">
                <TableCell>{{ unit.code }}</TableCell>
                <TableCell>{{ unit.name }}</TableCell>
                <TableCell>{{ unit.type }}</TableCell>
                <TableCell class="text-right">
                  <Button variant="ghost" size="sm" :href="route('units.show', unit.id)" as-child>
                    <a>Lihat Detail</a>
                  </Button>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>
          <div v-else class="text-center py-4 text-gray-500">
            Tidak ada unit kompetensi yang terkait dengan skema ini.
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>