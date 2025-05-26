import { computed } from 'vue';
import { usePage } from '@inertiajs/vue3';

interface AuthUser {
  id: number;
  name: string;
  email: string;
  roles: string[];
  permissions: string[];
}

export default function usePermission() {
    const user = computed(() => (usePage().props as any).auth.user as AuthUser || null);
 
    const hasRole = (role: string) => {
        return user.value?.roles.includes(role) || false;
    };

    const hasPermission = (permission: string) => {
        return user.value?.permissions.includes(permission) || false;
    }

    const hasAnyPermission = (permissions: string[]) => {
        return permissions.some(p => user.value?.permissions.includes(p));
    }

    return {
        hasRole,
        hasPermission,
        hasAnyPermission
    }
}