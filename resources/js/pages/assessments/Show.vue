<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardFooter } from '@/components/ui/card';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import { BreadcrumbItem } from '@/types';

interface Assessment {
    id: number;
    name: string;
    type: string;
    link: string;
    scheme: {
        id: number;
        name: string;
    };
    created_by: {
        id: number;
        name: string;
    }
}

const props = defineProps<{
    assessment: Assessment;
}>();

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
        title: 'Detail Asesmen',
        href: route('assessments.show', props.assessment.id)
    }
];


</script>

<template>
    <Head title="Detail Asesmen" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Detail Asesmen"/>
                    <CardContent class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <Label class="text-md">Nama Asesmen</Label>
                                <p class="text-sm">{{ props.assessment.name }}</p>
                            </div>

                            <div class="space-y-1">
                                <Label class="text-md">Skema</Label>
                                <p class="text-sm">{{ props.assessment.scheme.name }}</p>
                            </div>

                            <div class="space-y-1">
                                <Label class="text-md">Tipe Asesmen</Label>
                                <p class="text-sm">{{ props.assessment.type }}</p>
                            </div>

                            <div class="space-y-1">
                                <Label class="text-md">Dibuat oleh</Label>
                                <p class="text-sm">{{ props.assessment.created_by.name }}</p>
                            </div>

                            <div class="col-span-full">
                                <Label class="text-md">Link Asesmen</Label>
                                <a
                                    :href="assessment.link"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="text-sm text-blue-600 hover:text-blue-800"
                                >
                                    {{ assessment.link }}
                                </a>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <div class="flex justify-between w-full">
                            <Link :href="route('assessments.index')">
                                <Button variant="outline">Kembali ke Daftar</Button>
                            </Link>
                        </div>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>