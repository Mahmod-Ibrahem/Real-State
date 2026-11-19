<template>
    <div class="mt-4">
        <ul class="nav nav-pills bg-white shadow border-bottom p-3 flex-row d-md-inline-flex nav-justified mb-0 rounded-top-3 position-relative overflow-hidden"
            id="pills-tab" role="tablist">
            <li class="nav-item m-1">
                <a class="nav-link py-2 px-4 active rounded-3 fw-medium" id="buy-login" data-bs-toggle="pill"
                    href="#buy" role="tab" aria-controls="buy" aria-selected="false">
                    Buy
                </a><!--end nav link-->
            </li><!--end nav item-->

            <li class="nav-item m-1">
                <a class="nav-link py-2 px-4 rounded-3 fw-medium" id="sold-login" data-bs-toggle="pill" href="#sold"
                    role="tab" aria-controls="sold" aria-selected="false">
                    Sell
                </a><!--end nav link-->
            </li><!--end nav item-->

            <li class="nav-item m-1">
                <a class="nav-link py-2 px-4 rounded-3 fw-medium" id="rent-login" data-bs-toggle="pill" href="#rent"
                    role="tab" aria-controls="rent" aria-selected="false">
                    Rent
                </a><!--end nav link-->
            </li><!--end nav item-->
        </ul>
        <div class="tab-content bg-white rounded-3 sm-rounded-0 shadow" id="pills-tabContent">
            <div class="card border-0 tab-pane fade show active" id="buy" role="tabpanel" aria-labelledby="buy-login">
                <form class="card-body text-start">
                    <div class="registration-form text-dark text-start">
                        <div class="row g-lg-0">
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label fs-6">Search :</label>
                                    <div class="filter-search-form position-relative filter-border">
                                        <i data-feather="search" class="fea icon-ex-md icons"></i>
                                        <input name="name" type="text" id="job-keyword"
                                            class="form-control filter-input-box bg-light border-0"
                                            placeholder="Search your keaywords">
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label fs-6">Select Categories:</label>
                                    <div class="filter-search-form position-relative filter-border bg-light">
                                        <i data-feather="home" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options" v-model="selected" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label fs-6">Min Price :</label>
                                    <div class="filter-search-form position-relative filter-border bg-light">
                                        <i data-feather="dollar-sign" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options2" v-model="selected2" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3">
                                    <label class="form-label fs-6">Max Price :</label>
                                    <div class="filter-search-form position-relative bg-light">
                                        <i data-feather="dollar-sign" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options3" v-model="selected3" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <input type="submit" id="search" name="search" style="height: 48px;"
                                    class="btn btn-primary searchbtn w-100" value="Search">
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                </form><!--end form-->
            </div><!--end teb pane-->

            <div class="card border-0 tab-pane fade" id="sold" role="tabpanel" aria-labelledby="sold-login">
                <form class="card-body text-start">
                    <div class="registration-form text-dark text-start">
                        <div class="row g-lg-0">
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 text-start">
                                    <label class="form-label fs-6">Search :</label>
                                    <div class="filter-search-form position-relative filter-border">
                                        <i data-feather="search" class="fea icon-ex-md icons"></i>
                                        <input name="name" type="text" id="job-keyword"
                                            class="form-control filter-input-box bg-light border-0"
                                            placeholder="Search your keaywords">
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 text-start">
                                    <label class="form-label fs-6">Select Categories:</label>
                                    <div class="filter-search-form position-relative filter-border bg-light">
                                        <i data-feather="home" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options" v-model="selected" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 text-start">
                                    <label class="form-label fs-6">Min Price :</label>
                                    <div class="filter-search-form position-relative filter-border bg-light">
                                        <i data-feather="dollar-sign" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options2" v-model="selected2" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 text-start">
                                    <label class="form-label fs-6">Max Price :</label>
                                    <div class="filter-search-form position-relative bg-light">
                                        <i data-feather="dollar-sign" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options3" v-model="selected3" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <input type="submit" id="search" name="search" style="height: 48px;"
                                    class="btn btn-primary searchbtn w-100" value="Search">
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                </form><!--end form-->
            </div><!--end teb pane-->

            <div class="card border-0 tab-pane fade" id="rent" role="tabpanel" aria-labelledby="rent-login">
                <form class="card-body text-start">
                    <div class="registration-form text-dark text-start">
                        <div class="row g-lg-0">
                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 text-start">
                                    <label class="form-label fs-6">Search :</label>
                                    <div class="filter-search-form position-relative filter-border">
                                        <i data-feather="search" class="fea icon-ex-md icons"></i>
                                        <input name="name" type="text" id="job-keyword"
                                            class="form-control filter-input-box bg-light border-0"
                                            placeholder="Search your keaywords">
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 text-start">
                                    <label class="form-label fs-6">Select Categories:</label>
                                    <div class="filter-search-form position-relative filter-border bg-light">
                                        <i data-feather="home" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options" v-model="selected" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 text-start">
                                    <label class="form-label fs-6">Min Price :</label>
                                    <div class="filter-search-form position-relative filter-border bg-light">
                                        <i data-feather="dollar-sign" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options2" v-model="selected2" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <div class="mb-3 text-start">
                                    <label class="form-label fs-6">Max Price :</label>
                                    <div class="filter-search-form position-relative bg-light">
                                        <i data-feather="dollar-sign" class="fea icon-ex-md icons"></i>
                                        <i class="uil uil-angle-down vs_select-arrow"></i>
                                        <v-select :options="options3" v-model="selected3" class=""></v-select>
                                    </div>
                                </div>
                            </div><!--end col-->

                            <div class="col-lg-3 col-md-6 col-12">
                                <input type="submit" id="search" name="search" style="height: 48px;"
                                    class="btn btn-primary searchbtn w-100" value="Search">
                            </div><!--end col-->
                        </div><!--end row-->
                    </div>
                </form><!--end form-->
            </div><!--end teb pane-->
        </div><!--end tab content-->
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import feather from 'feather-icons';


const options = ref(['Houses', 'Apartment', 'Offices', 'Townhome',]);
const selected = ref('Houses');
const options2 = ref(['500', '1000', '2000', '3000', '4000', '5000', '6000', 'Min Price']);
const selected2 = ref('Min Price');
const options3 = ref(['500', '1000', '2000', '3000', '4000', '5000', '6000', 'Max Price']);
const selected3 = ref('Max Price');


onMounted(() => {
    feather.replace()
})
</script>

<style lang="scss" scoped></style>