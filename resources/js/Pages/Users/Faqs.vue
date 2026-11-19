<template>
    <Head>
        <title>FAQs | Answers to Common Questions About Buying, Selling & Renting on Qeima
        </title>
        <meta name="description"
              content="Got questions? Find answers in Qeima’s FAQ section. Learn how to list a property, contact sellers, book viewings, and more. Everything you need to know about using Egypt’s trusted real estate platform—quick and clear.
">
    </Head>
    <navbar :navLight="'nav-light nav-left'" :logoLight="false"/>
    <!-- Hero Start -->
    <section class="bg-half-170 d-table w-100"
             :style="{ 'background-image': `url(${faqImage})`, backgroundPosition: 'bottom' }">
        <div class="bg-overlay bg-gradient-overlay-2"></div>
        <div class="container">
            <div class="row mt-5 justify-content-center">
                <div class="col-12">
                    <div class="title-heading text-center">
                        <p class="text-white-50 para-desc mx-auto mb-0">Questions & Answers</p>
                        <h5 class="heading fw-semibold mb-0 sub-heading text-white title-dark">Frequently Asked
                            Questions</h5>
                    </div>
                </div><!--end col-->
            </div><!--end row-->
        </div><!--end container-->
    </section><!--end section-->
    <div class="position-relative">
        <div class="shape overflow-hidden text-white">
            <svg viewBox="0 0 2880 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 48H1437.5H2880V0H2160C1442.5 52 720 0 720 0H0V48Z" fill="currentColor"></path>
            </svg>
        </div>
    </div>
    <!-- Hero End -->

    <section class="py-[72px]">
        <div class="mx-auto max-w-3xl px-[16px]">
            <ul v-if="faqs.data.length" class="m-0 list-none space-y-[12px] p-0">
                <li v-for="faq in faqs.data" :key="faq.id"
                    class="overflow-hidden rounded-xl border-[1px] border-slate-200 bg-white">
                    <h2 class="m-0">
                        <button type="button" :id="`faq-${faq.id}-question`" :aria-expanded="isOpen(faq.id)"
                                :aria-controls="`faq-${faq.id}-answer`"
                                class="flex w-full items-center justify-between gap-[16px] px-6 py-[18px] text-start text-base font-semibold text-slate-900 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400/50"
                                @click="toggle(faq.id)">
                            <span>{{ faq.question }}</span>
                            <ChevronDownIcon class="h-5 w-5 shrink-0 text-slate-400 transition-transform duration-200"
                                             :class="{'rotate-180 text-sky-400': isOpen(faq.id)}" aria-hidden="true"/>
                        </button>
                    </h2>
                    <div v-show="isOpen(faq.id)" :id="`faq-${faq.id}-answer`" role="region"
                         :aria-labelledby="`faq-${faq.id}-question`"
                         class="whitespace-pre-line px-6 pb-[20px] text-[15px] leading-relaxed text-slate-600">
                        {{ faq.answer }}
                    </div>
                </li>
            </ul>
            <p v-else class="m-0 text-center text-slate-500">No questions have been added yet.</p>
        </div>
    </section>

    <footers/>
    <backtotop/>
</template>

<script setup>
import {ref} from 'vue';
import {Head} from '@inertiajs/vue3';
import {ChevronDownIcon} from '@heroicons/vue/24/outline';
import navbar from '@/Components/Users/navbar/navbar.vue';
import footers from '@/Components/Users/footer/footer.vue';
import backtotop from '@/Components/Users/back-to-top.vue';
import faqImage from '@/assets/images/bg/02.jpg';

const props = defineProps({
    // FaqResource collection: {data: [{id, question, answer}]}
    faqs: {type: Object, required: true},
});

// One answer open at a time, starting with the first
const openId = ref(props.faqs.data[0]?.id ?? null);
const isOpen = (id) => openId.value === id;
const toggle = (id) => {
    openId.value = isOpen(id) ? null : id;
};
</script>
