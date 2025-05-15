<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

defineProps<{
  editRoute: string;
  showRoute: string;
  deleteRoute: string;
}>();
</script>

<template>
  <div class="flex space-x-3 justify-end">
    <Link :href="editRoute" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300" title="Edit">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 0L11.828 15.1l-3.414.586.586-3.414 9.586-9.586z" />
      </svg>
    </Link>
    <Link :href="showRoute" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" title="Show">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
      </svg>
    </Link>
    <Link 
      :href="deleteRoute"
      method="delete"
      as="button"
      class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300"
      onclick="return confirm('Are you sure you want to delete this user?')"
      title="Delete"
      >
      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
      </svg>
    </Link>
  </div>
</template>