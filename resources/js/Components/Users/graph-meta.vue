<template>
    <Head>
        <meta property="og:url" :content="currentUrl"/>
        <meta property="og:type" content="website"/>
        <meta property="og:title" :content="title"/>
        <meta property="og:description" :content="description"/>
        <meta property="og:image" :content="image"/>
        <meta name="twitter:card" content="summary_large_image"/>
        <meta name="twitter:url" :content="currentUrl"/>
        <meta name="twitter:title" :content="title"/>
        <meta name="twitter:description" :content="description"/>
        <meta name="twitter:image" :content="image"/>
    </Head>
</template>

<script setup>
import {currentUrl} from "@/globalVariables.js";
import {Head} from "@inertiajs/vue3";

defineProps({
    title: String,
    description: String,
    image: String
});
</script>
