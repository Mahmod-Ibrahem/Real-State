<template>
    <div class="row">
        <div class="col-12">
            <div class="tiny-slide-three">
                <div v-for="item in props.relatedProperties" :key="item" class="tiny-slide">
                    <div class="card property border-0 shadow position-relative overflow-hidden rounded-3 m-3">
                        <div class="property-image position-relative overflow-hidden shadow">
                            <img :src="item.image" class="img-fluid" alt="">
                            <ul class="list-unstyled property-icon">
                                <!--                                <li class=""><a href="javascript:void(0)"-->
                                <!--                                        class="btn btn-sm btn-icon btn-pills btn-primary"><i data-feather="home"-->
                                <!--                                            class="icons"></i></a></li>-->
                                <!--                                <li class="mt-1"><a href="javascript:void(0)"-->
                                <!--                                        class="btn btn-sm btn-icon btn-pills btn-primary"><i data-feather="heart"-->
                                <!--                                            class="icons"></i></a></li>-->
                                <!--                                <li class="mt-1"><a href="javascript:void(0)"-->
                                <!--                                        class="btn btn-sm btn-icon btn-pills btn-primary"><i data-feather="camera"-->
                                <!--                                            class="icons"></i></a></li>-->
                            </ul>
                        </div>
                        <div class="card-body content p-4">
                            <Link :href="route('listing.show', item.slug)"
                                  class="title fs-5 text-dark fw-medium">{{
                                    item.name
                                }}
                            </Link>

                            <ul
                                class="list-unstyled mt-3 py-3 border-top border-bottom d-flex align-items-center justify-content-between">
                                <li class="d-flex align-items-center me-3">
                                    <i class="mdi mdi-arrow-expand-all fs-5 me-2 text-primary"></i>
                                    <span class="text-muted">{{ item.sqft }}</span>
                                </li>

                                <li class="d-flex align-items-center me-3">
                                    <i class="mdi mdi-bed fs-5 me-2 text-primary"></i>
                                    <span class="text-muted">{{ item.bedrooms }}</span>
                                </li>

                                <li class="d-flex align-items-center">
                                    <i class="mdi mdi-shower fs-5 me-2 text-primary"></i>
                                    <span class="text-muted">{{ item.bathrooms }}</span>
                                </li>
                            </ul>
                            <ul class="list-unstyled d-flex justify-content-between mt-2 mb-0">
                                <li class="list-inline-item mb-0">
                                    <span class="text-muted">Price</span>
                                    <p class="fw-medium mb-0">{{ item.price }}</p>
                                </li>
                            </ul>
                        </div>
                    </div><!--end items-->
                </div><!--end col-->
            </div>
        </div>
    </div><!--end row-->
</template>

<script setup>
import {ref, onMounted} from 'vue';
import {Link} from '@inertiajs/vue3'
import {tns} from 'tiny-slider/src/tiny-slider';

const props = defineProps(['relatedProperties'])

const datas = ref([
    {
        id: 1,
        image: ('../assets/images/property/1.jpg'),
        title: '10765 Hillshire Ave, Baton Rouge, LA 70810, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 2,
        image: ('../assets/images/property/2.jpg'),
        title: '59345 STONEWALL DR, Plaquemine, LA 70764, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 3,
        image: ('../assets/images/property/3.jpg'),
        title: '3723 SANDBAR DR, Addis, LA 70710, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 4,
        image: ('../assets/images/property/4.jpg'),
        title: 'Lot 21 ROYAL OAK DR, Prairieville, LA 70769, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 5,
        image: ('../assets/images/property/5.jpg'),
        title: '710 BOYD DR, Unit #1102, Baton Rouge, LA 70808, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 6,
        image: ('../assets/images/property/6.jpg'),
        title: '5133 MCLAIN WAY, Baton Rouge, LA 70809, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
])

onMounted(() => {
    tns({
        container: '.tiny-slide-three',
        controls: true,
        mouseDrag: true,
        loop: true,
        rewind: true,
        autoplay: true,
        autoplayButtonOutput: false,
        autoplayTimeout: 3000,
        navPosition: "bottom",
        controlsText: ['<i class="mdi mdi-chevron-left "></i>', '<i class="mdi mdi-chevron-right"></i>'],
        nav: false,
        speed: 400,
        gutter: 0,
        responsive: {
            1025: {
                items: 3
            },

            992: {
                items: 2
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
