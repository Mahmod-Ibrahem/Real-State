<template>
    <!-- row start -->
    <div class="row">
        <div class="col-12 mt-4 pt-2">
            <ul class="pagination justify-content-center mb-0">
                <li
                    v-for="(link, i) in props.meta.links"
                    :key="i"
                    class="page-item"
                    @click.prevent="goToPage(link)"
                    :class="{
            active: link.active,
            disabled: !link.url
        }"
                >
                    <!-- Previous -->
                    <a v-if="link.label.toLowerCase().includes('previous')" class="page-link" href="#"
                       aria-label="Previous">
            <span aria-hidden="true">
                <i class="mdi mdi-chevron-left fs-6"></i>
            </span>
                    </a>

                    <!-- Next -->
                    <a v-else-if="link.label.toLowerCase().includes('next')" class="page-link" href="#"
                       aria-label="Next">
            <span aria-hidden="true">
                <i class="mdi mdi-chevron-right fs-6"></i>
            </span>
                    </a>

                    <!-- Page numbers -->
                    <a v-else class="page-link" href="#">
                        {{ link.label }}
                    </a>
                </li>
            </ul>

        </div><!--end col-->
    </div><!--end row-->
</template>

<script setup>
import {router} from "@inertiajs/vue3";

const props = defineProps({
    meta: Object
})
function goToPage(link) {
    if(!link.url || link.active) return
    router.visit(link.url, {
        preserveScroll: true,
        preserveState: true
    })
}
</script>

<style lang="scss" scoped></style>
