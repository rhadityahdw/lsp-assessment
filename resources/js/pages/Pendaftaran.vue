<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import PilihSkema from './pendaftaran/PilihSkema.vue';
import UploadDokumen from './pendaftaran/UploadDokumen.vue';
import { Stepper, StepperItem, StepperTrigger, StepperTitle, StepperSeparator } from '@/components/ui/stepper';
import { Scheme } from '@/types';
import { ref, computed } from 'vue';
import { useFormStore } from './stores/formStore';

const props = defineProps<{
    schemes: Scheme[];
}>();

const selectedSchemeId = ref(0);

const formStore = useFormStore();

const steps = [
    {
        id: 0,
        title: 'Pilih Skema Sertifikasi',
        component: 'pilih-skema',
    },
    {
        id: 1,
        title: 'Upload Dokumen',
        component: 'upload-dokumen',
    },
    {
        id: 2,
        title: 'Pra Asesmen',
        component: 'pra-asesmen',
    },
];

const activeComponent = computed(() => {
    return steps[formStore.activeStep].component;
})
</script>

<template>
    <Head title="Pendaftaran"/>

    <div class="bg-gradient-to-r from-cyan-600 to-cyan-700 p-4 text-white shadow-md dark:text-gray-200 mb-4">
        <h1 class="pl-2 text-2xl font-bold">Pendaftaran Sertifikasi</h1>
    </div>

    <div class="container mx-auto px-4 py-6">
        <Stepper
            :value="formStore.activeStep"
            class="flex w-full items-start gap-2 mb-8"
            >
            <StepperItem
                v-for="step in steps"
                :key="step.id"
                :value="step.id"
                :completed="formStore.activeStep >= step.id"
                >

            </StepperItem>

        </Stepper>
    </div>

    <div v-if="activeComponent === 'pilih-skema'">
        <PilihSkema :schemes="props.schemes" :scheme_id="selectedSchemeId"/>
    </div>
    <div v-if="activeComponent === 'upload-dokumen'">
        <UploadDokumen />
    </div>
</template>