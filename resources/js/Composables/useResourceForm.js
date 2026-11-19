import {watch} from 'vue';
import {usePage} from '@inertiajs/vue3';
import {showToast} from '@/Composables/useToast.js';

const capitalize = (text) => text.charAt(0).toUpperCase() + text.slice(1);

/** The content language picked in the admin top bar; sent with translatable forms. */
export const currentLocale = () => usePage().props.locale ?? 'en';

/**
 * Create or update a resource and toast the result.
 *
 *   saveResource(form, 'cities', 'city', {id: city?.id, onSuccess: close})
 *
 * Multipart updates go as POST + _method=put, because PHP doesn't parse files sent with PUT.
 */
export function saveResource(form, routeBase, noun, {id = null, multipart = false, onSuccess = () => {}} = {}) {
    const options = {
        preserveScroll: true,
        forceFormData: multipart,
        onSuccess: () => {
            showToast(`${capitalize(noun)} ${id ? 'updated' : 'created'}`);
            onSuccess();
        },
        onError: (errors) => errors.database && showToast(errors.database, 'error'),
    };

    // Always set the transform: a modal reuses one form for edit and create, so a leftover
    // `_method: put` from an earlier edit would turn the next create into a PUT.
    form.transform((data) => (id && multipart ? {...data, _method: 'put'} : data));

    if (!id) {
        return form.post(route(`${routeBase}.store`), options);
    }
    if (multipart) {
        return form.post(route(`${routeBase}.update`, id), options);
    }
    return form.put(route(`${routeBase}.update`, id), options);
}

/**
 * Refill a modal form every time the modal opens: the record being edited, or blanks when creating.
 *
 *   resetFormOnOpen(form, () => props.open, () => ({name: props.city?.name ?? '', locale: currentLocale()}))
 */
export function resetFormOnOpen(form, isOpen, fields) {
    watch(isOpen, (open) => {
        if (!open) return;
        form.defaults(fields());
        form.reset();
        form.clearErrors();
    });
}
