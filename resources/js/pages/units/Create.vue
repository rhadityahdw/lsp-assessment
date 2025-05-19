<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent, CardAction, CardDescription, CardFooter } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import InputError from '@/components/InputError.vue';
import { Textarea } from '@/components/ui/textarea/index';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Unit, PreAssessment, BreadcrumbItem } from '@/types';

defineProps<{
    units: Unit[];
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
        title: 'Tambah Unit',
        href: route('units.create'),
    },
];

const form = useForm({
    code: '',
    name: '',
    pre_assessments: [
        {
            question: '',
            expected_answer: '',
        }
    ]
});

const addPreAssessment = () => {
    form.pre_assessments.push({
        question: '',
        expected_answer: '',
    });
};

const removePreAssessment = (index: number) => {
    if (form.pre_assessments.length > 1) {
        form.pre_assessments.splice(index, 1);
    }
};

const submit = () => {
    console.log('Form data:', form);
    form.post(route('units.store'));
};
</script>

<template>
    <Head title="Tambah Unit"/>

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <CardHeader>
                        <CardTitle class="text-xl md:text-2xl font-bold">Tambah Unit</CardTitle>
                    </CardHeader>
                    <form @submit.prevent="submit">
                        <CardContent class="space-y-6">
                            <!-- Informasi Unit -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-medium">Informasi Unit</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-2">
                                        <Label for="code">Kode Unit</Label>
                                        <Input id="code" v-model="form.code" placeholder="Masukkan kode unit" required />
                                        <p v-if="form.errors.code" class="text-sm text-red-500">{{ form.errors.code }}</p>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <Label for="name">Nama Unit</Label>
                                        <Input id="name" v-model="form.name" placeholder="Masukkan nama unit" required />
                                        <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Pre Assessment -->
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <h3 class="text-lg font-medium">Pre Assessment</h3>
                                    <Button type="button" variant="outline" size="sm" @click="addPreAssessment">
                                        Tambah Pertanyaan
                                    </Button>
                                </div>
                                
                                <p v-if="form.errors.pre_assessments" class="text-sm text-red-500">{{ form.errors.pre_assessments }}</p>
                                
                                <div v-for="(pre_assessment, index) in form.pre_assessments" :key="index" class="border rounded-md p-4 space-y-4">
                                    <div class="flex justify-between items-center">
                                        <h4 class="font-medium">Pertanyaan #{{ index + 1 }}</h4>
                                        <Button 
                                            type="button" 
                                            variant="destructive" 
                                            size="sm" 
                                            @click="removePreAssessment(index)"
                                            :disabled="form.pre_assessments.length <= 1"
                                        >
                                            Hapus
                                        </Button>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <Label :for="`question-${index}`">Pertanyaan</Label>
                                        <Textarea 
                                            :id="`question-${index}`" 
                                            v-model="pre_assessment.question" 
                                            placeholder="Masukkan pertanyaan pre-assessment"
                                            required
                                        />
                                        <InputError :message="(form.errors as any)[`pre_assessments.${index}.question`]"/>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <Label :for="`expected_answer-${index}`">Jawaban yang Diharapkan</Label>
                                        <Select v-model="pre_assessment.expected_answer">
                                            <SelectTrigger :id="`expected_answer-${index}`">
                                                <SelectValue placeholder="Pilih jawaban" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="yes">Ya</SelectItem>
                                                <SelectItem value="no">Tidak</SelectItem>
                                            </SelectContent>
                                        </Select>
                                        <InputError :message="(form.errors as any)[`pre_assessments.${index}.expected_answer`]"/>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                        <CardFooter>
                            <div class="flex justify-end gap-2">
                                <Link :href="route('units.index')">
                                    <Button type="button" variant="outline">Kembali</Button>
                                </Link>
                                <Button type="submit" variant="default" :disabled="form.processing">Simpan</Button>
                            </div>
                        </CardFooter>
                    </form>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>