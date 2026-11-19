<template>
    <div class="row justify-content-center">
        <div class="col">
            <div class="section-title text-center mb-4 pb-2">
                <h4 class="title mb-3">Property Broker</h4>
            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div v-if="brokers" class="row g-4 mt-0">
        <div v-for="broker in brokers" :key="item" class="col-lg-3 col-md-4 col-12">
            <div class="card team team-primary text-center">
                <div
                    class="card-img team-image d-inline-block mx-auto rounded-pill avatar avatar-ex-large overflow-hidden">
                    <img :src="broker.image" class="img-fluid" alt="">
                    <div class="card-overlay avatar avatar-ex-large rounded-pill"></div>

                    <ul class="list-unstyled team-social mb-0">
                        <li v-if="broker.facebook" class="list-inline-item me-1"><a
                            :href="broker.facebook" class="btn btn-sm btn-pills btn-icon"><i data-feather="facebook"
                                                                                             class="icons fea-social"></i></a>
                        </li>
                        <li v-if="broker.instagram" class="list-inline-item me-1"><a
                            :href="broker.instagram" class="btn btn-sm btn-pills btn-icon"><i data-feather="instagram"
                                                                                             class="icons fea-social"></i></a>
                        </li>
                        <li v-if="broker.twitter" class="list-inline-item me-1"><a
                            :href="broker.twitter" class="btn btn-sm btn-pills btn-icon"><i data-feather="twitter"
                                                                                             class="icons fea-social"></i></a>
                        </li>
                    </ul><!--end icon-->
                </div>

                <div class="content mt-3">
                    <p class="text-dark h5 mb-0 title">{{ broker.name }}</p>
                    <h6 class="text-muted mb-0 fw-normal">{{ broker.job_title }}</h6>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import {ref, onMounted} from 'vue';
import feather from 'feather-icons';

defineProps({
    brokers: Object,
})

onMounted(() => {
    feather.replace()
});
</script>

<style lang="scss" scoped></style>
