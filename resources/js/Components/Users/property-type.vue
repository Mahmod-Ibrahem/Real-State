<template>
    <div v-if="property" class="row justify-content-center">
        <div class="col">
            <div class="section-title text-center mb-4 pb-2">
                <h4 class="title mb-3">Property Types</h4>

            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div v-else class="row justify-content-center">
        <div class="col">
            <div class="section-title mb-4 pb-2">
                <h4 class="title mb-3">Property Types</h4>
            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div class="row row-cols-lg-5 row-cols-md-3 row-cols-sm-2 row-cols-1 g-4 mt-0">
        <div v-for="item in datas" :key="item" class="col">
            <div class="categories position-relative overflow-hidden rounded-3 p-4 text-center">
                <img :src="item.image" class="avatar avatar-small" alt="">

                <div class="mt-4">
                    <a href="" class="title text-dark fs-5 fw-medium">{{ item.name }}</a>
                    <p class="text-muted small mb-0">{{ item.listing }}</p>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import { ref, defineProps } from 'vue';

defineProps({
    property: {
        type: Boolean,
        required: true
    }
});

const datas = ref([
    {
        image: require('../assets/images/icons/house.png'),
        name: 'Houses',
        listing: '46 Listings'
    },
    {
        image: require('../assets/images/icons/bungalow.png'),
        name: 'Villas',
        listing: '124 Listings'
    },
    {
        image: require('../assets/images/icons/buildings.png'),
        name: 'Apartments',
        listing: '265 Listings'
    },
    {
        image: require('../assets/images/icons/commercial.png'),
        name: 'Commercial',
        listing: '452 Listings'
    },
    {
        image: require('../assets/images/icons/industries.png'),
        name: 'Industries',
        listing: '12 Listings'
    },
]);
</script>

<style lang="scss" scoped></style>
