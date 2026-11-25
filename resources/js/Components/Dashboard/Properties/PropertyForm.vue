<template>
    <FormCard :submit-label="property ? 'Save changes' : submitLabel" :cancel-href="route(`${routeBase}.index`)"
              :processing="form.processing" @submit="save">
        <FormSection title="Basics" description="What visitors see first.">
            <TextField v-model="form.name" label="Title" placeholder="e.g. Modern 3-Bedroom Villa" required
                       class="sm:col-span-2" :error="form.errors.name"/>
            <RichTextField v-model="form.description" label="Description" required class="sm:col-span-2"
                           placeholder="Describe the property, its surroundings and what makes it stand out"
                           :error="form.errors.description"/>
            <SelectField v-model="form.property_type_id" label="Property type" :options="property_types.data"
                         placeholder="Select a type" required :error="form.errors.property_type_id"/>
            <SelectField v-model="form.property_classification_id" label="Classification"
                         :options="classifications.data" placeholder="Select a classification" required
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
            <ToggleField v-if="publishable" v-model="form.is_published" label="Published"
                         description="Unpublished properties are hidden from the website." class="self-end pb-1"/>
        </FormSection>

        <FormSection v-if="form.purpose === 'sale'" title="Payment"
                     description="Up to 3 installment plans. Amounts are worked out from the price.">
            <PaymentPlansField v-model="form.payment_plans" :price="form.price" :frequencies="installment_frequencies"
                               :errors="form.errors" class="sm:col-span-2"/>
            <TextField v-model="form.maintenance_deposit_percent" label="Maintenance deposit (%)" type="number" min="0"
                       max="30" hint="Paid at handover. Not part of the installments."
                       :error="form.errors.maintenance_deposit_percent"/>
        </FormSection>

        <FormSection title="Details">
            <TextField v-model="form.bedrooms" label="Bedrooms" type="number" min="0" :error="form.errors.bedrooms"/>
            <TextField v-model="form.bathrooms" label="Bathrooms" type="number" min="0" :error="form.errors.bathrooms"/>
            <TextField v-model="form.garages" label="Garages" type="number" min="0" :error="form.errors.garages"/>
            <TextField v-model="form.floor" label="Floor" placeholder="e.g. 3 or Ground" :error="form.errors.floor"/>
            <TextField v-model="form.sqft" label="Area (sq ft)" type="number" min="0" required :error="form.errors.sqft"/>
            <TextField v-model="form.year_built" label="Year built" type="number" min="1900" :max="CURRENT_YEAR" required
                       :error="form.errors.year_built"/>
            <SelectField v-model="form.feature_ids" label="Features" :options="features.data" multiple searchable
                         :max-chips="6" placeholder="Select features" search-placeholder="Search features…" required
                         class="sm:col-span-2" :error="form.errors.feature_ids"/>
        </FormSection>

        <FormSection title="Media">
            <ImageField v-model="form.image" label="Cover image" :current-url="property?.image ?? ''"
                        hint="The main photo on cards and the property page." :required="!property"
                        :error="form.errors.image" class="sm:col-span-2"/>
            <PropertyGallery v-model="form.gallery_images" v-model:removed-ids="form.removed_image_ids" :saved="gallery"
                             :saving="form.processing" :progress="form.progress" :error="galleryError" class="sm:col-span-2"/>
        </FormSection>
    </FormCard>
</template>

<script setup>
import {computed, watch} from 'vue';
import {useForm} from '@inertiajs/vue3';
import FormCard from '@/Components/Admin/ui/FormCard.vue';
import FormSection from '@/Components/Admin/ui/FormSection.vue';
import ImageField from '@/Components/Admin/ui/ImageField.vue';
import RichTextField from '@/Components/Admin/ui/RichTextField.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import ToggleField from '@/Components/Admin/ui/ToggleField.vue';
import PaymentPlansField from './PaymentPlansField.vue';
import PropertyGallery from './PropertyGallery.vue';
import {PROPERTY_FORM_PROPS} from './formProps.js';
import {currentLocale, saveResource} from '@/Composables/useResourceForm.js';

defineOptions({inheritAttrs: false});

const CURRENT_YEAR = new Date().getFullYear();

const props = defineProps({
    ...PROPERTY_FORM_PROPS,
    routeBase: {type: String, required: true},
    publishable: {type: Boolean, default: false},
    submitLabel: {type: String, default: 'Add property'},
    property: {type: Object, default: null},
    gallery: {type: Array, default: () => []},
});

const form = useForm({
    name: props.property?.name ?? '',
    description: props.property?.description ?? '',
    property_type_id: props.property?.property_type_id ?? '',
    property_classification_id: props.property?.property_classification_id ?? '',
    country_id: props.property?.country_id ?? '',
    city_id: props.property?.city_id ?? '',
    area_id: props.property?.area_id ?? '',
    address: props.property?.address ?? '',
    google_map: props.property?.google_map ?? '',
    purpose: props.property?.purpose ?? '',
    status: props.property?.status ?? 'available',
    price: props.property?.price ?? '',
    payment_plans: props.property?.payment_plans ?? [],
    maintenance_deposit_percent: props.property?.maintenance_deposit_percent ?? '',
    ...(props.publishable && {is_published: Boolean(props.property?.is_published ?? false)}),
    bedrooms: props.property?.bedrooms ?? '',
    bathrooms: props.property?.bathrooms ?? '',
    garages: props.property?.garages ?? '',
    floor: props.property?.floor ?? '',
    sqft: props.property?.sqft ?? '',
    year_built: props.property?.year_built ?? '',
    feature_ids: props.property?.feature_ids ?? [],
    image: null,
    gallery_images: [],
    removed_image_ids: [],
    locale: currentLocale(),
});

const countryCities = computed(() => props.cities.data.filter((city) => city.country_id === form.country_id));
const cityAreas = computed(() => props.areas.data.filter((area) => area.city_id === form.city_id));
watch(() => form.country_id, () => {
    if (!countryCities.value.some((city) => city.id === form.city_id)) form.city_id = '';
});
watch(() => form.city_id, () => {
    if (!cityAreas.value.some((area) => area.id === form.area_id)) form.area_id = '';
});

const allowedStatuses = computed(() =>
    props.statuses.filter((status) => !form.purpose || status.purposes.includes(form.purpose))
);
watch(() => form.purpose, (purpose) => {
    if (!allowedStatuses.value.some((status) => status.id === form.status)) form.status = 'available';
    if (purpose !== 'sale') {
        form.payment_plans = [];
        form.maintenance_deposit_percent = '';
    }
});

const galleryError = computed(() => form.errors.gallery_images
    ?? Object.entries(form.errors).find(([key]) => key.startsWith('gallery_images.'))?.[1] ?? '');

const save = () => saveResource(form, props.routeBase, 'property', {id: props.property?.id, multipart: true});
</script>
