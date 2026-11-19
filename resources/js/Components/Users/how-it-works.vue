<template>
    <div class="row justify-content-center">
        <div class="col">
            <div class="section-title text-center mb-4 pb-2">
                <h4 class="title mb-3">How It Works</h4>
            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div class="row g-4 mt-0">
        <div v-for="item in datas" :key="item" class="col-md-4">
            <div class="position-relative features text-center mx-lg-4 px-md-1">
                <div class="feature-icon position-relative overflow-hidden d-flex justify-content-center">
                    <i data-feather="hexagon" class="hexagon"></i>
                    <div class="position-absolute top-50 start-50 translate-middle">
                        <i :data-feather="item.icon" class="fea icon-m-md text-primary"></i>
                    </div>
                </div>

                <div class="mt-4">
                    <p href="" class="fw-medium title text-dark fs-5">{{ item.name }}</p>
                    <p class="text-muted mt-3 mb-0">{{ item.title }}</p>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import {ref, onMounted} from 'vue';
import feather from 'feather-icons';

const datas = ref([
    {
        icon: 'home',
        name: 'Evaluate Property',
        title: "Our team has the knowledge and practical experience that qualifies us to evaluate your property based on scientific, cognitive, and real foundations."
    },
    {
        icon: 'briefcase',
        name: 'Meeting with Agent',
        title: "With a professional and experienced team, investments become in the fastest and most successful way. The trustees of your real estate wealth and are always with you at all times records."
    },
    {
        icon: 'key',
        name: 'Close the Deal',
        title: "With an opinion from an honest expert, will be able to make a decision based on scientific factors and professional foundations, Conclude contracts and we are with you and still."
    },
]);

onMounted(() => {
    feather.replace()
});

</script>

<style lang="scss" scoped></style>
