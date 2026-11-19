<template>
    <div class="container-fluid bg-building-pic mt-16">
        <div class="position-absolute top-0 left-0 right-0 bottom-0 w-100 h-100 opacity-25"
            :style="{'background-image': 'url(' + ('../assets/images/map.png') + ')', backgroundPosition: 'center'}"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col">
                    <div class="section-title text-center mb-4 pb-2">
                        <h4 class="title mb-3">Trusted by more than 1.2K members</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->

            <div class="row">
                <div class="col-4 py-3">
                    <div class="counter-box text-center">
                        <h1 class="mb-0 fw-semibold d-flex justify-content-center"><count-up class="counter-value"
                                :end-val="1212">1</count-up>+</h1>
                        <h6 class="counter-head text-muted fw-normal">Members</h6>
                    </div><!--end counter box-->
                </div><!--end col-->

                <div class="col-4 py-3">
                    <div class="counter-box text-center">
                        <h1 class="mb-0 fw-semibold d-flex justify-content-center"><count-up class="counter-value"
                                :end-val="25">1</count-up>+</h1>
                        <h6 class="counter-head text-muted fw-normal">Benefits</h6>
                    </div><!--end counter box-->
                </div><!--end col-->

                <div class="col-4 py-3">
                    <div class="counter-box text-center">
                        <h1 class="mb-0 fw-semibold d-flex justify-content-center"><count-up class="counter-value"
                                :end-val="3">1</count-up>+</h1>
                        <h6 class="counter-head text-muted fw-normal">Memberships</h6>
                    </div><!--end counter box-->
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </div><!--end container-->
</template>

<script setup>
import CountUp from 'vue-countup-v3';

</script>

<style lang="scss" scoped></style>
