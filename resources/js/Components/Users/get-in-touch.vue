<template>
    <div class="container mt-16">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="section-title text-center">
                    <h4 class="title mb-3">{{ header }}</h4>
                    <div class="mt-4 pt-2">
                        <Link :href="route('contact.index')"
                              class="btn btn-pills btn-primary d-inline-flex align-items-center">
                            <span class="h5 mb-0 me-1">
                                <i data-feather="mail" class="fea icon-sm"></i>
                            </span>
                            Contact us
                        </Link>
                    </div>
                </div>
            </div><!--end col-->
        </div><!--end row-->
    </div><!--end container-->
</template>

<script setup>
import {Link} from '@inertiajs/vue3';

defineProps({
    header: {
        type: String,
        default: () => {
            return 'Have Question ? Get in touch!'
        }
    }
})
</script>

<style lang="scss" scoped></style>
