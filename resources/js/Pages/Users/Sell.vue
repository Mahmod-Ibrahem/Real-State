<template>
    <Head>
        <title>Sell Your Property in Egypt | List Apartments, Villas & More – Qeima
        </title>
        <meta name="description"
              content="Easily sell your property in Egypt with Qeima. List your apartment, villa, or commercial space and reach thousands of serious buyers. Fast, secure, and hassle-free property selling across Egypt. Start listing now!
">
    </Head>
    <navbar :navLight="'nav-light nav-left'" :logoLight="false"/>
    <!-- Hero Start -->
    <section class="bg-half-170 d-table w-100"
             :style="{ 'background-image': `url(${sellImage})`, backgroundPosition: 'top' }">
        <div class="bg-overlay bg-gradient-overlay-2"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-12">
                    <div class="title-heading text-center">
                        <p class="text-white-50 para-desc mx-auto mb-0">Sell Properties</p>
                        <h5 class="heading fw-semibold mb-0 sub-heading text-white title-dark">Sell Faster. Save
                            Thousands.</h5>
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
            <howitworks/>
        </div>
        <div class="container mt-16">
            <calculator/>
        </div>

        <div class="container mt-16">
            <get-in-touch header="Want To List Your Property On Our Platform ? Get In Touch !"/>
        </div>
    </section>
    <footers/>
    <backtotop/>
</template>

<script setup>
import navbar from '@/Components/Users/navbar/navbar.vue';
import howitworks from '@/Components/Users/how-it-works.vue';
import calculator from '@/Components/Users/brokerage-calculator.vue';
import footers from '@/Components/Users/footer/footer.vue';
import backtotop from '@/Components/Users/back-to-top.vue'
import image from '@/assets/images/bg/03.jpg';
import {ref} from "vue";
import {Head} from "@inertiajs/vue3";
import ContactUs from "@/Pages/Users/ContactUs.vue";
import ContactForm from "@/Components/Users/contact-form.vue";
import GetInTouch from "@/Components/Users/get-in-touch.vue";

const sellImage = ref(image);

</script>

<style lang="scss" scoped></style>
