<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';

import { ref } from 'vue';

const props = defineProps<{
  attempts: Array<{
    id: number;
    user_name: string;
    scheme_name: string;
    status: string;
    created_at: string;
  }>;
}>();

const updateStatus = (id: number, status: string) => {
  router.post(route('attempts.verify', id), { status }, {
    onSuccess: () => {
      // You can add notification here if needed
    },
    onError: (errors) => {
      console.error(errors);
    }
  });
};
</script>

<template>
  <Head title="Daftar Pendaftaran" />

  <AppLayout>
    <div class="container py-6 space-y-6 max-w-7xl mx-auto">
      <Card>
        <CardContent>
          <h1 class="text-2xl font-bold mb-4">Daftar Pendaftaran</h1>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>ID</TableHead>
                <TableHead>Nama Peserta</TableHead>
                <TableHead>Skema Sertifikasi</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Tanggal Daftar</TableHead>
                <TableHead class="text-center">Aksi</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              <TableRow v-for="attempt in props.attempts" :key="attempt.id">
                <TableCell>{{ attempt.id }}</TableCell>
                <TableCell>{{ attempt.user_name }}</TableCell>
                <TableCell>{{ attempt.scheme_name }}</TableCell>
                <TableCell>
                  <span
                    :class="{
                      'text-green-600': attempt.status === 'approved',
                      'text-red-600': attempt.status === 'rejected',
                      'text-yellow-600': attempt.status === 'submitted',
                    }"
                    class="font-semibold"
                  >
                    {{ attempt.status }}
                  </span>
                </TableCell>
                <TableCell>{{ new Date(attempt.created_at).toLocaleDateString() }}</TableCell>
                <TableCell class="text-center space-x-2">
                  <Link
                    :href="route('attempts.show', attempt.id)"
                    class="text-blue-600 hover:underline"
                  >
                    Detail
                  </Link>
                </TableCell>
              </TableRow>
            </TableBody>
          </Table>

          <div v-if="props.attempts.length === 0" class="text-center py-8 text-gray-500">
            Belum ada pendaftaran yang terdaftar.
          </div>
        </CardContent>
      </Card>
    </div>
  </AppLayout>
</template>
