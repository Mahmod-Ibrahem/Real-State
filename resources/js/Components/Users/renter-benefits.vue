<template>
    <div class="row justify-content-center">
        <div class="col">
            <div class="section-title text-center mb-4 pb-2">
                <h4 class="title mb-3">Renter Benefits</h4>

            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div class="row g-4 mt-0">
        <div v-for="item in datas" :key="item" class="col-md-4">
            <div class="position-relative features text-center mx-lg-4 px-md-1">
                <div class="feature-icon position-relative overflow-hidden d-flex justify-content-center">
                    <i data-feather="hexagon" class="hexagon"></i>
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <i :data-feather="item.icon" class="fea icon-m-md text-primary"></i>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="" class="fw-medium title text-dark fs-5">{{ item.name }}</a>
                    <p class="text-muted mt-3 mb-0">{{ item.title }}</p>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import { ref } from 'vue';

const datas = ref([
    {
        icon: 'dollar-sign',
        name: 'Affordable Living',
        title: "Enjoy quality housing without the high upfront costs of buying a home."
    },
    {
        icon: 'calendar',
        name: 'Flexible Lease Terms',
        title: "Choose rental agreements that suit your lifestyle, whether short-term or long-term."
    },
    {
        icon: 'map-pin',
        name: 'Prime Locations',
        title: "Rent in desirable areas that may be difficult to afford if buying."
    },
])

</script>

<style lang="scss" scoped></style>
