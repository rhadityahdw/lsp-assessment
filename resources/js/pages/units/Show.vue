<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent, CardFooter } from '@/components/ui/card';
import { BreadcrumbItem, Unit, PreAssessment } from '@/types';
import { Pencil, FileText, CheckCircle, XCircle, ClipboardList } from 'lucide-vue-next';

const props = defineProps<{
    unit: Unit;
    pre_assessments: PreAssessment[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard'),
    },
    {
        title: 'Unit',
        href: route('units.index'),
    },
    {
        title: props.unit.name,
        href: route('units.show', props.unit.id),
    },
];
</script>

<template>
    <Head :title="unit.name"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <!-- Unit Info Card -->
                <Card class="mb-6">
                    <CardHeader class="pb-3">
                        <div class="flex items-center justify-between">
                            <CardTitle class="text-xl md:text-2xl font-bold">Detail Unit</CardTitle>
                            <Link :href="route('units.edit', unit.id)">
                                <Button size="sm">
                                    <Pencil class="mr-2 h-4 w-4" />
                                    Edit Unit
                                </Button>
                            </Link>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Kode Unit</div>
                                <div class="font-medium flex items-center">
                                    <FileText class="h-4 w-4 mr-2 text-primary" />
                                    {{ unit.code }}
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Nama Unit</div>
                                <div class="font-medium">{{ unit.name }}</div>
                            </div>
                            <div class="space-y-2">
                                <div class="text-sm text-gray-500">Tipe Unit</div>
                                <div class="font-medium">{{ unit.type }}</div>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Pre Assessment Card -->
                <Card>
                    <CardHeader class="pb-3">
                        <CardTitle class="text-lg font-bold flex items-center">
                            <ClipboardList class="h-5 w-5 mr-2 text-primary" />
                            Daftar Pertanyaan Pre-Assessment
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div v-if="(unit.pre_assessments || []).length === 0" class="text-center py-8 text-gray-500">
                            Belum ada pertanyaan pre-assessment untuk unit ini.
                        </div>
                        <div v-else class="space-y-4">
                            <div v-for="(assessment, index) in (unit.pre_assessments || [])" :key="index" 
                                class="border rounded-md p-4 hover:bg-gray-50 transition-colors">
                                <div class="flex justify-between items-start">
                                    <div class="space-y-2 flex-1">
                                        <div class="font-medium">Pertanyaan #{{ index + 1 }}</div>
                                        <p class="text-gray-700">{{ assessment.question }}</p>
                                    </div>
                                    <div class="ml-4 flex items-center gap-1 bg-gray-100 px-3 py-1 rounded-full">
                                        <span class="text-sm font-medium">Jawaban yang diharapkan:</span>
                                        <span class="flex items-center gap-1 font-medium">
                                            <CheckCircle v-if="assessment.expected_answer === 'yes'" class="h-4 w-4 text-green-500" />
                                            <XCircle v-else class="h-4 w-4 text-red-500" />
                                            {{ assessment.expected_answer === 'yes' ? 'Ya' : 'Tidak' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <div class="flex justify-between w-full">
                            <Link :href="route('units.index')">
                                <Button variant="outline">Kembali ke Daftar</Button>
                            </Link>
                            <div class="text-sm text-gray-500">
                                Total: {{ (unit.pre_assessments || []).length }} pertanyaan
                            </div>
                        </div>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>