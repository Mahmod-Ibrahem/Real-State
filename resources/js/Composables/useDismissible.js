import {onBeforeUnmount, onMounted} from 'vue';

export function useDismissible(root, open) {
    const onDocumentClick = (event) => {
        if (open.value && !root.value?.contains(event.target)) open.value = false;
    };
    const onKeydown = (event) => {
        if (event.key === 'Escape') open.value = false;
    };

    onMounted(() => {
        document.addEventListener('click', onDocumentClick);
        document.addEventListener('keydown', onKeydown);
    });
    onBeforeUnmount(() => {
        document.removeEventListener('click', onDocumentClick);
        document.removeEventListener('keydown', onKeydown);
    });
}
