<template>
    <div class="flex items-center gap-1.5">
        <Button v-if="property.review_status !== 'approved'" size="sm" :icon="CheckIcon" :loading="approving"
            @click="approve">Approve</Button>
        <Button v-if="property.review_status !== 'rejected'" size="sm" variant="secondary" :icon="XMarkIcon"
            @click="rejecting = true">Reject</Button>

        <PropertyRejectModal :open="rejecting" :property="property" @close="rejecting = false"/>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {CheckIcon, XMarkIcon} from '@heroicons/vue/20/solid';
import Button from '@/Components/Admin/ui/Button.vue';
import PropertyRejectModal from '@/Components/Admin/PropertyRejectModal.vue';
import {approveProperty} from '@/Composables/usePropertyReview.js';

const props = defineProps({
    property: {type: Object, required: true},
});

const approving = ref(false);
const rejecting = ref(false);

function approve() {
    approving.value = true;
    approveProperty(props.property, () => (approving.value = false));
}
</script>
