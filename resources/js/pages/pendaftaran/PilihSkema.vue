<script setup lang="ts">
import { useFormStore } from '@/pages/stores/formStore';
import { Card, CardContent } from '@/components/ui/card';
import {
  Select,
  SelectTrigger,
  SelectValue,
  SelectContent,
  SelectGroup,
  SelectItem,
} from '@/components/ui/select';
import { Label } from '@/components/ui/label';
import { computed } from 'vue';
import { Scheme } from '@/types';
import { BadgeCheck } from 'lucide-vue-next';

const props = defineProps<{
  schemes: Scheme[];
}>();

const formStore = useFormStore();

const modelValue = computed({
  get: () => formStore.selectedScheme?.id.toString() ?? '',
  set: (val: string) => {
    const selected = props.schemes.find((s) => s.id.toString() === val);
    if (selected) {
      formStore.setScheme(selected);
    }
  },
});
</script>

<template>
  <Card class="shadow-sm border rounded-2xl">
    <CardContent>
      <div class="grid gap-4">
        <div class="flex items-center gap-2">
          <Label class="text-base font-semibold text-gray-800">Pilih Skema Sertifikasi</Label>
        </div>

        <Select v-model="modelValue">
          <SelectTrigger class="h-11 rounded-lg border-gray-300 focus:ring-cyan-600 focus:border-cyan-600 w-full">
            <SelectValue placeholder="Pilih skema sertifikasi..." />
          </SelectTrigger>
          <SelectContent>
            <SelectGroup v-if="schemes.length > 0">
              <SelectItem
                v-for="scheme in schemes"
                :key="scheme.id"
                :value="scheme.id.toString()"
                class="cursor-pointer"
              >
                {{ scheme.name }}
              </SelectItem>
            </SelectGroup>
            <div v-else class="px-3 py-2 text-sm text-gray-500">Tidak ada skema tersedia.</div>
          </SelectContent>
        </Select>
      </div>
    </CardContent>
  </Card>
</template>
