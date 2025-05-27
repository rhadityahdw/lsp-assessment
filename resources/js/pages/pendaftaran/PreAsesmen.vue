<script setup lang="ts">
import { onMounted, ref, computed } from 'vue';
import { useFormStore } from '@/pages/stores/formStore';
import { router } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import { RadioGroup, RadioGroupItem } from '@/components/ui/radio-group';
import { Card, CardContent } from '@/components/ui/card';
import { AlertTriangle, CheckCircle, AlertCircle } from 'lucide-vue-next';

const formStore = useFormStore();

const loading = ref(false);
const error = ref<string | null>(null);

// Tangani struktur units (bisa berupa array atau { data: [...] })
const groupedQuestions = computed(() => {
  const unitsRaw = formStore.selectedScheme?.units;

  console.log(unitsRaw);
  
  const units = Array.isArray(unitsRaw) ? unitsRaw : unitsRaw ?? [];

  return units.map(unit => ({
    ...unit,
    pre_assessments: unit.pre_assessments?.map(question => ({
      ...question,
      unit: {
        code: unit.code,
        name: unit.name
      }
    })) || []
  }));
});

const totalQuestions = computed(() =>
  groupedQuestions.value.reduce(
    (acc, unit) => acc + (unit.pre_assessments?.length || 0),
    0
  )
);

const answeredQuestions = computed(() =>
  Object.keys(formStore.answers).length
);

const handleAnswer = (questionId: number, answer: boolean) => {
  formStore.setAnswer(questionId, answer);
};

onMounted(() => {
  if (!formStore.selectedScheme) {
    router.visit('/pendaftaran');
  }
});
</script>

<template>
  <Card>
    <CardContent class="space-y-6">
      <!-- Progress Indicator -->
      <div class="flex items-center justify-between mb-6 p-4 bg-accent rounded-lg">
        <div>
          <h3 class="font-semibold">Progress Pre-Asesmen</h3>
          <p class="text-sm text-muted-foreground">
            {{ answeredQuestions }} dari {{ totalQuestions }} pertanyaan terjawab
          </p>
        </div>
        <div class="relative w-24 h-24">
          <svg class="w-full h-full" viewBox="0 0 100 100">
            <circle
              class="text-gray-200"
              stroke-width="8"
              stroke="currentColor"
              fill="transparent"
              r="40"
              cx="50"
              cy="50"
            />
            <circle
              class="text-primary"
              stroke-width="8"
              :stroke-dasharray="`${(answeredQuestions / totalQuestions) * 251} 251`"
              stroke-linecap="round"
              stroke="currentColor"
              fill="transparent"
              r="40"
              cx="50"
              cy="50"
              transform="rotate(-90 50 50)"
            />
          </svg>
          <span class="absolute inset-0 flex items-center justify-center font-bold">
            {{ Math.round((answeredQuestions / totalQuestions) * 100) || 0 }}%
          </span>
        </div>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="p-4 bg-destructive/10 border border-destructive rounded-lg">
        <div class="flex items-center gap-3 text-destructive">
          <AlertTriangle class="w-5 h-5" />
          <p class="font-medium">{{ error }}</p>
        </div>
      </div>

      <!-- Pertanyaan Per Unit -->
      <div class="space-y-8">
        <div
          v-for="(unit, unitIndex) in groupedQuestions"
          :key="unit.id"
          class="space-y-4"
        >
          <!-- Header Unit -->
          <div class="sticky top-0 z-10 py-4 bg-background border-b">
            <div class="flex items-center gap-3">
              <Badge variant="secondary" class="text-lg px-3 py-1">
                Unit {{ unitIndex + 1 }}
              </Badge>
              <div>
                <h2 class="font-semibold">{{ unit.code }} - {{ unit.name }}</h2>
                <p class="text-sm text-muted-foreground">
                  {{ unit.pre_assessments.length }} Pertanyaan
                </p>
              </div>
            </div>
          </div>

          <!-- Daftar Pertanyaan -->
          <RadioGroup class="space-y-4">
            <div
              v-for="(question, questionIndex) in unit.pre_assessments"
              :key="question.id"
              class="p-4 rounded-lg border hover:border-primary transition-colors"
              :class="{ 'border-primary': formStore.answers[question.id] !== undefined }"
            >
              <div class="flex gap-4 items-start">
                <div class="w-8 shrink-0 pt-1">
                  <span class="text-muted-foreground font-medium">
                    {{ questionIndex + 1 }}.
                  </span>
                </div>
                <div class="flex-1 space-y-3">
                  <h3 class="font-medium text-base">
                    {{ question.question }}
                  </h3>

                  <!-- Opsi Jawaban -->
                  <div class="flex flex-col sm:flex-row gap-4 mt-2">
                    <Label class="flex items-center space-x-2 cursor-pointer p-2 rounded hover:bg-accent">
                      <RadioGroupItem
                        value="true"
                        :id="`q${question.id}-yes`"
                        @click="handleAnswer(question.id, true)"
                      />
                      <span>Ya</span>
                    </Label>

                    <Label class="flex items-center space-x-2 cursor-pointer p-2 rounded hover:bg-accent">
                      <RadioGroupItem
                        value="false"
                        :id="`q${question.id}-no`"
                        @click="handleAnswer(question.id, false)"
                      />
                      <span>Tidak</span>
                    </Label>
                  </div>
                </div>

                <!-- Status Jawaban -->
                <div class="w-12 shrink-0 text-right">
                  <CheckCircle
                    v-if="formStore.answers[question.id] !== undefined"
                    class="w-5 h-5 text-green-600"
                  />
                  <AlertCircle
                    v-else
                    class="w-5 h-5 text-red-400"
                  />
                </div>
              </div>
            </div>
          </RadioGroup>
        </div>
      </div>
    </CardContent>
  </Card>
</template>

<style scoped>
circle {
  transition: stroke-dasharray 0.3s ease;
}
.sticky {
  position: -webkit-sticky;
  position: sticky;
}
</style>
