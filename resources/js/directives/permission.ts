import { Directive } from 'vue';
import usePermission from '@/composables/usePermission';

export const permissionDirective: Directive = {
  mounted(el, binding) {
    const { hasPermission } = usePermission();
    const requiredPermission = binding.value;

    if (!hasPermission(requiredPermission)) {
      el.parentNode?.removeChild(el);
    }
  }
};