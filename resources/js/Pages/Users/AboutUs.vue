<template>
    <Head>
        <title>About Qeima | Egypt’s Leading Real Estate Platform for Buying, Selling & Renting
        </title>
        <meta name="description"
              content="Learn more about Qeima – your trusted real estate partner in Egypt. Our mission is to simplify property search and connect buyers,
              sellers, and renters with verified listings across the country. Discover our story and values.
">
    </Head>
    <navbar :navLight="'nav-light nav-left'" :logoLight="false"/>
    <!-- Hero Start -->
    <section class="bg-half-170 d-table w-100"
             :style="{ 'background-image': `url(${aboutImage})`, backgroundPosition: 'center' }">
        <div class="bg-overlay bg-gradient-overlay-2"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-12">
                    <div class="title-heading text-center">
                        <p class="text-white-50 para-desc mx-auto mb-0">Our story: Qeima</p>
                        <h5 class="heading fw-semibold mb-0 sub-heading text-white title-dark">About Us</h5>
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
            <efficiency :aboutVideo="props.aboutVideo"/>
        </div>
        <div class="container mt-16">
            <howitwork/>
        </div>
        <trusted/>
        <!--        <blogs/>-->
        <getintouch/>
    </section>
    <footers/>
    <backtotop/>
</template>

<script setup>
import navbar from '@/Components/Users/navbar/navbar.vue';
import efficiency from '@/Components/Users/efficiency-transparency/efficiency-transparency-our.vue';
import howitwork from '@/Components/Users/how-it-works.vue';
import trusted from '@/Components/Users/trusted-users.vue';
import blogs from '@/Components/Users/blog/blogs.vue';
import getintouch from '@/Components/Users/get-in-touch.vue';
import footers from '@/Components/Users/footer/footer.vue';
import backtotop from '@/Components/Users/back-to-top.vue'
import {ref} from "vue";
import aboutBG from '@/assets/images/bg/03.jpg';
import {Head} from "@inertiajs/vue3";

const props = defineProps({
    aboutVideo: String,
})
const aboutImage = ref(aboutBG);
</script>

<style lang="scss" scoped></style>
