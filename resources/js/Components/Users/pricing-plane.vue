<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 mt-sm-0 pt-sm-0">
                <div class="features-absolute">
                    <div class="row g-4">
                        <div class="col-lg-4 col-md-6">
                            <div class="card rounded-3 shadow">
                                <div class="p-4 border-bottom text-center">
                                    <div class="position-relative features text-center mx-lg-4 px-md-1">
                                        <div
                                            class="feature-icon position-relative overflow-hidden d-flex justify-content-center">
                                            <i data-feather="hexagon" class="hexagon"></i>
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <i class="mdi mdi-tree-outline fs-2 text-primary"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="text-primary">Basic</h5>

                                    <div class="d-flex justify-content-center">
                                        <span class="fs-5">$</span>
                                        <span class="h2 fw-semibold mb-0">19</span>
                                        <span class="fs-6 align-self-end">/Month</span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <h6>Pricing Features:</h6>
                                    <ul class="list-unstyled">
                                        <li v-for="item in datas" :key="item" class="text-muted mb-0"><span
                                                class="icon fs-5 me-2"><i
                                                    class="mdi mdi-check-circle align-middle text-success"></i></span>{{ item }}
                                        </li>
                                    </ul>

                                    <a href="" class="btn btn-outline-primary w-100">Started Now</a>
                                </div>
                            </div>
                        </div><!--end col-->


                        <div class="col-lg-4 col-md-6">
                            <div class="card rounded-3 shadow">
                                <div class="p-4 border-bottom text-center">
                                    <div class="position-relative features text-center mx-lg-4 px-md-1">
                                        <div
                                            class="feature-icon position-relative overflow-hidden d-flex justify-content-center">
                                            <i data-feather="hexagon" class="hexagon"></i>
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <i class="mdi mdi-shield-star-outline fs-2 text-primary"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="text-primary">Premium</h5>

                                    <div class="d-flex justify-content-center">
                                        <span class="fs-5">$</span>
                                        <span class="h2 fw-semibold mb-0">39</span>
                                        <span class="fs-6 align-self-end">/Month</span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <h6>Pricing Features:</h6>
                                    <ul class="list-unstyled">
                                        <li v-for="item in datas" :key="item" class="text-muted mb-0"><span
                                                class="icon fs-5 me-2"><i
                                                    class="mdi mdi-check-circle align-middle text-success"></i></span>{{ item }}
                                        </li>
                                    </ul>

                                    <a href="" class="btn btn-soft-primary w-100">Started Now</a>
                                </div>
                            </div>
                        </div><!--end col-->


                        <div class="col-lg-4 col-md-6">
                            <div class="card rounded-3 shadow">
                                <div class="p-4 border-bottom text-center">
                                    <div class="position-relative features text-center mx-lg-4 px-md-1">
                                        <div
                                            class="feature-icon position-relative overflow-hidden d-flex justify-content-center">
                                            <i data-feather="hexagon" class="hexagon"></i>
                                            <div class="position-absolute top-50 start-50 translate-middle">
                                                <i class="mdi mdi-rocket-launch-outline fs-2 text-primary"></i>
                                            </div>
                                        </div>
                                    </div>

                                    <h5 class="text-primary">Business</h5>

                                    <div class="d-flex justify-content-center">
                                        <span class="fs-5">$</span>
                                        <span class="h2 fw-semibold mb-0">59</span>
                                        <span class="fs-6 align-self-end">/Month</span>
                                    </div>
                                </div>

                                <div class="p-4">
                                    <h6>Pricing Features:</h6>
                                    <ul class="list-unstyled">
                                        <li v-for="item in datas" :key="item" class="text-muted mb-0"><span
                                                class="icon fs-5 me-2"><i
                                                    class="mdi mdi-check-circle align-middle text-success"></i></span>{{ item }}
                                        </li>
                                    </ul>

                                    <a href="" class="btn btn-primary w-100">Started Now</a>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->

</template>

<script setup>
import { ref } from 'vue';

const datas = ref(['Full Access', 'Enhanced Security', 'Source Files', '1 Domain Free']);

</script>

<style lang="scss" scoped></style>