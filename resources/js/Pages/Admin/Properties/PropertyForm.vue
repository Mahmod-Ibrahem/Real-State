<template>
    <FormCard :submit-label="property ? 'Save changes' : 'Add property'" :cancel-href="route('admin.properties.index')"
              :processing="form.processing" @submit="save">
        <FormSection title="Basics" description="What visitors see first.">
            <TextField v-model="form.name" label="Title" placeholder="e.g. Modern 3-Bedroom Villa" required
                       class="sm:col-span-2" :error="form.errors.name"/>
            <FieldFrame input-id="property-description" label="Description" required :error="form.errors.description"
                        class="sm:col-span-2">
                <Editor v-model="form.description" editor-style="height: 160px" :pt="{root: {id: 'property-description'}}"/>
            </FieldFrame>
            <SelectField v-model="form.property_type_id" label="Property type" :options="propertyTypes.data"
                         placeholder="Select a type" required :error="form.errors.property_type_id"/>
            <SelectField v-model="form.property_classification_id" label="Classification"
                         :options="propertyClassification.data" placeholder="Select a classification" required
                         :error="form.errors.property_classification_id"/>
        </FormSection>

        <FormSection title="Location" description="Pick the country first; cities and areas follow.">
            <SelectField v-model="form.country_id" label="Country" :options="countries.data" placeholder="Select a country"
                         required :error="form.errors.country_id"/>
            <SelectField v-model="form.city_id" label="City" :options="countryCities" :disabled="!form.country_id"
                         :placeholder="form.country_id ? 'Select a city' : 'Select a country first'" required
                         :error="form.errors.city_id"/>
            <SelectField v-model="form.area_id" label="Area" :options="cityAreas" :disabled="!form.city_id"
                         :placeholder="form.city_id ? 'Select an area' : 'Select a city first'" required
                         :error="form.errors.area_id"/>
            <TextField v-model="form.address" label="Address" placeholder="Street and building" required
                       :error="form.errors.address"/>
            <TextField v-model="form.google_map" label="Google Maps embed" type="textarea" :rows="3"
                       placeholder='<iframe src="https://www.google.com/maps/embed?…"></iframe>'
                       hint="Google Maps › Share › Embed a map › copy HTML." class="sm:col-span-2"
                       :error="form.errors.google_map"/>
        </FormSection>

        <FormSection title="Listing">
            <SelectField v-model="form.purpose" label="Purpose" :options="purposes" placeholder="Sale or rent" required
                         :error="form.errors.purpose"/>
            <SelectField v-model="form.status" label="Status" :options="allowedStatuses" placeholder="Select a status"
                         required hint="Sold applies to sale listings, Rented to rent listings."
                         :error="form.errors.status"/>
            <TextField v-model="form.price" label="Price (EGP)" type="number" min="0" step="1000" required
                       :error="form.errors.price"/>
            <ToggleField v-model="form.is_published" label="Published"
                         description="Unpublished properties are hidden from the website." class="self-end pb-1"/>
        </FormSection>

        <FormSection title="Details">
            <TextField v-model="form.bedrooms" label="Bedrooms" type="number" min="0" :error="form.errors.bedrooms"/>
            <TextField v-model="form.bathrooms" label="Bathrooms" type="number" min="0" :error="form.errors.bathrooms"/>
            <TextField v-model="form.garages" label="Garages" type="number" min="0" :error="form.errors.garages"/>
            <TextField v-model="form.floor" label="Floor" placeholder="e.g. 3 or Ground" :error="form.errors.floor"/>
            <TextField v-model="form.sqft" label="Area (sq ft)" type="number" min="0" required :error="form.errors.sqft"/>
            <TextField v-model="form.year_built" label="Year built" type="number" min="1900" :max="CURRENT_YEAR" required
                       :error="form.errors.year_built"/>
            <FieldFrame input-id="property-features" label="Features" required :error="form.errors.feature_ids"
                        class="sm:col-span-2">
                <MultiSelect v-model="form.feature_ids" input-id="property-features" :options="features.data"
                             option-label="name" option-value="id" filter display="chip"
                             placeholder="Select features" :max-selected-labels="6" class="w-full"/>
            </FieldFrame>
        </FormSection>

        <FormSection title="Media">
            <ImageField v-model="form.image" label="Cover image" :current-url="property?.image ?? ''"
                        hint="The main photo on cards and the property page." :required="!property"
                        :error="form.errors.image" class="sm:col-span-2"/>
            <PropertyGallery v-model="form.propertyImages" v-model:removed-ids="form.removed_image_ids" :saved="gallery"
                             :saving="form.processing" :progress="form.progress" :required="!property"
                             :error="galleryError" class="sm:col-span-2"/>
        </FormSection>
    </FormCard>
