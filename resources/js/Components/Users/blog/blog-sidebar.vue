<template>
    <div class="col-lg-4 col-md-6 col-12">
        <div class="card bg-white p-4 rounded-3 shadow sticky-bar">
            <!-- SEARCH -->
            <div>
                <h6 class="pt-2 pb-2 bg-light rounded-3 text-center">Search</h6>

                <div class="search-bar mt-4">
                    <div id="itemSearch2" class="menu-search mb-0">
                        <form role="search" method="get" id="searchItemform2" class="searchform">
                            <input type="text" class="form-control rounded-3 border" name="s" id="searchItem2"
                                placeholder="Search...">
                            <input type="submit" id="searchItemsubmit2" value="Search">
                        </form>
                    </div>
                </div>
            </div>
            <!-- SEARCH -->

            <!-- RECENT POST -->
            <div class="mt-4 pt-2">
                <h6 class="pt-2 pb-2 bg-light rounded-3 text-center">Recent Post</h6>
                <div class="mt-4">
                    <div v-for="item in datas" :key="item" class="blog blog-primary d-flex align-items-center"
                        :class="item.class">
                        <img :src="item.image" class="avatar avatar-small rounded-3" style="width: auto;" alt="">
                        <div class="flex-1 ms-3">
                            <a href="" class="d-block title text-dark fw-medium">{{ item.name }}</a>
                            <span class="text-muted small">{{ item.date }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- RECENT POST -->

            <!-- TAG CLOUDS -->
            <div class="mt-4 pt-2 text-center">
                <h6 class="pt-2 pb-2 bg-light rounded-3">Tags Cloud</h6>
                <ul class="tagcloud list-unstyled mt-4">
                    <li v-for="item in social" :key="item" class="list-inline-item m-1"><a href="javascript:void(0)"
                            class="rounded-3 fw-medium text-dark inline-block py-2 px-3">{{ item }}</a></li>
                </ul>
            </div>
            <!-- TAG CLOUDS -->
        </div>
    </div><!--end col-->
</template>

<script setup>
import { ref } from 'vue';

const social = ref(['Business', 'Finance', 'Marketing', 'Fashion', 'Bride', 'Lifestyle', 'Travel', 'Beauty', 'Video', 'Audio']);

const datas = ref([
    {
        image: require('../../assets/images/property/1.jpg'),
        name: 'Consultant Business',
        date: '16th Oct 2023',
    },
    {
        image: require('../../assets/images/property/2.jpg'),
        name: 'Grow Your Business',
        date: '16th Oct 2023',
        class: 'mt-3'
    },
    {
        image: require('../../assets/images/property/3.jpg'),
        name: 'Look On The Glorious Balance',
        date: '16th Oct 2023',
        class: 'mt-3'
    },
])

</script>

<style lang="scss" scoped></style>