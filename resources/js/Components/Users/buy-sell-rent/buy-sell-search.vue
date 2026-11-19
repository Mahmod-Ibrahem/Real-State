<template>
    <div v-if="search" class="col-lg-10">
        <div class="features-absolute">
            <div class="card border-0 bg-white shadow mt-5">
                <form class="card-body text-start">
                    <div class="registration-form text-dark text-start">
                        <div class="row g-lg-0">
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 mb-sm-0">
                                    <label class="form-label d-none fs-6">Search :</label>
                                    <div class="filter-search-form position-relative filter-border">
                                        <i data-feather="search" class="fea icon-ex-md icons"></i>
                                        <input name="name" type="text" id="job-keyword"
                                            class="form-control filter-input-box bg-light border-0"
                                            placeholder="Search your keaywords">
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 mb-sm-0">
                                    <label class="form-label d-none fs-6">Select Categories:</label>
                                    <div class="filter-search-form position-relative filter-border bg-light">
                                        <i data-feather="home" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options" v-model="selected" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 mb-sm-0">
                                    <label class="form-label d-none fs-6">Price :</label>
                                    <div class="filter-search-form position-relative bg-light">
                                        <i data-feather="dollar-sign" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options2" v-model="selected2" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <input type="submit" id="search" name="search" style="height: 60px;"
                                    class="btn btn-primary searchbtn w-100" value="Search">
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                </form><!--end form-->
            </div>
        </div>
    </div>

    <div v-else class="col-lg-10">
        <div class="card border-0 bg-white shadow z-1">
            <form class="card-body text-start">
                <div class="registration-form text-dark text-start">
                    <div class="row g-lg-0">
                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="mb-3 mb-sm-0">
                                <label class="form-label d-none fs-6">Search :</label>
                                <div class="filter-search-form position-relative filter-border">
                                    <i data-feather="search" class="fea icon-ex-md icons"></i>
                                    <input name="name" type="text" id="job-keyword"
                                        class="form-control filter-input-box bg-light border-0"
                                        placeholder="Search your keaywords">
                                </div>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="mb-3 mb-sm-0">
                                <label class="form-label d-none fs-6">Select Categories:</label>
                                <div class="filter-search-form position-relative filter-border bg-light">
                                    <i data-feather="home" class="fea icon-ex-md icons"></i>
                                    <i class="uil uil-angle-down vs_select-arrow"></i>
                                    <v-select :options="options" v-model="selected" class=""></v-select>
                                </div>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-3 col-md-6 col-12">
                            <div class="mb-3 mb-sm-0">
                                <label class="form-label d-none fs-6">Price :</label>
                                <div class="filter-search-form position-relative bg-light">
                                    <i data-feather="dollar-sign" class="fea icon-ex-md icons"></i>
                                    <i class="uil uil-angle-down vs_select-arrow"></i>
                                    <v-select :options="options2" v-model="selected2" class=""></v-select>
                                </div>
                            </div>
                        </div><!--end col-->

                        <div class="col-lg-3 col-md-6 col-12">
                            <input type="submit" id="search" name="search" style="height: 60px;"
                                class="btn btn-primary searchbtn w-100" value="Search">
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
            </form><!--end form-->
        </div>
    </div>
</template>

<script setup>
import { ref, defineProps } from 'vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';

defineProps({
    search: {
        type: Boolean,
        required: true
    }
})

const options = ref(['Houses', 'Apartment', 'Offices', 'Townhome',]);
const selected = ref('Houses');
const options2 = ref(['500', '1000', '2000', '3000', '4000', '5000', '6000', 'Price']);
const selected2 = ref('Price');
</script>

<style lang="scss" scoped></style>