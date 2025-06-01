<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Asesor Asesmen',
        href: route('asesor-assessments'),
    }
]
</script>

<template>
    <Head title="Asesor Asesmen" />

    <AppLayout :breadcrumbs="breadcrumbs">

    </AppLayout>
</template>