<template>
    <Head>
        <title>Contact Qeima | Get in Touch with Egypt’s Trusted Property Platform
        </title>
        <meta name="description"
              content="Have questions or need support? Contact Qeima today. Our team is here to help with property listings, buying, selling, or
              renting in Egypt. Reach us via phone, email, or our contact form. We're happy to assist you!
">
    </Head>
    <navbar :navLight="'nav-light nav-left'" :logoLight="false"/>

    <!-- Hero Start -->
    <section class="bg-half-170 d-table w-100"
             :style="{'background-image': `url(${contactImage})`, backgroundPosition: 'top'}">
        <div class="bg-overlay bg-gradient-overlay-2"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-12">
                    <div class="title-heading text-center">
                        <p class="text-white-50 para-desc mx-auto mb-0">Get in touch !</p>
                        <h5 class="heading fw-semibold mb-0 sub-heading text-white title-dark">Contact us</h5>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="position-middle-bottom">
                <nav aria-label="breadcrumb" class="d-block">
                    <ul class="breadcrumb breadcrumb-muted mb-0 p-0">
                        <li class="breadcrumb-item">
                            <Link :href="route('home')">Qeima</Link>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Contact us</li>
                    </ul>
                </nav>
            </div>
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

    <section class="section pb-0">
        <contact-form/>
        <contact/>
    </section>
    <footers/>
    <backtotop/>
</template>

<script setup>
import navbar from '@/Components/Users/navbar/navbar.vue';
import contact from '@/Components/Users/contactus.vue';
import footers from '@/Components/Users/footer/footer.vue';
import backtotop from '@/Components/Users/back-to-top.vue'
import {Head} from "@inertiajs/vue3";
import contactBG from '@/assets/images/bg/04.jpg';
import {ref} from "vue";
import {Link} from "@inertiajs/vue3";
import ContactForm from "@/Components/Users/contact-form.vue";

const contactImage = ref(contactBG);
</script>

<style lang="scss" scoped></style>
