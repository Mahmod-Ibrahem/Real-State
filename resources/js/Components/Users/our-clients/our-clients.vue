<template>
    <div class="row justify-content-center">
        <div class="col">
            <div class="section-title text-center mb-4 pb-2">
                <h4 class="title mb-3">What Our Client Say ?</h4>
                <p class="text-muted para-desc mb-0 mx-auto">A great plateform to buy, sell and rent your properties
                    without any agent or commisions.</p>
            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div class="row">
        <div class="col-12 mt-4">
            <div class="tiny-three-item">
                <div v-for="item in datas" :key="item" class="tiny-slider client-testi !flex-row">
                    <div class="card border-0 bg-white">
                        <div class="card-body p-4 rounded-3 shadow m-2">
                            <i
                                class="mdi mdi-format-quote-open display-1 text-primary opacity-25 position-absolute end-0 top-0"></i>

                            <div class="d-flex">
                                <img :src="item.image" class="avatar avatar-md-sm rounded-circle shadow-md" alt="">
                                <div class="flex-1 ms-3">
                                    <h6 class="mb-0">{{ item.name }}</h6>
                                    <small class="text-muted">{{ item.position }}</small>
                                </div>
                            </div>

                            <p class="text-muted fst-italic mb-0 mt-4">{{ item.title }}
                            </p>

                            <ul class="list-unstyled mb-0 mt-3 text-warning h5">
                                <li v-for="star in item.star" :key="star" class="list-inline-item"><i :class="star"></i>
                                </li>
                            </ul>

                        </div>
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { tns } from 'tiny-slider/src/tiny-slider';
import 'tiny-slider/dist/tiny-slider.css';


const datas = ref([
    {
        image: '/assets/images/client/01.jpg',
        name: 'Young Bonetti',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        image: ('../../assets/images/client/02.jpg'),
        name: 'Ofelia Bell',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today.One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        image: ('../../assets/images/client/03.jpg'),
        name: 'Barbara Carpenter',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        image: ('../../assets/images/client/04.jpg'),
        name: 'Jesus Day',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today.One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        image: ('../../assets/images/client/05.jpg'),
        name: 'Jennifer Smith',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today.One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        image: ('../../assets/images/client/06.jpg'),
        name: 'Michael Wilkes',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today.One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
])

onMounted(() => {
    tns({
        container: '.tiny-three-item',
        controls: false,
        mouseDrag: true,
        loop: true,
        rewind: true,
        autoplay: true,
        autoplayButtonOutput: false,
        autoplayTimeout: 3000,
        navPosition: "bottom",
        speed: 400,
        gutter: 12,
        responsive: {
            992: {
                items: 3
            },

            767: {
                items: 2
            },

            320: {
                items: 1
            },
        },
    });
});

</script>

<style lang="scss" scoped></style>
