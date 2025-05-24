<script setup lang="ts">
import { Label } from '@/components/ui/label';
import { Card, CardContent } from '@/components/ui/card';
import InputError from '@/components/InputError.vue';
import { Select, SelectTrigger, SelectContent, SelectGroup, SelectItem, SelectValue } from '@/components/ui/select';
import { Scheme } from '@/types';
import { useForm } from '@inertiajs/vue3';

const props = defineProps<{
    schemes: Scheme[],
    scheme_id: number
}>();

const form = useForm({
    scheme_id: props.scheme_id ?? '',
});


</script>

<template>
    <div class="min-h-screen">
        <div class="container mx-auto max-w-7xl py-8 px-4">
            <Card>
                <CardContent>
                    <form @submit.prevent="">
                        <div class="sm:grid-cols-[200px, 1fr] grid grid-cols-1 items-start gap-2 p-2 transition-colors sm:items-center sm:gap-3">
                                <Label for="borrower_name" class="text-base font-medium">Pilih Skema Sertifikasi</Label>
                                <Select v-model="form.scheme_id">
                                    <SelectTrigger class="w-full">
                                        <SelectValue placeholder="Pilih skema" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-if="schemes" v-for="scheme in schemes" :key="scheme.id" :value="scheme.id">
                                                {{ scheme.name }}
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                                <InputError :message="form.errors.scheme_id" />
                            </div>
                    </form>
                </CardContent>
            </Card>
        </div>
    </div>
</template>