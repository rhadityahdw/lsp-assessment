<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Label } from '@/components/ui/label';
import { Card, CardContent } from '@/components/ui/card';
import { Select, SelectContent, SelectItem,  } from '@/components/ui/select';
import PageHeaderComponent from '@/components/PageHeaderComponent.vue';
import { BreadcrumbItem } from '@/types';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import InputError from '@/components/InputError.vue';
import DatePicker from '@/components/DatePicker.vue';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { ref } from 'vue';

defineProps<{
    schemes: { id: number; name: string }[];
    asesors: { id: number; name: string }[];
    asesis: { id: number; name: string }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: route('dashboard')
    },
    {
        title: 'Jadwal',
        href: route('schedules.index')
    },
    {
        title: 'Tambah Jadwal',
        href: route('schedules.create')
    }
];

const selectedAsesis = ref<number[]>([]);

const form = useForm({
    scheme: '',
    asesor: '',
    schedule_time: '',
    location: '',
    status: 'draft',
    asesis: []
});

const toggleAsesi = (asesiId: number) => {
    const index = selectedAsesis.value.indexOf(asesiId);
    if (index > -1) {
        selectedAsesis.value.splice(index, 1);
    } else {
        if (selectedAsesis.value.length < 10) {
            selectedAsesis.value.push(asesiId);
        }
    }
    form.asesis = selectedAsesis.value;
};

const isAsesiSelected = (asesiId: number) => {
    return selectedAsesis.value.includes(asesiId);
};

const submitForm = () => {
    form.post(route('schedules.store'));
};
</script>

<template>
    <Head title="Tambah Jadwal" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="py-6 md:py-12">
            <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:max-w-7xl lg:px-8">
                <Card>
                    <PageHeaderComponent title="Tambah Jadwal" />
                    <form @submit.prevent="submitForm">
                        <CardContent class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <Label for="scheme">Pilih Skema</Label>
                                    <Select id="scheme" v-model="form.scheme">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Pilih skema" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="scheme in schemes" :key="scheme.id" :value="scheme.id.toString()">
                                                {{ scheme.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.scheme" />
                                </div>
                                <div class="space-y-2">
                                    <Label for="asesor">Pilih Asesor</Label>
                                    <Select id="asesor" v-model="form.asesor">
                                        <SelectTrigger class="w-full">
                                            <SelectValue placeholder="Pilih Asesor" />
                                        </SelectTrigger>
                                        <SelectContent>
                                            <SelectItem v-for="asesor in asesors" :key="asesor.id" :value="asesor.id.toString()">
                                                {{ asesor.name }}
                                            </SelectItem>
                                        </SelectContent>
                                    </Select>
                                    <InputError :message="form.errors.asesor" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="schedule_time">Tanggal Pelaksanaan</Label>
                                    <DatePicker id="schedule_time" v-model="form.schedule_time" />
                                    <InputError :message="form.errors.schedule_time" />
                                </div>
                                <div class="grid gap-2">
                                    <Label for="location">Lokasi Pelaksanaan</Label>
                                    <Input id="location" v-model="form.location" placeholder="Masukan lokasi pelaksanaan"/>
                                    <InputError :message="form.errors.location" />
                                </div>
                            </div>
                            
                            <!-- Section untuk memilih Asesi -->
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <Label class="text-lg font-semibold">Pilih Asesi (Maksimal 10)</Label>
                                    <span class="text-sm text-gray-500">{{ selectedAsesis.length }}/10 dipilih</span>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 max-h-60 overflow-y-auto border rounded-lg p-4">
                                    <div 
                                        v-for="asesi in asesis" 
                                        :key="asesi.id" 
                                        class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 cursor-pointer"
                                        @click="toggleAsesi(asesi.id)"
                                    >
                                        <Checkbox 
                                            :id="`asesi-${asesi.id}`"
                                            :checked="isAsesiSelected(asesi.id)"
                                            :disabled="!isAsesiSelected(asesi.id) && selectedAsesis.length >= 10"
                                        />
                                        <Label 
                                            :for="`asesi-${asesi.id}`" 
                                            class="text-sm cursor-pointer flex-1"
                                            :class="{
                                                'text-gray-400': !isAsesiSelected(asesi.id) && selectedAsesis.length >= 10
                                            }"
                                        >
                                            {{ asesi.name }}
                                        </Label>
                                    </div>
                                </div>
                                <InputError :message="form.errors.asesis" />
                                
                                <!-- Daftar asesi yang dipilih -->
                                <div v-if="selectedAsesis.length > 0" class="space-y-2">
                                    <Label class="text-sm font-medium">Asesi yang dipilih:</Label>
                                    <div class="flex flex-wrap gap-2">
                                        <span 
                                            v-for="asesiId in selectedAsesis" 
                                            :key="asesiId"
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                        >
                                            {{ asesis.find(a => a.id === asesiId)?.name }}
                                            <button 
                                                type="button"
                                                @click="toggleAsesi(asesiId)"
                                                class="ml-1 text-blue-600 hover:text-blue-800"
                                            >
                                                ×
                                            </button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex justify-end space-x-2">
                                <Button type="button" variant="outline" @click="$inertia.visit(route('schedules.index'))">
                                    Batal
                                </Button>
                                <Button type="submit" :disabled="form.processing">
                                    {{ form.processing ? 'Menyimpan...' : 'Simpan Jadwal' }}
                                </Button>
                            </div>
                        </CardContent>
                    </form>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>