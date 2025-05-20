<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/layouts/AppLayout.vue";
import { Card, CardHeader, CardTitle, CardContent, CardDescription } from "@/components/ui/card";
import { BreadcrumbItem } from "@/types";

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard')
    },
    {
        title: 'Asesmen',
        href: route('assessments.index')
    },
    {
        title: 'Tambah Asesmen Baru',
        href: route('assessments.create')
    }
]
</script>

<template>
    <Head title="Buat Asesmen Baru" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-xl md:text-2xl">Buat Asesmen</CardTitle>
                    </CardHeader>
                    <form @submit.prevent>
                        <CardContent class="space-y-6">
                            <!-- Informasi Asesmen -->
                            <CardDescription>
                                Tambahkan informasi dasar untuk membuat asesmen baru.
                            </CardDescription>
                            <div class="grid gap-2">
                                <Label for="name">Nama</Label>
                            </div>
                        </CardContent>
                    </form>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>