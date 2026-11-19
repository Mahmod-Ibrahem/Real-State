<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 mt-sm-0 pt-sm-0">
                <div class="features-absolute">
                    <ul class="nav nav-pills bg-white shadow border-bottom p-3 flex-row d-md-inline-flex nav-justified mb-0 rounded-top-3 position-relative overflow-hidden"
                        id="pills-tab" role="tablist">
                        <li v-for="purpose in purposes" :key="purpose.id" class="nav-item m-1">

                            <a class="nav-link py-2 px-4 rounded-3 fw-medium" :id="purpose.id + '-tab'"
                               @click="form.purpose = purpose.id"
                               :class="{ 'active': purpose.id === form.purpose }"
                               data-bs-toggle="pill" :href="'#' + purpose.id" role="tab"
                               :aria-controls="purpose.id" :aria-selected="purpose.id === form.purpose">
                                {{ purpose.name }}
                            </a><!--end nav link-->
                        </li><!--end nav item-->
                    </ul>

                    <div class="tab-content bg-white rounded-bottom-3 rounded-end-3 sm-rounded-0 shadow"
                         id="pills-tabContent">
                        <div v-for="purpose in purposes" :key="purpose.id" class="card border-0 tab-pane fade"
                             :class="{'active show': purpose.id === form.purpose }"
                             :id="purpose.id" role="tabpanel"
                             :aria-labelledby="purpose.id + '-tab'">
                            <form @submit.prevent="submit" class="card-body text-start">
                                <div class="registration-form text-dark text-start">
                                    <div class="row g-lg-0">
                                        <div class="col-lg-3 col-md-6 col-12">
                                            <div class="mb-3">
                                                <label class="form-label fs-6">Search :</label>
                                                <div
                                                    class="flex justify-between items-center w-full bg-light border-r px-3">
                                                    <i data-feather="search"
                                                       class="fea icon-ex-md icons text-sky-400"></i>

                                                    <input v-model="form.search"
                                                           name="name" type="text" id="job-keyword"
                                                           class="form-control filter-input-box border-0 bg-light"
                                                           placeholder="Search your keywords">
                                                </div>
                                            </div>
                                        </div><!--end col-->

                                        <div class="col-lg-3 col-md-6 col-12">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fs-6">Select Categories:</label>
                                                <div
                                                    class="filter-search-form position-relative bg-light border-r">
                                                    <i data-feather="home" class="fea icon-ex-md icons"></i>
                                                    <i class="uil uil-angle-down vs_select-arrow"></i>
                                                    <v-select :options="props.propertyTypes" v-model="form.type"
                                                              :reduce="option => option.id" label="name"
                                                              placeholder="Select Categories"
                                                    ></v-select>

                                                </div>
                                            </div>
                                        </div><!--end col-->

                                        <div class="col-lg-3 col-md-6 col-12">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fs-6">Select City :</label>
                                                <div
                                                    class="flex justify-start items-center w-full bg-gray-50">
                                                    <v-select :options="cities" label="name" v-model="form.cityId"
                                                              placeholder="Select a city"
                                                              :reduce="city => city.id"
                                                              class=""></v-select>
                                                </div>
                                            </div>
                                        </div><!--end col-->

                                        <div class="col-lg-3 col-md-6 col-12">
                                            <div class="mb-3 text-start">
                                                <label class="form-label fs-6">Select Area</label>
                                                <div class="flex justify-start items-center px-6 bg-gray-50">
                                                    <v-select :options="cityAreas" v-model="form.areaId" label="name"
                                                              placeholder="Select an area"
                                                              :reduce="area => area.id"
                                                              class=""></v-select>
                                                </div>
                                            </div>
                                        </div><!--end col-->

                                        <div class="col-lg-3 col-md-6 col-12">
                                            <button type="submit" id="search" name="search" style="height: 48px;"
                                                    class="btn btn-primary searchbtn w-100">
                                                Search
                                            </button>
                                        </div><!--end col-->
                                    </div><!--end row-->
                                </div>
                            </form><!--end form-->
                        </div><!--end teb pane-->

                    </div><!--end tab content-->
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</template>

<script setup>
import {computed, watch} from 'vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import feather from 'feather-icons';
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    purposes: Array,
    propertyTypes: Object,
    cities: Object,
    areas: Object
})
const form = useForm({
    search: '',
    type: '',
    purpose: props.purposes[0]?.id,
    cityId: props.cities[0]?.id,
    areaId: props.areas.find(area => area.cityId === props.cities[0]?.id)?.id
});

// Only offer areas that belong to the chosen city; reset the area when the city changes.
const cityAreas = computed(() => props.areas.filter(area => area.cityId === form.cityId));
watch(() => form.cityId, () => {
    if (!cityAreas.value.some(area => area.id === form.areaId)) form.areaId = cityAreas.value[0]?.id;
});


function submit() {
    form.get(route('sell-buy-rent'),
        {
            preserveScroll: true,
            onSuccess: () => {
                console.log('success')
            },
        })
}

feather.replace()

</script>

<style lang="scss" scoped></style>
