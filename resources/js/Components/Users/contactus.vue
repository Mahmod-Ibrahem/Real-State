<template>
    <div class="container mt-16">
        <div class="row g-4">
            <div v-for="item in datas" :key="item" class="col-md-4">
                <div class="position-relative features text-center mx-lg-4 px-md-1">
                    <div class="feature-icon position-relative overflow-hidden d-flex justify-content-center">
                        <i data-feather="hexagon" class="hexagon"></i>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i :data-feather="item.icon" class="fea icon-m-md text-primary"></i>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5 class="mb-3">{{ item.name }}</h5>
                        <a :href="item.link" class="text-primary">{{ item.subname }}</a>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-md-4">
                <div class="position-relative features text-center mx-lg-4 px-md-1">
                    <div class="feature-icon position-relative overflow-hidden d-flex justify-content-center">
                        <i data-feather="hexagon" class="hexagon"></i>
                        <div class="position-absolute top-50 start-50 translate-middle">
                            <i data-feather="map-pin" class="fea icon-m-md text-primary"></i>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5 class="mb-3">Location</h5>
                        <a @click="toggle" class="lightbox text-primary" data-type="iframe" data-group="iframe"
                           data-width="1024px" data-height="576px">View on Google map</a>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->

        <div :class="isActive === false ? 'position-fixed ' : 'd-none'" class="modal-video" tabindex="-1" role="dialog"
             area-modal="true" aria-label="You just opened the modal video">
            <div class="modal-video-body">
                <div class="modal-video-inner">
                    <div class="modal-video-movie-wrap" style="">
                        <iframe width="460" height="230"
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d39206.002432144705!2d-95.4973981212445!3d29.709510002925988!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8640c16de81f3ca5%3A0xf43e0b60ae539ac9!2sGerald+D.+Hines+Waterwall+Park!5e0!3m2!1sen!2sin!4v1566305861440!5m2!1sen!2sin"
                                frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen="" tabindex="-1">
                        </iframe>
                        <a @click="toggle" class="mdi mdi-close m-3 text-light h4 position-class title-dark"> </a>
                    </div>
                    <div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid mt-16">
        <div class="row">
            <div class="col-12 p-0">
                <div class="card map border-0">
                    <div class="card-body p-0">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3455.583598635728!2d31.4315327256918!3d29.99139473828957!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x14583d0036f25a1b%3A0xebb38686711b5489!2sCasarica!5e0!3m2!1sar!2seg!4v1747922572117!5m2!1sar!2seg"
                            style="border:0" allowfullscreen></iframe>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</template>

<script setup>
import {ref, onMounted} from 'vue';
import feather from 'feather-icons';

const isActive = ref(true);

const datas = ref([
    {
        icon: 'phone',
        name: 'Phone',
        title: 'Start working with Towntor that can provide everything',
        subname: '+20225618036',
        link: 'tel:+152534-468-854'
    },
    {
        icon: 'mail',
        name: 'Email',
        title: 'Start working with Towntor that can provide everything',
        subname: 'info@qeima.com',
        link: 'mailto:info@qeima'
    },
])

const toggle = () => {
    isActive.value = !isActive.value;
}

onMounted(() => {
    feather.replace()
})

</script>

<style lang="scss" scoped></style>
