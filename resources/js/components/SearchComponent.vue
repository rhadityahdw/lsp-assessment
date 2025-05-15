<script setup lang="ts">
import { Input } from '@/components/ui/input';
import { Search, X } from 'lucide-vue-next';
import { ref, watch } from 'vue';

const props = defineProps<{
  placeholder?: string;
  modelValue: string;
}>();

const emit = defineEmits<{
  (e: 'update:modelValue', value: string): void;
}>();

const searchQuery = ref(props.modelValue);

watch(searchQuery, (newValue) => {
  emit('update:modelValue', newValue);
});

const clearSearch = () => {
  searchQuery.value = '';
  emit('update:modelValue', '');
};
</script>

<template>
  <div class="relative">
    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
      <Search class="h-4 w-4 text-gray-500" />
    </div>
    <Input
      v-model="searchQuery"
      type="text"
      :placeholder="placeholder || 'Cari..'"
      class="pl-10 pr-10 w-full border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm"
    />
    <button 
      v-if="searchQuery" 
      @click="clearSearch" 
      class="absolute inset-y-0 right-0 flex items-center pr-3"
    >
      <X class="h-4 w-4 text-gray-500 hover:text-gray-700" />
    </button>
  </div>
</template>

<style scoped>
/* Hide browser's clear button for WebKit browsers */
input[type="search"]::-webkit-search-cancel-button {
  -webkit-appearance: none;
  appearance: none;
  display: none;
}
</style>