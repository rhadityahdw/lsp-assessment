<script setup>
import { ref, computed } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { PlusCircle, Search } from 'lucide-vue-next';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '@/components/ui/table';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import ActionButtonsComponent from '@/components/ActionButtonsComponent.vue';

const props = defineProps({
  schemes: {
    type: Array,
    default: () => [],
  },
});

const searchQuery = ref('');
const tableHeaders = ['Kode', 'Nama Skema', 'Tipe', 'Jumlah Unit'];

const filteredSchemes = computed(() => {
  if (!searchQuery.value) return props.schemes;
  
  const query = searchQuery.value.toLowerCase();
  return props.schemes.filter(scheme => 
    scheme.code.toLowerCase().includes(query) || 
    scheme.name.toLowerCase().includes(query) ||
    scheme.type.toLowerCase().includes(query)
  );
});
</script>

<template>
  <AppLayout>
    <Head title="Daftar Skema" />

    <div class="container py-6 space-y-6">
      <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold">Daftar Skema</h1>
        <Link :href="route('schemes.create')">
          <Button>
            <PlusCircle class="h-4 w-4 mr-2" />
            Tambah Skema
          </Button>
        </Link>
      </div>

      <div class="flex items-center border rounded-md px-3 max-w-sm">
        <Search class="h-4 w-4 text-gray-500" />
        <Input
          v-model="searchQuery"
          placeholder="Cari skema..."
          class="border-0 focus-visible:ring-0 focus-visible:ring-offset-0"
        />
      </div>

      <Table>
        <TableCaption>Daftar skema yang tersedia</TableCaption>
        <TableHeader>
          <TableRow>
            <TableHead v-for="header in tableHeaders" :key="header">{{ header }}</TableHead>
            <TableHead class="text-right">Aksi</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          <TableRow v-for="scheme in filteredSchemes" :key="scheme.id">
            <TableCell>{{ scheme.code }}</TableCell>
            <TableCell>{{ scheme.name }}</TableCell>
            <TableCell>{{ scheme.type }}</TableCell>
            <TableCell>{{ scheme.units ? scheme.units.length : 0 }}</TableCell>
            <TableCell class="text-right">
              <ActionButtonsComponent
                :show-route="route('schemes.show', scheme.id)"
                :edit-route="route('schemes.edit', scheme.id)"
                :delete-route="route('schemes.destroy', scheme.id)"
                delete-message="Apakah Anda yakin ingin menghapus skema ini?"
              />
            </TableCell>
          </TableRow>
        </TableBody>
      </Table>
    </div>
  </AppLayout>
</template>