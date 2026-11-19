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

    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7 mt-4">
            <i class="mdi mdi-format-quote-open display-1 position-absolute start-0 top-0" style="opacity: 0.05;"></i>
            <i class="mdi mdi-format-quote-open display-1 position-absolute end-0 bottom-0" style="opacity: 0.05;"></i>
            <div class="tiny-single-item">
                <div v-for="item in datas" :key="item" class="tiny-slider client-testi">
                    <div class="text-center">
                        <div class="m-2">
                            <img :src="item.image" class="avatar avatar-small rounded-circle shadow-md" alt="">
                            <div class="flex-1 mt-4">
                                <h6 class="mb-0">{{ item.name }}</h6>
                                <small class="text-muted">{{ item.position }}</small>
                            </div>

                            <p class="text-muted fst-italic mb-0 mt-3">{{ item.title }}
                            </p>

                            <ul class="list-unstyled mb-0 mt-3 text-warning fs-6">
                                <li v-for="star in item.star" :key="star" class="list-inline-item me-1"><i
                                        :class="star"></i></li>
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
        image: ('../../assets/images/client/01.jpg'),
        name: 'Young Bonetti',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        image: ('../../assets/images/client/02.jpg'),
        name: 'Ofelia Bell',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
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
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        image: ('../../assets/images/client/05.jpg'),
        name: 'Jennifer Smith',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        image: ('../../assets/images/client/06.jpg'),
        name: 'Michael Wilkes',
        position: 'Product Manager',
        title: '" It seems that only fragments of the original text remain in the Lorem Ipsum texts used today. One may speculate that over the course of time certain letters were added or deleted at various positions within the text. "',
        star: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
])

onMounted(() => {
    tns({
        container: '.tiny-single-item',
        items: 1,
        controls: false,
        mouseDrag: true,
        loop: true,
        rewind: true,
        autoplay: true,
        autoplayButtonOutput: false,
        autoplayTimeout: 3000,
        navPosition: "bottom",
        speed: 400,
        gutter: 0,
    });
});
</script>

<style lang="scss" scoped></style>
