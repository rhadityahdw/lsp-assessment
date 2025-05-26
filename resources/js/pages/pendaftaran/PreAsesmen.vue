<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useFormStore } from '@/pages/stores/formStore';
import { router } from '@inertiajs/vue3';
import { Card, CardContent } from '@/components/ui/card';
import { Label } from '@/components/ui/label';
import axios from 'axios';
import { PreAssessment } from '@/types';

const formStore = useFormStore();
const questions = ref<PreAssessment[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);

onMounted(async () => {
  if (!formStore.selectedScheme) {
    router.visit('/pendaftaran');
    return;
  }

  try {
    loading.value = true;
    const { data } = await axios.get(
      `/api/schemes/${formStore.selectedScheme.id}/pre-assessments`
    );
    questions.value = data;
  } catch (err) {
    error.value = 'Gagal memuat soal pre-asesmen';
  } finally {
    loading.value = false;
  }
});

const handleAnswer = (questionId: number, answer: boolean) => {
  formStore.answers[questionId] = answer;
};
</script>

<template>
  <Card>
    <CardContent class="pt-6">
      <div v-if="loading" class="text-center py-8">
        <Spinner class="w-8 h-8 mx-auto" />
        <p class="mt-2 text-sm text-muted-foreground">Memuat soal...</p>
      </div>

      <div v-else-if="error" class="text-red-500 p-4 text-center">
        {{ error }}
      </div>

      <div v-else class="space-y-8">
        <div 
          v-for="(question, index) in questions" 
          :key="question.id"
          class="p-4 rounded-lg border"
        >
          <div class="flex gap-4">
            <div class="w-8 shrink-0">
              <span class="text-muted-foreground">{{ index + 1 }}.</span>
            </div>
            <div class="flex-1 space-y-2">
              <h3 class="font-medium">{{ question.question }}</h3>
              <p class="text-sm text-muted-foreground">
                {{ question.unit.code }} - {{ question.unit.name }}
              </p>
              
              <div class="flex gap-4 mt-2">
                <div class="flex items-center space-x-2">
                  <input
                    type="radio"
                    :id="`q${question.id}-yes`"
                    :value="true"
                    :checked="formStore.answers[question.id] === true"
                    @change="handleAnswer(question.id, true)"
                    class="h-4 w-4 text-primary"
                  />
                  <Label :for="`q${question.id}-yes`">Ya</Label>
                </div>
                <div class="flex items-center space-x-2">
                  <input
                    type="radio"
                    :id="`q${question.id}-no`"
                    :value="false"
                    :checked="formStore.answers[question.id] === false"
                    @change="handleAnswer(question.id, false)"
                    class="h-4 w-4 text-primary"
                  />
                  <Label :for="`q${question.id}-no`">Tidak</Label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </CardContent>
  </Card>
</template>