</template>

<script setup>
import {computed, watch} from 'vue';
import {useForm} from '@inertiajs/vue3';
import MultiSelect from 'primevue/multiselect';
import Editor from 'primevue/editor';
import FieldFrame from '@/Components/Admin/ui/FieldFrame.vue';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import FormSection from '@/Components/Admin/ui/FormSection.vue';
import ImageField from '@/Components/Admin/ui/ImageField.vue';
import PropertyGallery from './PropertyGallery.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import ToggleField from '@/Components/Admin/ui/ToggleField.vue';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';

const CURRENT_YEAR = new Date().getFullYear();

const props = defineProps({
    // null when creating
    property: {type: Object, default: null},
    propertyTypes: {type: Object, required: true},
    propertyClassification: {type: Object, required: true},
    countries: {type: Object, required: true},
    cities: {type: Object, required: true},
    areas: {type: Object, required: true},
    purposes: {type: Array, required: true},
    statuses: {type: Array, required: true},
    features: {type: Object, required: true},
    // Saved gallery photos when editing: [{id, url}]
    gallery: {type: Array, default: () => []},
});

const form = useForm({
    name: props.property?.name ?? '',
    description: props.property?.description ?? '',
    property_type_id: props.property?.propertyTypeId ?? '',
    property_classification_id: props.property?.classificationId ?? '',
    country_id: props.property?.countryId ?? '',
    city_id: props.property?.cityId ?? '',
    area_id: props.property?.areaId ?? '',
    address: props.property?.address ?? '',
    google_map: props.property?.google_map ?? '',
    purpose: props.property?.purpose ?? '',
    status: props.property?.status ?? 'available',
    price: props.property?.price ?? '',
    is_published: Boolean(props.property?.is_published ?? false),
    bedrooms: props.property?.bedrooms ?? '',
    bathrooms: props.property?.bathrooms ?? '',
    garages: props.property?.garages ?? '',
    floor: props.property?.floor ?? '',
    sqft: props.property?.sqft ?? '',
    year_built: props.property?.year_built ?? '',
    feature_ids: props.property?.featureIds ?? [],
    image: null,
    // Gallery changes, saved with the property
    propertyImages: [],
    removed_image_ids: [],
    locale: currentLocale(),
});

// Country → City → Area: each list only offers children of the chosen parent.
const countryCities = computed(() => props.cities.data.filter((city) => city.countryId === form.country_id));
const cityAreas = computed(() => props.areas.data.filter((area) => area.cityId === form.city_id));
watch(() => form.country_id, () => {
    if (!countryCities.value.some((city) => city.id === form.city_id)) form.city_id = '';
});
watch(() => form.city_id, () => {
    if (!cityAreas.value.some((area) => area.id === form.area_id)) form.area_id = '';
});

// Sold only applies to sale listings, Rented only to rent listings.
const allowedStatuses = computed(() =>
    props.statuses.filter((status) => !form.purpose || status.purposes.includes(form.purpose))
);
watch(() => form.purpose, () => {
    if (!allowedStatuses.value.some((status) => status.id === form.status)) form.status = 'available';
});

// First gallery error: the array itself ("required") or one of its photos ("propertyImages.2")
const galleryError = computed(() => form.errors.propertyImages
    ?? Object.entries(form.errors).find(([key]) => key.startsWith('propertyImages.'))?.[1] ?? '');

const save = () => saveResource(form, 'admin.properties', 'property', {id: props.property?.id, multipart: true});
</script>
