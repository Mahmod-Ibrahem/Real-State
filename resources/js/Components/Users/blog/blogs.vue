<template>
    <div v-if="allblogs" class="row g-4">
        <div v-for="item in datas" :key="item" class="col-lg-4 col-md-6">
            <div class="card blog blog-primary shadow rounded-3 overflow-hidden border-0">
                <div class="card-img blog-image position-relative overflow-hidden rounded-0">
                    <div class="position-relative overflow-hidden">
                        <img :src="item.image" class="img-fluid" alt="">
                        <div class="card-overlay"></div>
                    </div>

                    <div class="blog-tag p-3">
                        <a href="" class="badge bg-primary">{{ item.subname }}</a>
                    </div>
                </div>

                <div class="card-body content p-0">
                    <div class="p-4">
                        <router-link :to="{ name: 'blog-detail', params: { id: item.id } }"
                            class="title fw-medium fs-5 text-dark">{{ item.name }}</router-link>
                        <p class="text-muted mt-2">{{ item.title }}</p>

                        <a href="" class="text-dark read-more">Read More <i
                                class="mdi mdi-chevron-right align-middle"></i></a>
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div v-else-if="sidebarblog" class="row g-4">
        <div v-for="item in datas.slice(0, 8)" :key="item" class="col-lg-6">
            <div class="card blog blog-primary shadow rounded-3 overflow-hidden border-0">
                <div class="card-img blog-image position-relative overflow-hidden rounded-0">
                    <div class="position-relative overflow-hidden">
                        <img :src="item.image" class="img-fluid" alt="">
                        <div class="card-overlay"></div>
                    </div>

                    <div class="blog-tag p-3">
                        <a href="" class="badge bg-primary">{{ item.subname }}</a>
                    </div>
                </div>

                <div class="card-body content p-0">
                    <div class="p-4">
                        <router-link :to="{ name: 'blog-detail', params: { id: item.id } }"
                            class="title fw-medium fs-5 text-dark">{{ item.name }}</router-link>
                        <p class="text-muted mt-2">{{ item.title }}</p>

                        <a href="" class="text-dark read-more">Read More <i
                                class="mdi mdi-chevron-right align-middle"></i></a>
                    </div>
                </div>
            </div>
        </div><!--end col-->
    </div><!--end row-->

    <div v-else class="container mt-100 mt-60">
        <div class="row justify-content-center">
            <div class="col">
                <div class="section-title text-center mb-4 pb-2">
                    <h4 class="title mb-3">Blogs or News</h4>

                </div>
            </div><!--end col-->
        </div><!--end row-->
        <div class="row g-4 mt-0">
            <div v-for="item in datas.slice(0, 3)" :key="item" class="col-lg-4 col-md-6">
                <div class="card blog blog-primary shadow rounded-3 overflow-hidden border-0">
                    <div class="card-img blog-image position-relative overflow-hidden rounded-0">
                        <div class="position-relative overflow-hidden">
                            <img :src="item.image" class="img-fluid" alt="">
                            <div class="card-overlay"></div>
                        </div>

                        <div class="blog-tag p-3">
                            <a href="" class="badge bg-primary">{{ item.subname }}</a>
                        </div>
                    </div>

                    <div class="card-body content p-0">
                        <div class="p-4">
                            <router-link :to="{ name: 'blog-detail', params: { id: item.id } }"
                                class="title fw-medium fs-5 text-dark">{{ item.name
                                }}</router-link>
                            <p class="text-muted mt-2">{{ item.title }}</p>

                            <a href="" class="text-dark read-more">Read More <i
                                    class="mdi mdi-chevron-right align-middle"></i></a>
                        </div>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div>

</template>

<script setup>
import { ref, defineProps } from 'vue';

defineProps({
    allblogs: {
        type: Boolean,
        required: true
    },
    sidebarblog: {
        type: Boolean,
        required: true
    },
})

const datas = ref([
    {
        id: 1,
        image: require('../../assets/images/property/1.jpg'),
        subname: 'Towntor',
        name: 'Skills That You Can Learn In The Real Estate Market',
        title: "The most well-known dummy text is the 'Lorem Ipsum', in the 16th century."
    },
    {
        id: 2,
        image: require('../../assets/images/property/2.jpg'),
        subname: 'Towntor',
        name: 'Learn The Truth About Real Estate Industry',
        title: "The most well-known dummy text is the 'Lorem Ipsum', in the 16th century."
    },
    {
        id: 3,
        image: require('../../assets/images/property/3.jpg'),
        subname: 'Towntor',
        name: '10 Quick Tips About Business Development',
        title: "The most well-known dummy text is the 'Lorem Ipsum', in the 16th century."
    },
    {
        id: 4,
        image: require('../../assets/images/property/4.jpg'),
        subname: 'Towntor',
        name: '14 Common Misconceptions About Business Development',
        title: "The most well-known dummy text is the 'Lorem Ipsum', in the 16th century."
    },
    {
        id: 5,
        image: require('../../assets/images/property/5.jpg'),
        subname: 'Towntor',
        name: '10 Things Your Competitors Can Teach You About Real Estate',
        title: "The most well-known dummy text is the 'Lorem Ipsum', in the 16th century."
    },
    {
        id: 6,
        image: require('../../assets/images/property/6.jpg'),
        subname: 'Towntor',
        name: 'Why We Love Real Estate',
        title: "The most well-known dummy text is the 'Lorem Ipsum', in the 16th century."
    },
    {
        id: 7,
        image: require('../../assets/images/property/7.jpg'),
        subname: 'Towntor',
        name: '110 Quick Tips About Real Estate',
        title: "The most well-known dummy text is the 'Lorem Ipsum', in the 16th century."
    },
    {
        id: 8,
        image: require('../../assets/images/property/8.jpg'),
        subname: 'Towntor',
        name: '15 Best Blogs To Follow About Real Estate',
        title: "The most well-known dummy text is the 'Lorem Ipsum', in the 16th century."
    },
    {
        id: 9,
        image: require('../../assets/images/property/9.jpg'),
        subname: 'Towntor',
        name: 'Using Banner Stands To Increase Trade Show Traffic',
        title: "The most well-known dummy text is the 'Lorem Ipsum', in the 16th century."
    },
])

</script>

<style lang="scss" scoped></style>
