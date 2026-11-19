<template>
    <Head>
        <title>{{props.property.name}}</title>
        <meta name="description" :content="props.property.description">
    </Head>
    <graph-meta :title="props.property.name" :description="props.property.description"
                :image="props.property.image_url"/>
    <navbar :navLight="'nav-left'"/>
    <section class="section mt-5 pt-4">
        <div class="container-fluid mt-2">
            <div class="row g-2">
                <div class="col-md-6">
                    <a @click="showMultiple(0)" class="lightbox" title="">
                        <img :src="props.property?.propertyImages[0]?.image" class="img-fluid rounded-3 shadow"
                             alt="">
                    </a>
                </div><!--end col-->
                <div class="col-md-6">
                    <div class="row g-2">
                        <div v-for="(image,index) in props.property.propertyImages.slice(1,5)" class="col-6">
                            <a @click="showMultiple(index)" class="lightbox" title="">
                                <img :src="image.image" class="img-fluid rounded-3 shadow" alt="">
                            </a>
                        </div>

                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
        <property :property="props.property" :features=" props.features"/>
        <div class="container mt-[60px]">
            <div class="row justify-content-center">
                <div class="col">
                    <div class="section-title text-center mb-4 pb-2">
                        <h4 class="title mb-3">Related Properties</h4>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
            <featured :relatedProperties="props.relatedProperties.data"/>
        </div>
    </section>
    <footers/>
    <backtotop/>
    <vue-easy-lightbox :visible="visibleRef" :imgs="imgsRef" :index="indexRef" @hide="onHide"></vue-easy-lightbox>
</template>

<script setup>
import {computed, ref} from 'vue'
import {Head} from '@inertiajs/vue3';
import navbar from '@/Components/Users/navbar/navbar.vue';
import VueEasyLightbox from 'vue-easy-lightbox';
import property from '@/Components/Users/property-detail.vue';
import featured from '@/Components/Users/featured-properties-slider.vue';
import backtotop from '@/Components/Users/back-to-top.vue';
import footers from '@/Components/Users/footer/footer.vue'
import 'tiny-slider/dist/tiny-slider.css';
import GraphMeta from "@/Components/Users/graph-meta.vue";

const currentUrl = window.location.href

const props = defineProps({
    property: Object,
    relatedProperties: Object,
    features: Object
})

const images = ref(props.property.propertyImages.map(image => image.image))

const visibleRef = ref(false)
const imgsRef = computed(() => props.property.propertyImages.map(image => image.image))
const indexRef = ref(0)

const showMultiple = (index) => {
    indexRef.value = index,
        imgsRef.value = images.value,
        visibleRef.value = true
}
const onHide = () => {
    visibleRef.value = false
}
</script>

<style lang="scss" scoped></style>
