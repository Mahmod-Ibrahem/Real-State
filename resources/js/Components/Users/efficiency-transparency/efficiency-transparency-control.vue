<template>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-5 col-md-6">
                <div class="position-relative overflow-hidden shadow p-3 rounded-top-pill rounded-5 bg-white">
                    <img src="../../assets/images/hero.jpg" class="img-fluid rounded-top-pill rounded-5"
                        alt="work-image">

                    <div class="position-absolute top-50 start-50 translate-middle">
                        <a @click="toggle" data-type="youtube" data-id="yba7hPeTSjk"
                            class="avatar avatar-md-md rounded-pill shadow card d-flex justify-content-center align-items-center lightbox">
                            <i class="mdi mdi-play mdi-24px text-primary"></i>
                        </a>
                    </div>
                </div>
            </div><!--end col-->

            <div class="col-lg-7 col-md-6 mt-4 mt-sm-0 pt-2 pt-sm-0">
                <div class="section-title ms-lg-5">
                    <h6 class="text-primary fw-medium mb-2">Our story: Towntor</h6>
                    <h4 class="title mb-3">Efficiency. <br> Transparency. Control.</h4>
                    <p class="text-muted para-desc mb-0">Towntor developed a platform for the Real Estate marketplace
                        that allows buyers and sellers to easily execute a transaction on their own. The platform drives
                        efficiency, cost transparency and control into the hands of the consumers. Towntor is Real
                        Estate Redefined.</p>

                    <div class="row">
                        <div class="col-4 py-3">
                            <div class="counter-box text-center">
                                <h1 class="mb-0 fw-semibold d-flex justify-content-center"><count-up
                                        class="counter-value" :end-val="1548">1</count-up>+
                                </h1>
                                <h6 class="counter-head text-muted fw-normal">Investment</h6>
                            </div><!--end counter box-->
                        </div><!--end col-->

                        <div class="col-4 py-3">
                            <div class="counter-box text-center">
                                <h1 class="mb-0 fw-semibold d-flex justify-content-center"><count-up
                                        class="counter-value" :end-val="25">1</count-up>+</h1>
                                <h6 class="counter-head text-muted fw-normal">Awards</h6>
                            </div><!--end counter box-->
                        </div><!--end col-->

                        <div class="col-4 py-3">
                            <div class="counter-box text-center">
                                <h1 class="mb-0 fw-semibold d-flex justify-content-center"><count-up
                                        class="counter-value" :end-val="9">1</count-up>+</h1>
                                <h6 class="counter-head text-muted fw-normal">Profitability</h6>
                            </div><!--end counter box-->
                        </div><!--end col-->
                    </div><!--end row-->
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->

    <div :class="isActive === false ? 'position-fixed ' : 'd-none'" class="modal-video" tabindex="-1" role="dialog"
        area-modal="true" aria-label="You just opened the modal video">
        <div class="modal-video-body">
            <div class="modal-video-inner">
                <div class="modal-video-movie-wrap" style="">
                    <iframe width="460" height="230" src="https://www.youtube.com/embed/S_CGed6E610?feature=oembed"
                        frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
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
import { ref } from 'vue';
import CountUp from 'vue-countup-v3';

const isActive = ref(true);

const toggle = () => {
    isActive.value = !isActive.value
}
</script>

<style lang="scss" scoped></style>