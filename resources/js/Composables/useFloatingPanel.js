import {onBeforeUnmount, onMounted, ref} from 'vue';

/**
 * Places a dropdown panel (rendered in a Headless UI Portal with `position: fixed`) under its button,
 * or above it when there is no room below. Keeps it in place while the page scrolls or resizes.
 * ADMIN_UI.md §4, "Custom inputs only".
 */
export function useFloatingPanel({minWidth = 128, maxHeight = 270} = {}) {
    const anchorRef = ref(null);
    const panelStyle = ref({});

    function updatePosition() {
        // A Headless UI component ref is the component instance; its DOM node is $el
        const el = anchorRef.value?.$el ?? anchorRef.value;
        if (!el?.getBoundingClientRect) return;

        const rect = el.getBoundingClientRect();
        const spaceBelow = window.innerHeight - rect.bottom;
        const openUp = spaceBelow < maxHeight && rect.top > spaceBelow;
        // As wide as the button (at least minWidth) and never past the viewport; long labels truncate
        const width = Math.min(Math.max(rect.width, minWidth), window.innerWidth - rect.left - 8);

        panelStyle.value = {
            left: `${rect.left}px`,
            width: `${width}px`,
            ...(openUp ? {bottom: `${window.innerHeight - rect.top + 4}px`} : {top: `${rect.bottom + 4}px`}),
        };
    }

    onMounted(() => {
        window.addEventListener('resize', updatePosition);
        window.addEventListener('scroll', updatePosition, true);
    });
    onBeforeUnmount(() => {
        window.removeEventListener('resize', updatePosition);
        window.removeEventListener('scroll', updatePosition, true);
    });

    return {anchorRef, panelStyle, updatePosition};
}
