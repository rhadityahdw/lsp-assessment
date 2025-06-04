import { defineStore } from 'pinia'
import { Scheme } from '@/types'
import { markRaw } from 'vue'

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

const initialDocuments = (): DocumentState => ({
  ktp: null,
  ijazah: null,
  pas_foto: null,
  bukti_kerja: null,
  portofolio: null,
})

type DocumentKey = keyof DocumentState;

export const useFormStore = defineStore('form', {
  state: (): FormState => ({
    activeStep: 0,
    selectedScheme: null,
    documents: initialDocuments(),
    answers: {},
  }),

  actions: {
    nextStep() {
      if (this.activeStep < 2) {
        this.activeStep++
      }
    },
    prevStep() {
      if (this.activeStep > 0) {
        this.activeStep--
      }
    },
    setScheme(scheme: Scheme) {
      this.selectedScheme = scheme
    },
    setDocument({ name, file }: { name: DocumentKey; file: File }) {
      this.documents[name] = (file)
    },
    setAnswer(preAssessmentId: number, value: boolean) {
      this.answers = {
        ...this.answers,
        [preAssessmentId]: value
      };
    },
    reset() {
      this.activeStep = 0
      this.selectedScheme = null
      this.documents = initialDocuments()
      this.answers = {}
    },
  },

  getters: {
    isFirstStep: (state) => state.activeStep === 0,
    isLastStep: (state) => state.activeStep === 2,
  },
})
