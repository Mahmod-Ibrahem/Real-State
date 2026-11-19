import {usePage} from '@inertiajs/vue3';
import {showToast} from '@/Composables/useToast.js';

const capitalize = (text) => text.charAt(0).toUpperCase() + text.slice(1);

/** The content language picked in the admin top bar; sent with translatable forms. */
export const currentLocale = () => usePage().props.locale ?? 'en';

/**
 * Create or update a resource and toast the result.
 *
 *   saveResource(form, 'cities', 'city', {id: city?.id})
 *
 * Multipart updates go as POST + _method=put, because PHP doesn't parse files sent with PUT.
 */
export function saveResource(form, routeBase, noun, {id = null, multipart = false} = {}) {
    const options = {
        preserveScroll: true,
        forceFormData: multipart,
        onSuccess: () => showToast(`${capitalize(noun)} ${id ? 'updated' : 'created'}`),
        onError: (errors) => errors.database && showToast(errors.database, 'error'),
    };

    if (!id) {
        return form.post(route(`${routeBase}.store`), options);
    }
    if (multipart) {
        return form.transform((data) => ({...data, _method: 'put'})).post(route(`${routeBase}.update`, id), options);
    }
    return form.put(route(`${routeBase}.update`, id), options);
}
