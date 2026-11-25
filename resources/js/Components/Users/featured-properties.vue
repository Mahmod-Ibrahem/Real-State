<template>
    <div v-if="properties" class="container mt-16">
        <div class="row justify-content-center">
            <div class="col">
                <div class="section-title text-center mb-4 pb-2">
                    <h4 class="title mb-3">{{ header }}</h4>
                </div>
            </div><!--end col-->
        </div><!--end row-->
        <div class="row g-4 mt-0">
            <div v-for="item in properties" :key="item.id" class="col-lg-4 col-md-6 col-12">
                <div class="card property border-0 shadow position-relative overflow-hidden rounded-3">
                    <div class="property-image position-relative overflow-hidden shadow">
                        <PropertyStatusRibbon :status="item.status"/>
                        <Link :href="route('listing.show', item.slug)">
                            <img :src="item.image_url" class="img-fluid" :alt="item.name">
                        </Link>

                        <ul class="list-unstyled property-icon">
                            <li v-if="!$page.props.favouriteProperty.includes(item.id)"
                                class="mt-1">
                                <button @click="addToFavourite(item.id)"
                                        class="btn btn-sm btn-icon btn-pills btn-primary">
                                    <svg width="21px" height="21px" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                           stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z"
                                                  stroke="#ffffff" stroke-width="2" stroke-linecap="round"
                                                  stroke-linejoin="round"></path>
                                        </g>
                                    </svg>
                                </button>
                            </li>

                            <li v-else class="mt-1">
                                <button @click="removeFromFavourite(item.id)"
                                        class="btn btn-sm btn-icon btn-pills bg-white">
                                    <svg width="21px" height="21px" viewBox="0 0 24 24" fill="none"
                                         xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round"
                                           stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                  d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z"
                                                  stroke="#ed0202" stroke-width="2" stroke-linecap="round"
                                                  stroke-linejoin="round"></path>
                                        </g>
                                    </svg>

                                </button>
                            </li>
                            <!--                            <li class="mt-1"><a href="javascript:void(0)"-->
                            <!--                                                class="btn btn-sm btn-icon btn-pills btn-primary"><i-->
                            <!--                                data-feather="camera"-->
                            <!--                                class="icons"></i></a></li>-->
                        </ul>
                    </div>
                    <div class="card-body content p-4">
                        <Link :href="route('listing.show', item.slug)"
                              class="title fs-5 text-dark fw-medium">{{
                                item.name
                            }}
                        </Link>
                        <PropertyTags :property="item" class="mt-[8px]"/>

                        <ul
                            class="list-unstyled mt-3 py-3 border-top border-bottom d-flex align-items-center justify-content-between">
                            <li class="d-flex align-items-center me-3">
                                <i class="mdi mdi-arrow-expand-all fs-5 me-2 text-primary"></i>
                                <span class="text-muted">{{ item.sqft }} sqf</span>
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
                            </li>
                        </ul>
                        <div v-if="item.owner" class="mt-[12px] border-t border-slate-100 pt-[12px]">
                            <OwnerLink :owner="item.owner"/>
                        </div>
                    </div>
                </div><!--end items-->
            </div><!--end col-->
        </div><!--end row-->
    </div>
</template>

<script setup>
import {onMounted, defineProps} from 'vue';
import feather from 'feather-icons';
import OwnerLink from '@/Components/Users/OwnerLink.vue';
import PropertyTags from '@/Components/Users/PropertyTags.vue';
import PropertyStatusRibbon from '@/Components/Users/PropertyStatusRibbon.vue';
import {Link} from '@inertiajs/inertia-vue3';
import {router} from "@inertiajs/vue3";

defineProps({
    properties: {
        type: Array,
        required: true
    },
    header: {
        type: String,
        default: () => ''
    }
});

function addToFavourite(id) {
    router.post(route('favourite-listing.store'), {
            property_id: id
        },
        {
            preserveScroll: true,
        });
    feather.replace();
}

function removeFromFavourite(id) {
    router.delete(route('favourite-listing.destroy', id), {
        preserveScroll: true,
    })
    feather.replace()
}


onMounted(() => {
    feather.replace()
})

</script>

<style lang="scss" scoped></style>
