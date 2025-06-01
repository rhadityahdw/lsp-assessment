<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { BreadcrumbItem } from '@/types';
import { Pencil } from 'lucide-vue-next';

interface Assessment {
  id: number;
  name: string;
  type: string;
  link: string;
  scheme: {
    id: number;
    name: string;
  };
  created_by: {
    id: number;
    name: string;
  };
}

defineProps<{
  assessment: Assessment;
  can: {
    edit: boolean;
    delete: boolean;
  };
}>();

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
    title: 'Detail Asesmen',
    href: route('assessments.show')
  }
];
</script>

<template>
  <Head :title="`Asesmen - ${assessment.name}`" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-6 md:py-12">
      <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-2xl font-semibold">Detail Asesmen</h1>
          <div class="flex gap-4">
            <Link
              v-if="can.edit"
              :href="route('assessments.edit', assessment.id)"
              class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2"
            >
              <Pencil class="h-4 w-4 mr-2" />
              Edit
            </Link>
          </div>
        </div>

        <Card>
          <CardContent class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <h3 class="text-sm font-medium text-gray-500">Nama Asesmen</h3>
                <p class="mt-1 text-sm text-gray-900">{{ assessment.name }}</p>
              </div>

              <div>
                <h3 class="text-sm font-medium text-gray-500">Skema</h3>
                <p class="mt-1 text-sm text-gray-900">{{ assessment.scheme.name }}</p>
              </div>

              <div>
                <h3 class="text-sm font-medium text-gray-500">Tipe</h3>
                <p class="mt-1 text-sm text-gray-900 capitalize">{{ assessment.type }}</p>
              </div>

              <div>
                <h3 class="text-sm font-medium text-gray-500">Dibuat Oleh</h3>
                <p class="mt-1 text-sm text-gray-900">{{ assessment.created_by.name }}</p>
              </div>

              <div class="col-span-full">
                <h3 class="text-sm font-medium text-gray-500">Link Asesmen</h3>
                <a
                  :href="assessment.link"
                  target="_blank"
                  rel="noopener noreferrer"
                  class="mt-1 text-sm text-blue-600 hover:text-blue-800"
                >
                  {{ assessment.link }}
                </a>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>