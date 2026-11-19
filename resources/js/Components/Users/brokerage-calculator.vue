<template>
    <div class="row justify-content-center">
        <div class="col">
            <div class="section-title text-center mb-4 pb-2">
                <h4 class="title mb-3">Brokerage Calculator</h4>
                <p class="text-muted para-desc mb-0 mx-auto">A great plateform to buy, sell and rent your properties
                    without any agent or commisions.</p>
            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div class="row justify-content-center mt-4 pt-2">
        <div class="col-lg-8 col-12">
            <div class="p-4 shadow rounded-3" role="form">
                <ul class="list-unstyled d-flex justify-content-between mb-0">
                    <li class="h6 mb-0">Min EGP 1000000</li>
                    <li class="h6 mb-0">Max EGP 1000000000</li>
                </ul>

                <div class="row">
                    <div class="col-sm-12 mb-4 mt-4">
                        <label for="slider" class="form-label"></label>
                        <input type="range" v-model="sliderValue" value="1000000" min="1000000" max="1000000000"
                               class="form-range custom-range" id="slider">
                    </div><!--end col-->
                </div><!--end row-->

                <ul class="list-unstyled text-center d-md-flex justify-content-between mb-0">
                    <li>
                        <ul class="mb-0 text-md-start brokerage-form list-unstyled">
                            <li class="h5 mb-0"><label class="control-label">Total Value (EGP)</label></li>
                            <li class="h5 mb-0"><input type="hidden" id="amount" class="form-control"><span
                                class="text-primary">EGP</span>
                                <p class="mb-0 d-inline-block h5 text-primary ms-1" id="amount-label">{{ sliderValue }}
                                </p>
                            </li>
                        </ul>
                    </li>

                    <li class="mt-2 mt-sm-0">
                        <ul class="mb-0 text-md-end brokerage-form list-unstyled">
                            <li class="h5 mb-0"><label class="control-label">Brokerage Fee</label></li>
                            <li class="h5 mb-0"><input type="hidden" id="saving" class="form-control mb-0"><span
                                class="text-primary">EGP</span>
                                <p class="mb-0 d-inline-block h5 text-primary ms-1" id="saving-label">{{ brokerageFee }}
                                </p>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import {ref, computed} from 'vue';

const sliderValue = ref(1000000);
const brokerageFee = computed(() => parseFloat(sliderValue.value * 0.018).toFixed(2));
</script>

<style lang="scss" scoped></style>
