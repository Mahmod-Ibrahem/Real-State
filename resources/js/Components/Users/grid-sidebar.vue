<template>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card bg-white p-4 rounded-3 shadow sticky-bar">
            <form @submit.prevent="onSubmit">
                <!-- SEARCH -->
                <div>
                    <h6 class="mb-0">Search Properties</h6>
                    <div class="search-bar mt-2">
                        <div id="itemSearch2" class="menu-search mb-0">
                            <input v-model="form.search" type="text" class="form-control rounded-3 border" name="s"
                                   id="searchItem2"
                                   placeholder="Search...">
                        </div>
                    </div>
                </div>
                <!-- SEARCH -->

                <!-- Purpose -->
                <div class="mt-4">
                    <h6 class="mb-0">Sale or Rent</h6>

                    <ul class="list-unstyled mt-2 mb-0">
                        <li v-for="purpose in purposeOptions" :key="purpose.id" class="list-inline-item mb-0 me-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <div class="form-check mb-0">
                                    <input v-model="form.purpose" class="form-check-input" type="radio"
                                           name="purpose" :id="'purpose-' + purpose.id" :value="purpose.id">
                                    <label class="form-check-label" :for="'purpose-' + purpose.id">{{ purpose.name }}</label>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <!-- Countries -->
                <div class="mt-4">
                    <h6 class="mb-0">Countries</h6>
                    <select v-model="form.country" class="form-select form-control border mt-2"
                            aria-label="Default select example">
                        <option value="">Select Country</option>
                        <option v-for="country in countries" :value="country.id">{{ country.name }}</option>
                    </select>
                </div>
                <!-- Classficiation -->
                <div class="mt-4">
                    <h6 class="mb-0">Property Classification</h6>
                    <select v-model="form.classification" class="form-select form-control border mt-2"
                            aria-label="Default select example">
                        <option value="">Select Property Classification</option>
                        <option v-for="classification in classifications" :value="classification.id">
                            {{ classification.name }}
                        </option>
                    </select>
                </div>
                <!-- TYPE -->
                <div class="mt-4">
                    <h6 class="mb-0">Property Type</h6>
                    <select v-model="form.type" class="form-select form-control border mt-2"
                            aria-label="Default select example">
                        <option value="">Select Category</option>
                        <option v-for="type in propertyType" :value="type.id">{{ type.name }}</option>
                    </select>
                </div>
                <!-- Categories -->

                <!-- Location -->
                <div class="mt-4">
                    <h6 class="mb-0">City</h6>
                    <select v-model="form.city" class="form-select form-control border mt-2"
                            aria-label="Default select example">
                        <option value="">Select City</option>
                        <option v-for="city in visibleCities" :key="city.id" :value="city.id">{{ city.name }}</option>
                    </select>
                </div>
                <!-- Area -->
                <div class="mt-4">
                    <h6 class="mb-0">Area</h6>
                    <select v-model="form.area" class="form-select form-control border mt-2"
                            aria-label="Default select example">
                        <option value="">Select Area</option>
                        <option v-for="area in visibleAreas" :key="area.id" :value="area.id">{{ area.name }}</option>
                    </select>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary w-100">Apply Filter</button>
                </div>
            </form>
        </div>
    </div><!--end col-->
</template>


<script setup>
import {useForm} from "@inertiajs/vue3";
import {computed, watch} from "vue";

const props = defineProps({
    cities: Object,
    propertyType: Object,
    purposes: Array,
    classifications: Object,
    areas: Object,
    countries: Object,
    // Currently applied filters (from the URL), so the form shows what the results are filtered by.
    filters: {type: Object, default: () => ({})},
})

const form = useForm({
    search: props.filters.search ?? '',
    purpose: props.filters.purpose ?? '',
    type: props.filters.type ?? '',
    city: props.filters.city ?? '',
    area: props.filters.area ?? '',
    country: props.filters.country ?? '',
    classification: props.filters.classification ?? '',
})

const purposeOptions = computed(() => [{id: '', name: 'All'}, ...props.purposes]);

// Filters are optional: each list narrows only when its parent is chosen.
const visibleCities = computed(() =>
    form.country ? props.cities.filter(city => city.countryId === form.country) : props.cities
);
const visibleAreas = computed(() => {
    if (form.city) return props.areas.filter(area => area.cityId === form.city);
    const cityIds = visibleCities.value.map(city => city.id);
    return form.country ? props.areas.filter(area => cityIds.includes(area.cityId)) : props.areas;
});
watch(() => form.country, () => {
    if (form.city && !visibleCities.value.some(city => city.id === form.city)) form.city = '';
});
watch(visibleAreas, () => {
    if (form.area && !visibleAreas.value.some(area => area.id === form.area)) form.area = '';
});

function onSubmit() {
    // Keep the URL clean and shareable: /listing?purpose=sale&city=3 instead of every empty field.
    form
        .transform(data => Object.fromEntries(Object.entries(data).filter(([, value]) => value !== '' && value !== null)))
        .get(route('listing.index'), {preserveState: true, preserveScroll: true});
}

</script>

<style lang="scss" scoped></style>
