<script setup lang="ts">
import { Table, TableBody, TableCell, TableHeader, TableRow } from '@/components/ui/table';

defineProps<{
  headers: string[];
  items: any[];
  emptyMessage?: string;
}>();
</script>

<template>
  <div>
    <div v-if="items.length === 0" class="text-center py-8 text-gray-500">
      {{ emptyMessage || 'Tidak ada data yang tersedia.' }}
    </div>
    <Table v-else>
      <TableHeader>
        <TableRow>
          <TableCell v-for="(header, index) in headers" :key="index" :class="{ 'text-right': index === headers.length - 1 && header === '' }">
            {{ header }}
          </TableCell>
        </TableRow>
      </TableHeader>
      <TableBody>
        <slot></slot>
      </TableBody>
    </Table>
  </div>
</template>