<template>
    <Head>
        <title>{{ metaTitle }}</title>
        <meta name="description" :content="metaDescription">
    </Head>
    <navbar :navLight="'nav-light nav-left'"/>
    <!-- Hero Start -->
    <section class="bg-half-170 d-table w-100"
             :style="{ 'background-image': `url(${listingImage})`, backgroundPosition: 'top' }">
        <div class="bg-overlay bg-gradient-overlay-2"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-12">
                    <div class="title-heading text-center">
                        <p class="text-white-50 para-desc mx-auto mb-0">Qeima</p>
                        <h5 class="heading fw-semibold mb-0 sub-heading text-white title-dark">Property Listing</h5>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <div class="position-relative">
        <div class="shape overflow-hidden text-white">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!-- Hero End -->
    <section class="section">
        <div class="container">
            <div class="row g-4">
                <gridsidebar :cities="props.cities.data" :propertyType="props.propertyType.data"
                             :purposes="props.purposes" :classifications="props.classifications.data"
                             :areas="props.areas.data" :countries="props.countries.data"
                             :filters="props.filters"/>
                <div class="col-lg-8 col-md-6 col-12">
                    <featured :gridproperty="properties.data"/>
                    <pagination :meta="properties.meta"/>
                </div>
            </div>
        </div>
    </section>
    <footers/>
    <backtotop/>

</template>

<script setup>
import navbar from '@/Components/Users/navbar/navbar.vue';
import gridsidebar from '@/Components/Users/grid-sidebar.vue';
import featured from '@/Components/Users/featured-properties-two.vue';
import pagination from '@/Components/Users/pagination.vue';
import footers from '@/Components/Users/footer/footer.vue';
import backtotop from '@/Components/Users/back-to-top.vue'
import listingBG from '@/assets/images/bg/03.jpg';
import {ref} from "vue";
import {Head} from "@inertiajs/vue3";

const listingImage = ref(listingBG);

const props = defineProps({
    properties: Object,
    cities: Object,
    propertyType: Object,
    purposes: Array,
    classifications: Object,
    areas: Object,
    listingCategory: Object,
    countries: Object,
    // Active filters from the query string (e.g. {purpose: 'sale'}), used to pre-fill the sidebar.
    filters: {type: Object, default: () => ({})},
    metaTitle: {type: String, default: 'Explore All Properties in Egypt | Buy, Rent Apartments, Villas & More – Qeima'},
    metaDescription: {type: String, default: 'Browse all real estate listings on Qeima. Discover properties for sale and rent across Egypt.'},
})
</script>

<style lang="scss" scoped></style>
