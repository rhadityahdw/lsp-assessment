<script setup>
import { ref, onMounted } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '@/components/ui/select';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { ArrowLeft, Plus, Trash2 } from 'lucide-vue-next';

const props = defineProps({
  scheme: {
    type: Object,
    required: true,
  },
  units: {
    type: Array,
    default: () => [],
  },
});

const form = useForm({
  code: props.scheme.code,
  name: props.scheme.name,
  type: props.scheme.type,
  units: props.scheme.units.map(unit => unit.id),
  document_path: props.scheme.document_path || '',
  summary: props.scheme.summary || '',
});

const selectedUnits = ref([]);

onMounted(() => {
  // Inisialisasi unit yang sudah dipilih
  selectedUnits.value = props.scheme.units;
});

const handleUnitSelection = (unitId) => {
  const id = parseInt(unitId);
  if (!form.units.includes(id)) {
    form.units.push(id);
    selectedUnits.value.push(props.units.find(unit => unit.id === id));
  }
};

const removeUnit = (index) => {
  const unitId = form.units[index];
  form.units.splice(index, 1);
  selectedUnits.value = selectedUnits.value.filter(unit => unit.id !== unitId);
};

const submit = () => {
  form.put(route('schemes.update', props.scheme.id));
};
</script>

<template>
  <AppLayout>
    <Head title="Edit Skema" />

    <div class="container py-6 space-y-6">
      <div class="flex items-center gap-4">
        <Button variant="outline" :href="route('schemes.index')" as-child>
          <a>
            <ArrowLeft class="h-4 w-4 mr-2" />
            Kembali
          </a>
        </Button>
        <h1 class="text-2xl font-bold">Edit Skema</h1>
      </div>

      <form @submit.prevent="submit">
        <Card>
          <CardHeader>
            <CardTitle>Informasi Skema</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div class="space-y-2">
                <Label for="code">Kode Skema</Label>
                <Input id="code" v-model="form.code" placeholder="Masukkan kode skema" required />
                <p v-if="form.errors.code" class="text-sm text-red-500">{{ form.errors.code }}</p>
              </div>
              
              <div class="space-y-2">
                <Label for="name">Nama Skema</Label>
                <Input id="name" v-model="form.name" placeholder="Masukkan nama skema" required />
                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
              </div>
              
              <div class="space-y-2">
                <Label for="type">Tipe Skema</Label>
                <Input id="type" v-model="form.type" placeholder="Masukkan tipe skema" required />
                <p v-if="form.errors.type" class="text-sm text-red-500">{{ form.errors.type }}</p>
              </div>
              
              <div class="space-y-2">
                <Label for="document_path">Dokumen Path</Label>
                <Input id="document_path" v-model="form.document_path" placeholder="Masukkan path dokumen" />
                <p v-if="form.errors.document_path" class="text-sm text-red-500">{{ form.errors.document_path }}</p>
              </div>
              
              <div class="space-y-2 md:col-span-2">
                <Label for="summary">Ringkasan</Label>
                <Textarea id="summary" v-model="form.summary" placeholder="Masukkan ringkasan skema" />
                <p v-if="form.errors.summary" class="text-sm text-red-500">{{ form.errors.summary }}</p>
              </div>
            </div>
          </CardContent>
          
          <CardHeader>
            <CardTitle>Unit Kompetensi</CardTitle>
          </CardHeader>
          <CardContent>
            <div class="space-y-4">
              <div class="space-y-2">
                <Label for="unit-select">Pilih Unit</Label>
                <Select @update:modelValue="handleUnitSelection">
                  <SelectTrigger>
                    <SelectValue placeholder="Pilih unit kompetensi" />
                  </SelectTrigger>
                  <SelectContent>
                    <SelectItem v-for="unit in props.units" :key="unit.id" :value="unit.id">
                      {{ unit.code }} - {{ unit.name }}
                    </SelectItem>
                  </SelectContent>
                </Select>
                <p v-if="form.errors.units" class="text-sm text-red-500">{{ form.errors.units }}</p>
              </div>
              
              <div v-if="selectedUnits.length > 0" class="space-y-2">
                <h3 class="text-sm font-medium">Unit yang dipilih:</h3>
                <div class="border rounded-md divide-y">
                  <div v-for="(unit, index) in selectedUnits" :key="unit.id" class="p-3 flex justify-between items-center">
                    <div>
                      <p class="font-medium">{{ unit.code }}</p>
                      <p class="text-sm text-gray-500">{{ unit.name }}</p>
                    </div>
                    <Button variant="ghost" size="icon" @click="removeUnit(index)" type="button">
                      <Trash2 class="h-4 w-4 text-red-500" />
                    </Button>
                  </div>
                </div>
              </div>
            </div>
          </CardContent>
          
          <CardFooter class="flex justify-end gap-2">
            <Button type="submit" :disabled="form.processing">Simpan Perubahan</Button>
          </CardFooter>
        </Card>
      </form>
    </div>
  </AppLayout>
</template>