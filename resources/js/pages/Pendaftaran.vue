<script setup lang="ts">
import { Head, useForm, Link, usePage } from '@inertiajs/vue3';
import { useFormStore } from './stores/formStore';
import { Button } from '@/components/ui/button';
import PilihSkema from './pendaftaran/PilihSkema.vue';
import UploadDokumen from './pendaftaran/UploadDokumen.vue';
import PreAsesmen from './pendaftaran/PreAsesmen.vue';
import { computed, onMounted, ref } from 'vue';
import { Stepper,StepperItem, StepperSeparator, StepperTitle, StepperTrigger } from '@/components/ui/stepper';
import { Book, DownloadCloud, FileQuestion, AlertCircle } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription } from '@/components/ui/dialog';

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
    hasProfile?: boolean;
}>();

const formState = useFormStore();
const page = usePage();
const showProfileModal = ref(false);

// Check if user has profile
const { auth }: any = page.props;
const hasUserProfile = computed(() => {
    return props.hasProfile ?? auth.user?.profile !== null;
});

// Show modal on mount if no profile
onMounted(() => {
    if (!hasUserProfile.value) {
        showProfileModal.value = true;
    }
});

const closeModal = () => {
    showProfileModal.value = false;
    // Redirect to home or previous page
    router.visit(route('home'));
};

const goToProfile = () => {
    router.visit(route('profile.index'));
};

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
    <div>
        <Head title="Pendaftaran" />
        
        <!-- Profile Required Modal -->
        <Dialog :open="showProfileModal" @update:open="closeModal">
            <DialogContent class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle class="flex items-center space-x-2 text-amber-600">
                        <AlertCircle class="h-5 w-5" />
                        <span>Profil Diperlukan</span>
                    </DialogTitle>
                    <DialogDescription class="text-left">
                        Anda harus melengkapi profil terlebih dahulu sebelum dapat mengakses halaman pendaftaran sertifikasi.
                    </DialogDescription>
                </DialogHeader>
                
                <div class="bg-amber-50 p-4 rounded-lg border border-amber-200">
                    <div class="flex items-start space-x-3">
                        <AlertCircle class="h-5 w-5 text-amber-600 flex-shrink-0 mt-0.5" />
                        <div>
                            <h4 class="font-medium text-amber-800">Profil Belum Lengkap</h4>
                            <p class="text-sm text-amber-700 mt-1">
                                Untuk melanjutkan proses pendaftaran, silakan lengkapi profil Anda terlebih dahulu dengan mengisi informasi pribadi dan data perusahaan.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-3 pt-4">
                    <Button @click="goToProfile" class="flex-1 bg-gradient-to-r from-cyan-600 to-cyan-700 hover:from-cyan-700 hover:to-cyan-800">
                        Lengkapi Profil
                    </Button>
                    <Button @click="closeModal" variant="outline" class="flex-1">
                        Kembali ke Beranda
                    </Button>
                </div>
            </DialogContent>
        </Dialog>
        
        <!-- Existing content - only show if profile exists -->
        <div v-if="hasUserProfile">
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
                    <Link
                        v-if="formState.activeStep === 0"
                        :href="route('home')"
                    >
                        <Button
                            
                            variant="outline"
                        >Beranda</Button>
                    </Link>
                    
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
        </div>
        
        <!-- Alternative: Show message if no profile -->
        <div v-else class="min-h-screen flex items-center justify-center bg-gray-50">
            <div class="max-w-md mx-auto text-center p-6">
                <AlertCircle class="h-16 w-16 text-amber-500 mx-auto mb-4" />
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Profil Diperlukan</h2>
                <p class="text-gray-600 mb-6">
                    Silakan lengkapi profil Anda terlebih dahulu untuk mengakses halaman pendaftaran.
                </p>
                <div class="space-y-3">
                    <Button @click="goToProfile" class="w-full bg-gradient-to-r from-cyan-600 to-cyan-700 hover:from-cyan-700 hover:to-cyan-800">
                        Lengkapi Profil
                    </Button>
                    <Button @click="closeModal" variant="outline" class="w-full">
                        Kembali ke Beranda
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>