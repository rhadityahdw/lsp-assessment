<script setup lang="ts">
import { useFormStore } from '@/pages/stores/formStore';
import { Card, CardContent } from '@/components/ui/card';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectGroup, SelectItem } from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import { computed } from 'vue';
import { Scheme } from '@/types';

const props = defineProps<{
  schemes: Scheme[]
}>()

const formStore = useFormStore()

const modelValue = computed({
  get: () => formStore.selectedScheme?.id.toString() ?? '',
  set: (val: string) => {
    const selected = props.schemes.find(s => s.id.toString() === val)
    if (selected) {
      formStore.setScheme(selected)
    }
  }
})
</script>

<template>
  <Card>
    <CardContent class="pt-6">
      <div class="grid gap-4">
        <Label class="text-base font-medium">Pilih Skema Sertifikasi</Label>

        <Select v-model="modelValue">
          <SelectTrigger>
            <SelectValue placeholder="Pilih skema" />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup v-if="schemes.length > 0">
              <SelectItem 
                v-for="scheme in schemes" 
                :key="scheme.id" 
                :value="scheme.id.toString()"
              >
                {{ scheme.name }}
              </SelectItem>
            </SelectGroup>
            <p v-else class="px-2 py-1 text-sm text-gray-500">Tidak ada skema tersedia.</p>
          </SelectContent>
        </Select>
      </div>
    </CardContent>
  </Card>
</template>