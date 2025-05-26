<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Stepper, StepperItem, StepperTrigger, StepperTitle, StepperSeparator } from '@/components/ui/stepper';
import { useFormStore } from '@/pages/stores/formStore';
import PilihSkema from '@/pages/pendaftaran/PilihSkema.vue';
import UploadDokumen from '@/pages/pendaftaran/UploadDokumen.vue';
import PreAsesmen from '@/pages/pendaftaran/PreAsesmen.vue';
import { Button } from '@/components/ui/button';
import { computed } from 'vue';
import type { Scheme } from '@/types';

interface SchemeItem {
  id: number
  name: string
  code: string
  units: Array<{
    code: string
    name: string
  }>
}

const props = defineProps<{
  schemes: Scheme[]
}>()

const formStore = useFormStore()

const steps = [
  { id: 0, title: 'Pilih Skema', component: PilihSkema, required: true },
  { id: 1, title: 'Upload Dokumen', component: UploadDokumen, required: true },
  { id: 2, title: 'Pre Asesmen', component: PreAsesmen, required: true }
]

const CurrentComponent = computed(() => steps[formStore.activeStep].component)

const canProceed = computed(() => {
  switch(formStore.activeStep) {
    case 0:
      return !!formStore.selectedScheme
    case 1:
      return Boolean(
        formStore.documents.ktp &&
        formStore.documents.ijazah &&
        formStore.documents.pas_foto
      )
    case 2:
      return Object.keys(formStore.answers).length === steps[2].component.props.questions?.length
    default:
      return false
  }
})

const submitForm = async () => {
  try {
    const formData = new FormData()
    
    // Append documents
    Object.entries(formStore.documents).forEach(([key, file]) => {
      if (file) formData.append(key, file)
    })
    
    // Append answers
    formData.append('answers', JSON.stringify(formStore.answers))
    
    // Append scheme ID
    if (formStore.selectedScheme) {
      formData.append('scheme_id', formStore.selectedScheme.id.toString())
    }

    await window.axios.post('/attempts', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    formStore.reset()
    router.visit('/pendaftaran/success')
  } catch (error) {
    console.error('Submission failed:', error)
  }
}
</script>

<template>
  <Head title="Pendaftaran Sertifikasi" />
  
  <div class="bg-gradient-to-r from-cyan-600 to-cyan-700 p-4 text-white shadow-md mb-4">
    <h1 class="pl-2 text-2xl font-bold">Pendaftaran Sertifikasi</h1>
  </div>

  <div class="container mx-auto px-4 py-6">
    <Stepper :value="formStore.activeStep" class="mb-8">
      <StepperItem 
        v-for="step in steps" 
        :step="step.id"
        :key="step.id" 
        :value="step.id"
        :class="{
          'opacity-50': formStore.activeStep < step.id,
          'text-primary': formStore.activeStep >= step.id
        }"
      >
        <StepperTrigger :completed="formStore.activeStep > step.id">
          <StepperTitle>{{ step.title }}</StepperTitle>
        </StepperTrigger>
        <StepperSeparator v-if="step.id < steps.length - 1" />
      </StepperItem>
    </Stepper>

    <component 
      :is="CurrentComponent" 
      :schemes="props.schemes"
      @next="formStore.nextStep"
      @prev="formStore.prevStep"
    />

    <div class="mt-8 flex gap-4 justify-end">
      <Button 
        v-if="formStore.activeStep > 0"
        @click="formStore.prevStep"
        variant="outline"
      >
        Kembali
      </Button>
      
      <Button 
        v-if="formStore.activeStep < steps.length - 1"
        @click="formStore.nextStep"
        :disabled="!canProceed"
      >
        Lanjut
      </Button>
      
      <Button 
        v-if="formStore.activeStep === steps.length - 1"
        @click="submitForm"
        :disabled="!canProceed"
      >
        Submit Pendaftaran
      </Button>
    </div>
  </div>
</template>