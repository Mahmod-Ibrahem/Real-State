<template>
    <div class="container">
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
                        <p href="" class="fw-medium title text-dark fs-5 select-none">{{ item.name }}</p>
                        <p class="text-muted mt-3 mb-0">{{ item.title }}</p>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</template>

<script setup>
import { ref } from 'vue';

const datas = ref([
    {
        icon: 'heart',
        name: 'Comfortable',
        title: "If the distribution of letters and 'words' is random, the reader will not be distracted from making."
    },
    {
        icon: 'shield',
        name: 'Extra Security',
        title: "If the distribution of letters and 'words' is random, the reader will not be distracted from making.",
    },
    {
        icon: 'star',
        name: 'Luxury',
        title: "If the distribution of letters and 'words' is random, the reader will not be distracted from making."
    },
    {
        icon: 'dollar-sign',
        name: 'Best Price',
        title: "If the distribution of letters and 'words' is random, the reader will not be distracted from making."
    },
    {
        icon: 'map-pin',
        name: 'Stratagic Location',
        title: "If the distribution of letters and 'words' is random, the reader will not be distracted from making."
    },
    {
        icon: 'pie-chart',
        name: 'Efficient',
        title: "If the distribution of letters and 'words' is random, the reader will not be distracted from making."
    }
])

</script>

<style lang="scss" scoped></style>
