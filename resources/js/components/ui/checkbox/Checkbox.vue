<script setup lang="ts">
import type { CheckboxRootEmits, CheckboxRootProps } from 'reka-ui'
import { cn } from '@/lib/utils'
import { Check } from 'lucide-vue-next'
import { CheckboxIndicator, CheckboxRoot, useForwardPropsEmits } from 'reka-ui'
import { ref, computed, type HTMLAttributes } from 'vue'

const props = withDefaults(
  defineProps<CheckboxRootProps & { 
    class?: HTMLAttributes['class']
    indeterminate?: boolean
    modelValue: boolean
  }>(),
  {
    indeterminate: false,
    modelValue: false
  }
)

const modelChecked = ref(false)

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const handleCheckedChange = (checked: boolean) => {
  emit('update:modelValue', checked)
}

const delegatedProps = computed(() => {
  const { class: _, ...delegated } = props

  return delegated
})

const forwarded = useForwardPropsEmits(delegatedProps, emit)
</script>

<template>
  <CheckboxRoot
    :checked="props.checked"
    @checked-change="handleCheckedChange"
    data-slot="checkbox"
    :data-state="indeterminate ? 'indeterminate' : undefined"
    v-bind="forwarded"
    :class="
      cn('peer border-input data-[state=checked]:bg-primary data-[state=checked]:text-primary-foreground data-[state=checked]:border-primary focus-visible:border-ring focus-visible:ring-ring/50 aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive size-4 shrink-0 rounded-[4px] border shadow-xs transition-shadow outline-none focus-visible:ring-[3px] disabled:cursor-not-allowed disabled:opacity-50',
         props.class)"
  >
    <CheckboxIndicator
      data-slot="checkbox-indicator"
      class="flex items-center justify-center text-current transition-none"
    >
      <slot>
        <Check class="size-3.5" />
      </slot>
    </CheckboxIndicator>
  </CheckboxRoot>
</template>
