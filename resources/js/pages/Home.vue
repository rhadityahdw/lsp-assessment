<script setup lang="ts">
import Navbar from '@/components/Navbar.vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

console.log((page.props.auth as any).user);
</script>

<template>
    <Navbar />
</template>