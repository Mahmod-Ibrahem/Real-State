import {ref} from "vue";

// One toast at a time, shared by whichever Toast component the current bundle mounts.
export const toast = ref({
    show: false,
    message: "",
    type: "success", // 'success' | 'error'
    delay: 3000,
});

export function showToast(message, type = "success") {
    if (!message) return;
    toast.value = {...toast.value, show: true, message, type};
}

export function hideToast() {
    toast.value = {...toast.value, show: false, message: ""};
}
