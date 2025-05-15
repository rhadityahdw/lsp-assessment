<script setup lang="ts">
import { CardHeader, CardTitle } from '@/components/ui/card';

defineProps<{
  title: string;
}>();
</script>

<template>
  <CardHeader class="flex justify-between items-center">
    <CardTitle class="text-xl md:text-2xl font-bold">{{ title }}</CardTitle>
    <slot></slot>
  </CardHeader>
</template>