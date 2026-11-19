<template>
    <Head>
        <title>{{$page.props.metaTitle}}</title>
        <meta name="description"
              content="Find your dream property in Egypt with Qeima – the best platform for buying, selling, and renting real estate.">
    </Head>

    <navbar :navLight="'nav-left'" class="" :logoLight="true"/>
    <!-- Hero -->
    <section class="position-relative pt-10 md:pt-20">
        <div class="container-fluid px-md-4 px-2 mt-2">
            <div class="bg-home-one d-table w-100 shadow rounded-3 overflow-hidden duraction-500" id="home">
                <div class="bg-overlay image-wrap" id="hero-images"
                     :style="{ 'background-image': `url(${randomImage})`, backgroundPosition: 'top' }"></div>
                <div class="bg-overlay bg-black opacity-50"></div>

                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-12">
                            <div class="title-heading">
                                <h4 class="heading fw-bold text-white title-dark mb-3">Qualified Estate Investing <br>
                                    <span>
                                        <vue-typewriter-effect class="typewrite text-primary" data-period="2000"
                                                               :strings='["Misr", "Arab"]'/></span> Network
                                </h4>
                                <p class="para-desc text-white title-dark mb-0">Estate Investments PLATFORM Will help
                                    you to buy, sell, rent and Find your Qeima.
                                </p>
                            </div>
                        </div>
                    </div><!--end row-->
                </div><!--end container-->
            </div>
        </div><!--end container-->
    </section><!--end section-->

    <section class="section pt-5">
        <BuySell
            v-if="propertyTypes.data.length > 0"
            :purposes="purposes" :propertyTypes="propertyTypes.data" :cities="cities.data"
            :areas="areas.data"/>

        <div class="container  mt-16">
            <efficiency :homeVideo="props.homeVideo"/>
        </div>
        <div class="container  mt-16">
            <howitwork/>
        </div>
        <div class="container mt-16">
            <listingCategory :categories="props.propertyTypes"/>
        </div>
        <properties v-for="classification in props.classifications" :properties="classification.properties.data"
                    :header="classification.name +  ' Properties'"/>
        <!--                <div class="container  mt-60">-->
        <!--                    <clients />-->
        <!--                </div>-->
        <div class="container  mt-16">
            <broker :brokers="props.brokers"/>
        </div>
        <div v-if="faqs.data.length" class="container mt-16">
            <FaqSection :faqs="faqs.data"/>
        </div>
        <getintouch/>
    </section>
    <footers :footerimg="true"/>
    <backtotop/>
</template>

<script setup>
import {Head} from '@inertiajs/vue3';
import {ref, onMounted} from 'vue';
import VueTypewriterEffect from "vue-typewriter-effect";
import navbar from '@/Components/Users/navbar/navbar.vue';
import image from '@/assets/images/bg/01.jpg';
import image2 from '@/assets/images/bg/02.jpg';
import image3 from '@/assets/images/bg/03.jpg';
import BuySell from '@/Components/Users/buy-sell-rent/BuySellRent.vue';
import efficiency from '@/Components/Users/efficiency-transparency/efficiency-transparency.vue';
import howitwork from '@/Components/Users/how-it-works.vue';
import properties from '@/Components/Users/featured-properties.vue';
import broker from '@/Components/Users/property-broker.vue';
import footers from '@/Components/Users/footer/footer.vue';
import getintouch from '@/Components/Users/get-in-touch.vue';
import backtotop from '@/Components/Users/back-to-top.vue'
import listingCategory from '@/Components/Users/listing-categories.vue';
import FaqSection from '@/Components/Users/FaqSection.vue';

const props = defineProps({
    homeVideo: Object,
    propertyTypes: Object,
    classifications: Object,
    properties: Object,
    purposes: Array,
    brokers: Object,
    cities: Object,
    areas: Object,
    // Users/FaqResource collection
    faqs: Object,
})
const randomImage = ref('')

const backgroundImages = ref([
    image,
    image2,
    image3
])
const changeBackground = () => {
    const randomIndex = Math.floor(Math.random() * backgroundImages.value.length);
    randomImage.value = backgroundImages.value[randomIndex];
}
changeBackground();
setInterval(changeBackground, 5000);


</script>

<style scoped></style>
