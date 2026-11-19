import {router} from '@inertiajs/vue3';
import {showToast} from '@/Composables/useToast.js';

const capitalize = (text) => text.charAt(0).toUpperCase() + text.slice(1);

/**
 * Confirm, delete via the resource's destroy route, and toast the outcome.
 * Server-side refusals (e.g. "This city still has areas") arrive as errors.database.
 *
 *   confirmDelete('cities.destroy', city.id, 'city')
 */
export function confirmDelete(routeName, id, noun) {
    if (!window.confirm(`Delete this ${noun}? This can't be undone.`)) return;

    router.delete(route(routeName, id), {
        preserveScroll: true,
        onSuccess: () => showToast(`${capitalize(noun)} deleted`),
        onError: (errors) => showToast(errors.database ?? `The ${noun} could not be deleted.`, 'error'),
    });
}
