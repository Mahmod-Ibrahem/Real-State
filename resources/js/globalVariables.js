import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";

const page = usePage();
export const PRODUCTS_PER_PAGE = 10;

// Toast state lives in useToast.js; re-exported for older imports.
export {toast} from "@/Composables/useToast.js";


console.log(page.props)

export const isOpen = ref(false);

export const currentUrl = computed(() => {
    return window.location.href
})

