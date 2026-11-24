import {router} from '@inertiajs/vue3';
import {showToast} from '@/Composables/useToast.js';

export function approveProperty(property, onFinish = () => {}) {
    router.patch(route('admin.properties.approve', property.id), {}, {
        preserveScroll: true,
        onSuccess: () => showToast(`${property.name} approved`),
        onError: (errors) => errors.database && showToast(errors.database, 'error'),
        onFinish,
    });
}
