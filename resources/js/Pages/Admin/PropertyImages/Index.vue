<template>
    <AuthenticatedLayout title="Property gallery" description="Photos shown on the property page, after the cover image.">
        <template #actions>
            <Button variant="secondary" :href="route('properties.edit', id)">Edit property</Button>
        </template>

        <div class="grid gap-4">
            <Panel :title="`Current photos (${propertyImages.length})`">
                <ul v-if="propertyImages.length" class="grid grid-cols-2 gap-3 sm:grid-cols-4 lg:grid-cols-6">
                    <li v-for="image in propertyImages" :key="image.id" class="group relative">
                        <img :src="image.image" alt="" class="aspect-[4/3] w-full rounded-lg border border-slate-200 object-cover"/>
                        <button type="button" aria-label="Delete photo" @click="confirmDelete('propertyImages.destroy', image.id, 'photo')"
                                class="absolute right-1.5 top-1.5 grid h-7 w-7 place-content-center rounded-md border border-slate-200 bg-white text-slate-500 shadow-sm transition-colors hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500/30">
                            <TrashIcon class="h-4 w-4" aria-hidden="true"/>
                        </button>
                    </li>
                </ul>
                <p v-else class="py-6 text-center text-sm text-slate-500">No photos yet. Add some below.</p>
            </Panel>

            <FormCard submit-label="Upload photos" :processing="form.processing" :disabled="!form.images.length"
                      @submit="upload">
                <ImagesField v-model="form.images" label="Add photos" :error="form.errors.images || form.errors['images.0']"/>
            </FormCard>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import {useForm} from '@inertiajs/vue3';
import {TrashIcon} from '@heroicons/vue/24/outline';
import AuthenticatedLayout from '@/Layouts/Admin/AuthenticatedLayout.vue';
import Button from '@/Components/Admin/ui/Button.vue';
import Panel from '@/Components/Admin/ui/Panel.vue';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import ImagesField from '@/Components/Admin/ui/ImagesField.vue';
import {confirmDelete} from '@/Composables/useConfirmDelete.js';
import {showToast} from '@/Composables/useToast.js';

const props = defineProps({
    // [{id, image}]
    propertyImages: {type: Array, required: true},
    // The property id (from ?propertyId=)
    id: {type: [String, Number], required: true},
});

const form = useForm({
    property_id: props.id,
    images: [],
});

function upload() {
    form.post(route('propertyImages.store'), {
        preserveScroll: true,
        onSuccess: () => {
            showToast('Photos uploaded');
            form.reset('images');
        },
    });
}
</script>
