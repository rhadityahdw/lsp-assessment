<script setup lang="ts">
import { Head, useForm, router } from '@inertiajs/vue3';
import { useFormStore } from './stores/formStore';
import { Button } from '@/components/ui/button';
import PilihSkema from './pendaftaran/PilihSkema.vue';
import UploadDokumen from './pendaftaran/UploadDokumen.vue';
import PreAsesmen from './pendaftaran/PreAsesmen.vue';
import { computed } from 'vue';
import { Stepper, StepperIndicator, StepperItem, StepperSeparator, StepperTitle, StepperTrigger } from '@/components/ui/stepper';
import { Book, DownloadCloud, FileQuestion } from 'lucide-vue-next';

interface Scheme {
    id: number;
    code: string;
    name: string;
    type: string;
    units: Array<{
        id: number;
        name: string;
        code: string;
        pre_assessments: Array<{
            id: number;
            question: string;
        }>
    }>
}

const props = defineProps<{
    schemes: Scheme[];
}>();

const formState = useFormStore();


const steps = [
    {
        id: 0,
        title: 'Pilih Skema',
        component: PilihSkema,
        icon: Book,
        required: true,
    },
    {
        id: 1,
        title: 'Upload Dokumen',
        component: UploadDokumen,
        icon: DownloadCloud,
        required: true,
    },
    {
        id: 2,
        title: 'Pre Asesmen',
        component: PreAsesmen,
        icon: FileQuestion,
        required: true,
    }
];

const currentComponent = computed(() => steps[formState.activeStep].component);

const canProceed = computed(() => {
    switch (formState.activeStep) {
        case 0:
            return formState.selectedScheme !== null;
        case 1:
            const docs = formState.documents;
            return Boolean(
                docs.ktp &&
                docs.ijazah &&
                docs.pas_foto
            );
        case 2:
            if (!formState.selectedScheme) return false;

            console.log(formState.selectedScheme)

            const totalQuestions = formState.selectedScheme.units
                    ? formState.selectedScheme.units.flatMap((unit: any) =>
                        unit.pre_assessments ? unit.pre_assessments : []
                        ).length
                    : 0;

            const answeredCount = Object.keys(formState.answers).length;

            return answeredCount === totalQuestions;
        default:
            return false;
    }
})

const submitForm = () => {
    const formData = new FormData();

    if (formState.selectedScheme?.id) {
        formData.append('scheme_id', formState.selectedScheme.id.toString());
    }

    const documentKeys = ['ktp', 'ijazah', 'pas_foto', 'bukti_kerja', 'portofolio'] as const;

    documentKeys.forEach((key) => {
        const file = formState.documents[key];
        if (file) {
            formData.append(key, file);
        }
    });

    Object.entries(formState.answers).forEach(([key, value], index) => {
        formData.append(`pre_assessment_answers[${index}][id]`, key);
        formData.append(`pre_assessment_answers[${index}][answer]`, value ? '1' : '0');
    });

    router.post(route('attempt.store'), formData, {
        forceFormData: true,
        onSuccess: () => {
            formState.reset(); // reset state Pinia
        },
        onError: (errors: any) => {
        console.error('Validation errors:', errors);
        }
    });
}
</script>

<template>
    <Head title="Pendaftaran" />

    <div class="bg-gradient-to-r from-cyan-500 to-cyan-700 p-4 text-white shadow-md mb-4">
        <h1 class="pl-2 text-2xl font-bold">Pendaftaran Sertifikasi</h1>
    </div>

    <div class="container mx-auto px-4 py-6">
        <Stepper class="flex w-full items-start gap-4 max-w-4xl mx-auto mb-10">
            <StepperItem
                v-for="(step, index) in steps"
                :key="index"
                :step="index + 1"
                class="relative flex w-full flex-col items-center justify-center"
            >
                <StepperSeparator
                    v-if="index < steps.length - 1"
                    class="absolute left-[calc(50%+24px)] right-[calc(-50%+16px)] top-5 h-1 rounded-full transition-all duration-300 mx-1"
                    :class="{
                        'bg-cyan-500': index < formState.activeStep,
                        'bg-gray-300': index >= formState.activeStep,
                    }"
                />
                <StepperTrigger as-child>
                    <div
                        class="z-10 w-10 h-10 flex items-center justify-center rounded-full border-2 transition-all duration-300"
                        :class="{
                            'bg-cyan-700 text-white border-cyan-700 shadow-lg': formState.activeStep === index,
                            'bg-white text-gray-500 border-gray-300': formState.activeStep < index,
                            'bg-cyan-500 text-white border-cyan-500': formState.activeStep > index
                        }"
                    >
                        <component :is="step.icon" class="w-5 h-5" />
                    </div>
                </StepperTrigger>

                <div class="flex flex-col items-center text-center">
                    <StepperTitle
                        :class="{
                            'text-cyan-700': formState.activeStep === index,
                            'text-gray-400': formState.activeStep !== index
                        }"
                        class="text-sm font-medium"
                    >
                        {{ step.title }}
                    </StepperTitle>
                </div>
            </StepperItem>
        </Stepper>

        <component
            :is="currentComponent"
            :schemes="props.schemes"
            @next="formState.nextStep"
            @prev="formState.prevStep"
        />

        <div class="mt-8 flex gap-4 justify-end">
            <Button
                v-if="formState.activeStep > 0"
                @click="formState.prevStep"
                class="outline"
            >Kembali</Button>

            <Button
                v-if="formState.activeStep < steps.length - 1"
                @click="formState.nextStep"
                :disabled="!canProceed"
            >Lanjut</Button>

            <Button
                v-if="formState.activeStep === steps.length - 1"
                @click="submitForm"
                :disabled="!canProceed"
            >Submit</Button>
        </div>
    </div>
</template>