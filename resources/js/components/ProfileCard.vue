<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { UserCircle } from 'lucide-vue-next';

defineProps<{
  title: string;
  entity: any;
  avatar?: string;
  avatarIcon?: any;
  avatarBgColor?: string;
  avatarIconColor?: string;
  badgeText?: string;
  badgeClass?: string;
  infoSections: {
    icon?: any;
    iconBgColor?: string;
    iconColor?: string;
    label: string;
    value: string;
  }[];
  detailSections?: {
    title: string;
    items: {
      label: string;
      value: any;
    }[];
  }[];
  actions?: {
    label: string;
    route: string;
    variant?: string;
    hoverClass?: string;
  }[];
}>();

const defaultAvatarBgColor = 'bg-primary-100';
const defaultAvatarIconColor = 'text-primary-600';
const defaultIconBgColor = 'bg-primary-50 dark:bg-primary-900';
const defaultIconColor = 'text-primary-600 dark:text-primary-400';
</script>

<template>
  <Card>
    <CardHeader class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b">
      <CardTitle class="text-xl md:text-2xl font-bold">{{ title }}</CardTitle>
      <div class="flex flex-wrap gap-2">
        <Link v-for="action in actions" :key="action.label" :href="action.route">
          <Button 
            :variant="(action.variant as 'link' | 'default' | 'outline' | 'destructive' | 'secondary' | 'ghost' | null | undefined) || 'outline'"
            :class="action.hoverClass || 'hover:bg-dark-100 transition-colors'"
          >
            {{ action.label }}
          </Button>
        </Link>
      </div>
    </CardHeader>
    <CardContent class="p-6">
      <!-- Profile Header -->
      <div class="flex flex-col items-center mb-8 p-6 bg-dark-100 dark:bg-dark-800 rounded-lg">
        <div 
          class="w-24 h-24 rounded-full flex items-center justify-center mb-4"
          :class="avatarBgColor || defaultAvatarBgColor"
        >
          <component 
            :is="avatarIcon || UserCircle" 
            class="w-16 h-16" 
            :class="avatarIconColor || defaultAvatarIconColor" 
          />
        </div>
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center">{{ entity.name }}</h2>
        <div v-if="badgeText" class="mt-2">
          <span :class="['px-3 py-1 text-sm font-medium rounded-full', badgeClass]">
            {{ badgeText }}
          </span>
        </div>
      </div>

      <!-- Info Sections -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div 
          v-for="(section, index) in infoSections" 
          :key="index"
          class="flex items-center p-4  rounded-lg shadow-sm border border-dark-200 dark:border-dark-600"
        >
          <div 
            v-if="section.icon" 
            class="mr-4 p-3 rounded-full"
            :class="section.iconBgColor || defaultIconBgColor"
          >
            <component 
              :is="section.icon" 
              class="w-6 h-6" 
              :class="section.iconColor || defaultIconColor" 
            />
          </div>
          <div>
            <p class="text-sm text-dark-500 dark:text-dark-400">{{ section.label }}</p>
            <p class="font-medium text-dark-900 dark:text-white">{{ section.value }}</p>
          </div>
        </div>
      </div>

      <!-- Detail Sections -->
      <div 
        v-for="(detailSection, sectionIndex) in detailSections" 
        :key="sectionIndex"
        class="mt-8 pt-6 border-t border-dark-200 dark:border-dark-700"
      >
        <h3 class="text-lg font-medium text-dark-900 dark:text-white mb-4">{{ detailSection.title }}</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
          <div v-for="(item, itemIndex) in detailSection.items" :key="itemIndex">
            <p class="text-dark-500 dark:text-dark-400">{{ item.label }}</p>
            <p class="font-medium text-dark-900 dark:text-white">{{ item.value }}</p>
          </div>
        </div>
      </div>
    </CardContent>
  </Card>
</template>