<template>
    <div v-if="props.properties" class="row g-4 mt-0">
        <div v-for="item in properties" :key="item" class="col-lg-6 col-12">
            <div class="card property property-list border-0 shadow position-relative overflow-hidden rounded-3">
                <div class="d-md-flex">
                    <div class="property-image position-relative overflow-hidden shadow flex-md-shrink-0 rounded-3 m-2">
                        <PropertyStatusRibbon :status="item.status"/>
                        <Link :href="route('listing.show',[item.slug])">
                            <img :src="item.image_url" class="img-fluid h-100 w-100" alt="">
                        </Link>

                        <!--                        <ul class="list-unstyled property-icon">-->
                        <!--                            <li class="mt-1"><a href="javascript:void(0)"-->
                        <!--                                                class="btn btn-sm btn-icon btn-pills btn-primary"><i-->
                        <!--                                data-feather="heart"-->
                        <!--                                class="icons"></i></a></li>-->
                        <!--                            &lt;!&ndash;                            <li class="mt-1"><a href="javascript:void(0)"&ndash;&gt;-->
                        <!--                            &lt;!&ndash;                                    class="btn btn-sm btn-icon btn-pills btn-primary"><i data-feather="camera"&ndash;&gt;-->
                        <!--                            &lt;!&ndash;                                        class="icons"></i></a></li>&ndash;&gt;-->
                        <!--                        </ul>-->
                    </div>
                    <div class="card-body content p-3">
                        <Link :href="route('listing.show',[item.slug])"
                              class="title fs-5 text-dark fw-medium">{{ item.name }}
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
                </div>
            </div><!--end items-->
        </div><!--end col-->

        <div class="col-12 mt-4 pt-2">
            <div class="text-center">
                <Link :href="route('listing.index')" class="mt-3 fs-6 text-primary">View More Properties <i
                    class="mdi mdi-arrow-right align-middle"></i></Link>
            </div>
        </div>
    </div><!--end row-->
    <div v-else-if="gridproperty" class="row g-4">
        <div v-for="item in gridproperty" :key="item" class="col-12">
            <div class="card property property-list border-0 shadow position-relative overflow-hidden rounded-3">
                <div class="d-lg-flex">
                    <div
                        class="property-image md-full position-relative overflow-hidden shadow flex-md-shrink-0 rounded-3 m-2">
                        <PropertyStatusRibbon :status="item.status"/>
                        <Link :href="route('listing.show',[item.slug])">
                            <img :src="item.image_url" class="img-fluid h-100 w-100" alt="">
                        </Link>
                        <ul class="list-unstyled property-icon">
                            <!--                            <li class=""><a href="javascript:void(0)"-->
                            <!--                                            class="btn btn-sm btn-icon btn-pills btn-primary"><i data-feather="home"-->
                            <!--                                                                                                 class="icons"></i></a>-->
                            <!--                            </li>-->
                            <!--                            <li class="mt-1"><a href="javascript:void(0)"-->
                            <!--                                                class="btn btn-sm btn-icon btn-pills btn-primary"><i-->
                            <!--                                data-feather="heart"-->
                            <!--                                class="icons"></i></a></li>-->
                            <!--                            <li class="mt-1"><a href="javascript:void(0)"-->
                            <!--                                                class="btn btn-sm btn-icon btn-pills btn-primary"><i-->
                            <!--                                data-feather="camera"-->
                            <!--                                class="icons"></i></a></li>-->
                        </ul>
                    </div>
                    <div class="card-body content p-3">
                        <Link :href="route('listing.show', item.slug)"
                              class="title fs-5 text-dark fw-medium">{{ item.name }}
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
                                <p class="fw-medium mb-0">EGP {{ item.price }}</p>
                                <span v-if="item.lowest_monthly_installment" class="text-xs text-slate-500">
                                    From {{ formatNumber(item.lowest_monthly_installment) }} EGP / month
                                </span>
                            </li>
                        </ul>
                        <div v-if="item.owner" class="mt-[12px] flex items-center gap-2 border-t border-slate-100 pt-[12px]">
                            <OwnerAvatar :src="item.owner.avatar" :name="item.owner.name"/>
                            <span class="truncate text-sm text-slate-600">{{ item.owner.name }}</span>
                            <CheckBadgeIcon v-if="item.owner.verified" class="h-4 w-4 shrink-0 text-sky-500"
                                            aria-label="Verified phone" title="Verified phone"/>
                        </div>
                    </div>
                </div>
            </div><!--end items-->
        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import {ref, onMounted, defineProps} from 'vue';
import feather from 'feather-icons';
import {CheckBadgeIcon} from '@heroicons/vue/24/solid';
import OwnerAvatar from '@/Components/Users/OwnerAvatar.vue';
import PropertyStatusRibbon from '@/Components/Users/PropertyStatusRibbon.vue';
import {formatNumber} from '@/Composables/useFormatters.js';
import {Link} from '@inertiajs/inertia-vue3'

const props=defineProps({
    properties: {
        type: Object,
    },
    gridproperty: {
        type: Object,
    }
})

onMounted(() => {
    feather.replace()
})

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
    {
        id: 7,
        image: ('../assets/images/property/7.jpg'),
        title: '2141 Fiero Street, Baton Rouge, LA 70808',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 8,
        image: ('../assets/images/property/8.jpg'),
        title: '9714 Inniswold Estates Ave, Baton Rouge, LA 70809',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 9,
        image: ('../assets/images/property/9.jpg'),
        title: '1433 Beckenham Dr, Baton Rouge, LA 70808, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 10,
        image: ('../assets/images/property/10.jpg'),
        title: '1574 Sharlo Ave, Baton Rouge, LA 70820, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 11,
        image: ('../assets/images/property/11.jpg'),
        title: '2528 BOCAGE LAKE DR, Baton Rouge, LA 70809, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
    {
        id: 12,
        image: ('../assets/images/property/12.jpg'),
        title: '1533 NICHOLSON DR, Baton Rouge, LA 70802, USA',
        sqf: '8000sqf',
        beds: '4 Beds',
        baths: '4 Baths',
        price: '$5000',
        rating: ['mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star', 'mdi mdi-star']
    },
])

</script>

<style lang="scss" scoped></style>
