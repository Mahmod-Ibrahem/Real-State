<template>
    <div v-if="categories" class="row justify-content-center">
        <div class="col">
            <div class="section-title mb-4 pb-2">
                <h4 class="title mb-1">Listing Categories</h4>
            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div class="row row-cols-lg-5 row-cols-md-3 row-cols-sm-2 row-cols-1 g-4 mt-0">
        <div v-for="category in props.categories.data" :key="category" class="col">
            <div class="categories position-relative overflow-hidden rounded-3 shadow">
                <Link :href="route('propertyType.show', category.slug)">
                    <img :src="category.image" class="img-fluid cursor-pointer" alt="">
                </Link>

                <div class="p-3">
                    <Link :href="route('propertyType.show', category.slug)" class="text-lg text-dark fs-5 fw-medium">
                        {{ category.name }}
                    </Link>
                    <p class="text-muted small mb-0">{{ category.properties_count }}</p>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import {ref, defineProps} from 'vue';
import {Link} from '@inertiajs/vue3';

const props = defineProps({
    categories: {
        type: Object,
        required: true
    }
});

const datas = ref([
    {
        image: ('../assets/images/property/residential.jpg'),
        name: 'Residential',
        properties_count: '46 Listings'
    },
    {
        image: ('../assets/images/property/land.jpg'),
        name: 'Land',
        properties_count: '124 Listings'
    },
    {
        image: ('../assets/images/property/commercial.jpg'),
        name: 'Commercial',
        properties_count: '265 Listings'
    },
    {
        image: ('../assets/images/property/industrial.jpg'),
        name: 'Industrial',
        properties_count: '452 Listings'
    },
    {
        image: ('../assets/images/property/investment.jpg'),
        name: 'Investment',
        properties_count: '12 Listings'
    },
]);

</script>

<style lang="scss" scoped></style>
