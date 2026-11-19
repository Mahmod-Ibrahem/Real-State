<template>
    <!-- Back to top -->
    <a href="#" @click="scrollToTop" v-show="showTopButton" id="back-to-top" class="back-to-top rounded-pill fs-5 inline-flex items-center justify-center"><i
            data-feather="arrow-up" class="fea icon-sm align-middle"></i></a>
    <!-- Back to top -->
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import feather from 'feather-icons';


const showTopButton = ref(false);

onMounted(() => {
    feather.replace();
    window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
});

function scrollToTop() {
    window.scrollTo(0, 0);
}

function handleScroll() {
    if (
        document.body.scrollTop >= 400 ||
        document.documentElement.scrollTop >= 400
    ) {
        showTopButton.value = true
    } else {
        showTopButton.value = false
    }
}
</script>

<style lang="scss" scoped></style>
