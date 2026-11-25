import {reactive} from 'vue';
import {router} from '@inertiajs/vue3';
import {showToast} from '@/Composables/useToast.js';

/** Saves a lead's status through `routeName` (admin or supplier) and toasts the result. */
export function useLeadStatus(routeName) {
    const saving = reactive({});

    function changeStatus(lead, status) {
        if (lead.status === status) return;

        saving[lead.id] = true;
        router.patch(route(routeName, lead.id), {status}, {
            preserveScroll: true,
            preserveState: true,
            onSuccess: () => showToast('Lead status updated'),
            onError: (errors) => {
                showToast(errors.database ?? errors.status ?? "The status couldn't be changed", 'error');
                router.reload({preserveScroll: true});
            },
            onFinish: () => delete saving[lead.id],
        });
    }

    return {saving, changeStatus};
}
