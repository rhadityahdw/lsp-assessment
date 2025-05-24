import { defineStore } from 'pinia';

export const useFormStore = defineStore('form', {
    state: () => ({
        activeStep: 0,
        scheme: {
            scheme_id: 0,
        },
        
    }),
})