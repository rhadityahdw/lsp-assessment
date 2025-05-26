<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { ArrowLeft, Home } from 'lucide-vue-next';
</script>

<template>
    <div class="flex min-h-screen flex-col items-center justify-center gap-8 p-24 text-center">
        <Head title="Akses Tidak Diizinkan" />

        <div class="space-y-4">
            <h1 class="text-4xl font-bold tracking-tight">Akses Tidak Diizinkan</h1>
            <p class="text-lg text-muted-foreground">
                Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
            </p>
        </div>

        <div class="flex gap-4">
            <Button>
                <ArrowLeft class="mr-2 h-4 w-4" />
                Kembali
            </Button>

            <Button :href="route('home')">
                <Home class="mr-2 h-4 w-4" />
                Beranda
            </Button>
        </div>
    </div>
</template>