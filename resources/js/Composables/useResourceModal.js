import {ref} from 'vue';

/**
 * Create/edit modal state for a list page (ADMIN_UI.md §5).
 *
 *   const modal = useResourceModal();
 *   <Button @click="modal.openCreate()">Add city</Button>
 *   <RowActions editable @edit="modal.openEdit(city)"/>
 *   <CityForm :open="modal.isOpen.value" :city="modal.record.value" @close="modal.close()"/>
 */
export function useResourceModal() {
    const isOpen = ref(false);
    // The row being edited; null while creating
    const record = ref(null);

    return {
        isOpen,
        record,
        openCreate() {
            record.value = null;
            isOpen.value = true;
        },
        openEdit(row) {
            record.value = row;
            isOpen.value = true;
        },
        close() {
            isOpen.value = false;
        },
    };
}
