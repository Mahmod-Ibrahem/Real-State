<template>
    <div class="container-fluid mt-16">
        <div class="position-relative overflow-hidden rounded-3 shadow py-5"
            :style="{'background-image': 'url('+require('../assets/images/bg/05.jpg') + ')', backgroundPosition: 'center center', backgroundAttachment: 'fixed'}">
            <div class="bg-overlay"></div>
            <div class="container">
                <div class="row">
                    <div class="col-4 py-3">
                        <div class="counter-box text-center">
                            <h1 class="mb-0 text-white fw-bold d-flex justify-content-center"><count-up
                                    class="counter-value" :end-val="1548">1</count-up>+
                            </h1>
                            <h6 class="counter-head text-white fs-5 fw-semibold mb-0">Investment</h6>
                        </div><!--end counter box-->
                    </div><!--end col-->

                    <div class="col-4 py-3">
                        <div class="counter-box text-center">
                            <h1 class="mb-0 text-white fw-bold d-flex justify-content-center"><count-up
                                    class="counter-value" :end-val="25">1</count-up>+
                            </h1>
                            <h6 class="counter-head text-white fs-5 fw-semibold mb-0">Awards</h6>
                        </div><!--end counter box-->
                    </div><!--end col-->

                    <div class="col-4 py-3">
                        <div class="counter-box text-center">
                            <h1 class="mb-0 text-white fw-bold d-flex justify-content-center"><count-up
                                    class="counter-value" :end-val="9">1</count-up>+
                            </h1>
                            <h6 class="counter-head text-white fs-5 fw-semibold mb-0">Profitability</h6>
                        </div><!--end counter box-->
                    </div><!--end col-->
                </div><!--end row-->
            </div><!--end container-->
        </div>
    </div><!--end container fluid-->

</template>

<script setup>
import CountUp from 'vue-countup-v3';
</script>

<style lang="scss" scoped></style>
