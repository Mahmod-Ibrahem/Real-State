<template>
    <Head>
        <title>Qeima Features | Smart Tools to Find, List & Manage Properties in Egypt
        </title>
        <meta name="description"
              content="Discover powerful features on Qeima designed to simplify real estate in Egypt. From advanced search filters
              and interactive maps to secure messaging and listing management—explore everything that makes property finding easier.
">
    </Head>
    <navbar :navLight="'nav-light nav-left'"/>
    <!-- Hero Start -->
    <section class="bg-half-170 d-table w-100"
             :style="{ 'background-image': `url(${FeatureImage})`, backgroundPosition: 'bottom' }">
        <div class="bg-overlay bg-gradient-overlay-2"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-12">
                    <div class="title-heading text-center">
                        <p class="text-white-50 para-desc mx-auto mb-0">Benefits</p>
                        <h5 class="heading fw-semibold mb-0 sub-heading text-white title-dark">Services /
                            Features</h5>
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
        <features/>
        <trusted/>
        <!--        <div class="container mt-100 mt-60">-->
        <!--            <clients />-->
        <!--        </div>-->
    </section>
    <footers/>
    <backtotop/>
</template>

<script setup>
import navbar from '@/Components/Users/navbar/navbar.vue';
import features from '@/Components/Users/features-page.vue';
import trusted from '@/Components/Users/trusted-users.vue';
import clients from '@/Components/Users/our-clients/clients-say.vue';
import footers from '@/Components/Users/footer/footer.vue'
import backtotop from '@/Components/Users/back-to-top.vue'
import aboutBG from '@/assets/images/bg/03.jpg';
import {ref} from "vue";
import {Head} from "@inertiajs/vue3";

const FeatureImage = ref(aboutBG);

</script>

<style lang="scss" scoped></style>
