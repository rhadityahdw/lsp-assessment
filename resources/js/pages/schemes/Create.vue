<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import InputError from '@/components/InputError.vue';
import { Checkbox } from '@/components/ui/checkbox';
import {
  Card, CardAction, CardContent, CardFooter, CardHeader, CardTitle
} from '@/components/ui/card';
import { ArrowLeft, Trash2 } from 'lucide-vue-next';
import { BreadcrumbItem, Unit } from '@/types';

const props = defineProps<{
  units: Unit[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: route('dashboard'),
  },
  {
    title: 'Skema',
    href: route('schemes.index'),
  },
  {
    title: 'Tambah Skema',
    href: route('schemes.create'),
  },
];

const form = useForm({
  code: '',
  name: '',
  type: '',
  unit_ids: [] as number[],
  document_path: '',
  summary: '',
});

form.transform((data) => ({
  ...data,
  unit_ids: data.unit_ids.map(id => Number(id))
}));


const handleUnitSelection = (unitId: number, checked: boolean): void => {
  console.log(`Checkbox unit ${unitId} is now ${checked}`);
  form.unit_ids = checked 
    ? [...form.unit_ids, unitId]
    : form.unit_ids.filter((id: number) => id !== unitId)
};

const submit = (): void => {
  form.post(route('schemes.store'));
};
</script>

<template>
  <Head title="Tambah Skema" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="py-6 md:py-12">
      <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
        <Card>
          <CardHeader>
            <CardTitle class="text-xl md:text-2xl">Tambah Skema</CardTitle>
          </CardHeader>
          <form @submit.prevent="submit">
            <CardContent>
              <!-- Informasi Skema -->
              <div class="space-y-6">
                <h3 class="text-lg font-medium">Informasi Skema</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div class="space-y-2">
                    <Label for="code">Kode Skema</Label>
                    <Input id="code" v-model="form.code" placeholder="Masukkan kode skema" required />
                    <InputError :message="form.errors.code" />
                  </div>

                  <div class="space-y-2">
                    <Label for="name">Nama Skema</Label>
                    <Input id="name" v-model="form.name" placeholder="Masukkan nama skema" required />
                    <InputError :message="form.errors.name" />
                  </div>

                  <div class="space-y-2">
                    <Label for="type">Tipe Skema</Label>
                    <Input id="type" v-model="form.type" placeholder="Masukkan tipe skema" required />
                    <InputError :message="form.errors.type" />
                  </div>

                  <div class="space-y-2">
                    <Label for="document_path">Dokumen Path</Label>
                    <Input id="document_path" v-model="form.document_path" placeholder="Masukkan path dokumen" />
                    <InputError :message="form.errors.document_path" />
                  </div>

                  <div class="space-y-2 md:col-span-2">
                    <Label for="summary">Ringkasan</Label>
                    <Textarea id="summary" v-model="form.summary" placeholder="Masukkan ringkasan skema" />
                    <InputError :message="form.errors.summary" />
                  </div>
                </div>
              </div>
            </CardContent>

            <CardHeader>
              <CardTitle class="text-md font-medium">Pilih Unit Kompetensi</CardTitle>
            </CardHeader>
            <CardContent>
              <div class="space-y-4">
                <div v-if="props.units.length > 0">
                  <div class="border rounded-md divide-y">
                    <div v-for="unit in props.units" :key="unit.id" class="p-3 flex items-center space-x-3">
                      <Checkbox 
                        :id="`unit-${unit.id}`" 
                        :modelValue="form.unit_ids.includes(unit.id)"
                        @update:modelValue="(checked: boolean) => handleUnitSelection(unit.id, checked)"
                      />
                      <Label :for="`unit-${unit.id}`" class="flex-1 cursor-pointer">
                        <div class="font-medium">{{ unit.code }}</div>
                        <div class="text-sm text-gray-500">{{ unit.name }}</div>
                      </Label>
                    </div>
                  </div>
                  <InputError :message="form.errors.unit_ids" class="mt-2" />
                </div>
                <div v-else class="text-center py-4 text-gray-500">
                  Tidak ada unit kompetensi yang tersedia.
                </div>
              </div>
            </CardContent>
            
            <CardFooter class="flex justify-end gap-2 mt-4">
              <CardAction class="rounded-b-lg flex justify-end gap-2">
                <Button type="button" variant="outline" :href="route('schemes.index')" as-child>
                  <a>Batal</a>
                </Button>
                <Button type="submit" :disabled="form.processing">Simpan Skema</Button>
              </CardAction>
            </CardFooter>
          </form>
        </Card>
      </div>
    </div>
  </AppLayout>
</template>