<template>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 col-md-6">
                <div class="about-left">
                    <div class="position-relative shadow p-2 rounded-top-pill rounded-5 bg-white img-one">
                        <img :src="aboutImage" class="img-fluid rounded-top-pill rounded-5"
                             alt="work-image">

                        <div class="cta-video">
                            <a @click="toggle" data-type="youtube" data-id="yba7hPeTSjk"
                               class="avatar avatar-md-md rounded-pill shadow card d-flex justify-content-center align-items-center lightbox">
                                <i class="mdi mdi-play mdi-24px text-primary"></i>
                            </a>
                        </div>

                        <div class="position-absolute top-0 start-0 z-n1">
                            <img :src="dots" class="avatar avatar-xl-large" alt="">
                        </div>
                    </div>

                    <div class="img-two shadow rounded-3 overflow-hidden p-2 bg-white">
                        <img :src="secondryImage" class="img-fluid rounded-3" alt="work-image">
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-6 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="section-title ms-lg-5">
                    <h6 class="text-primary fw-medium mb-2">Our story: Qeima</h6>
                    <h4 class="title mb-3">Efficiency. <br> Transparency. Control.</h4>
                    <p class="text-muted para-desc mb-0">Our story
                        Casarica Properties developed a platform for the Real Estate marketplace that allows buyers and
                        sellers to easily execute a transaction. The platform drives efficiency, cost transparency and
                        control into the hands of the consumers.
                        Casarica Properties began in Egypt December 2017 with 20 years of back experience in real estate
                        industry over the world, Local estates service business with international experience,
                        Join our membership.</p>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->

    <div :class="isActive === false ? 'position-fixed ' : 'd-none'" class="modal-video" tabindex="-1" role="dialog"
         area-modal="true" aria-label="You just opened the modal video">
        <div class="modal-video-body">
            <div class="modal-video-inner">
                <div class="modal-video-movie-wrap" style="">
                    <iframe width="460" height="230" :src="aboutVideo.content"
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
</template>

<script setup>
import {ref} from 'vue';
import aboutImage from "@/assets/images/hero.jpg"
import dots from "@/assets/images/svg/dots.svg"
import secondryImage from "@/assets/images/1.jpg"

const isActive = ref(true);
const props = defineProps({
    aboutVideo: String
})
const toggle = () => {
    isActive.value = !isActive.value
}

</script>

<style lang="scss" scoped></style>
