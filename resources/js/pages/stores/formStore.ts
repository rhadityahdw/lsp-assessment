import { defineStore } from 'pinia'
import { Scheme } from '@/types';

interface DocumentState {
  ktp: File | null
  ijazah: File | null
  pas_foto: File | null
  bukti_kerja: File | null
  portofolio: File | null
}

interface FormState {
  activeStep: number
  selectedScheme: Scheme | null
  documents: DocumentState
  answers: Record<number, boolean>
}

export const useFormStore = defineStore('form', {
  state: (): FormState => ({
    activeStep: 0,
    selectedScheme: null,
    documents: {
      ktp: null,
      ijazah: null,
      pas_foto: null,
      bukti_kerja: null,
      portofolio: null
    },
    answers: {}
  }),
  actions: {
    nextStep() {
      if (this.activeStep < 2) this.activeStep++
    },
    prevStep() {
      if (this.activeStep > 0) this.activeStep--
    },
    setScheme(scheme: Scheme) {
      this.selectedScheme = scheme
    },
    setDocument(payload: { name: keyof DocumentState; file: File }) {
      this.documents[payload.name] = payload.file
    },
    reset() {
      this.activeStep = 0
      this.selectedScheme = null
      this.documents = {
        ktp: null,
        ijazah: null,
        pas_foto: null,
        bukti_kerja: null,
        portofolio: null
      }
      this.answers = {}
    }
  }
})