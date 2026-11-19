<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <navbar :navLight="'nav-left'"/>
        <section class="bg-half-170 d-table w-100"
                 :style="{ 'background-image': `url(${sellImage})`, backgroundPosition: 'center' }">
            <div class="bg-overlay bg-gradient-overlay-2"></div>
            <div class="container">
                <div class="row mt-5 justify-content-center">
                    <div class="col-12">
                        <div class="title-heading text-center">
                            <p class="text-white-50 para-desc mx-auto mb-0">Favourite Listings</p>
                            <h5 class="heading fw-semibold mb-0 sub-heading text-white title-dark">Check Your Favourite
                                Listings
                            </h5>
                        </div>
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </section><!--end section-->
        <propertiesComponent :properties="properties.data" :header="'Favourite Properties'"/>

    </div>
</template>

<script setup>
import Navbar from "@/Components/Users/navbar/navbar.vue";
import {ref} from "vue";
import propertiesComponent from '@/Components/Users/featured-properties.vue';
import image from '@/assets/images/bg/03.jpg';

const sellImage = ref(image);

const props = defineProps(['properties']);
</script>
