<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { BreadcrumbItem } from '@/types';

interface Props {
  schemes: {
    id: number;
    name: string;
  }[];
}

const props = defineProps<Props>();

const form = useForm({
  scheme_id: '',
  name: '',
  type: '',
  link: ''
});

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: route('dashboard')
  },
  {
    title: 'Asesmen',
    href: route('assessments.index')
  },
  {
    title: 'Tambah Asesmen Baru',
    href: route('assessments.create')
  }
];

const submit = () => {
  form.post(route('assessments.store'));
};
</script>

<template>
  <Head title="Buat Asesmen Baru" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-6 md:py-12">
      <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
        <Card>
          <CardHeader>
            <CardTitle class="text-xl md:text-2xl">Buat Asesmen</CardTitle>
          </CardHeader>
          <CardContent>
            <form @submit.prevent="submit" class="space-y-6">
              <div class="space-y-2">
                <Label for="scheme">Skema</Label>
                <Select v-model="form.scheme_id">
                  <SelectTrigger>
                    <SelectValue placeholder="Pilih skema" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem
                      v-for="scheme in schemes"
                      :key="scheme.id"
                      :value="scheme.id"
                    >
                      {{ scheme.name }}
                    </SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div class="space-y-2">
                <Label for="name">Nama Asesmen</Label>
                <Input
                  id="name"
                  v-model="form.name"
                  type="text"
                  required
                />
              </div>

              <div class="space-y-2">
                <Label for="type">Tipe Asesmen</Label>
                <Select v-model="form.type">
                  <SelectTrigger>
                    <SelectValue placeholder="Pilih tipe asesmen" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem value="tulis">Tulis</SelectItem>
                    <SelectItem value="praktek">Praktek</SelectItem>
                  </SelectContent>
                </Select>
              </div>

              <div class="space-y-2">
                <Label for="link">Link Asesmen</Label>
                <Input
                  id="link"
                  v-model="form.link"
                  type="url"
                  required
                />
              </div>

              <div class="flex justify-end gap-4">
                <Button
                  type="button"
                  variant="outline"
                  href="{{ route('assessments.index') }}"
                >
                  Batal
                </Button>
                <Button type="submit" :disabled="form.processing">
                  Simpan
                </Button>
              </div>
            </form>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>