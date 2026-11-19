<template>
    <!-- Home page FAQs, managed at /admin/faqs (CLAUDE.md, Domain rules) -->
    <div id="faqs" class="scroll-mt-[96px]">
        <div class="mb-6 text-center">
            <!-- The theme forces h2 sizes; the span carries the 30px/500 the other home section titles use -->
            <h2 class="m-0"><span class="block text-[30px] font-medium leading-tight text-slate-900">Frequently Asked Questions</span></h2>
            <span class="mt-2 block text-slate-500">Quick answers about buying, selling and renting on Qeima.</span>
        </div>

        <ul class="mx-auto my-0 max-w-3xl list-none space-y-[12px] p-0">
            <li v-for="faq in faqs" :key="faq.id" class="overflow-hidden rounded-xl border-[1px] border-slate-200 bg-white">
                <h3 class="m-0">
                    <button type="button" :id="`faq-${faq.id}-question`" :aria-expanded="isOpen(faq.id)"
                            :aria-controls="`faq-${faq.id}-answer`"
                            class="flex w-full items-center justify-between gap-[16px] px-6 py-[18px] text-start text-base font-semibold text-slate-900 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400/50"
                            @click="toggle(faq.id)">
                        <span>{{ faq.question }}</span>
                        <ChevronDownIcon class="h-5 w-5 shrink-0 text-slate-400 transition-transform duration-200"
                                         :class="{'rotate-180 text-sky-400': isOpen(faq.id)}" aria-hidden="true"/>
                    </button>
                </h3>
                <div v-show="isOpen(faq.id)" :id="`faq-${faq.id}-answer`" role="region"
                     :aria-labelledby="`faq-${faq.id}-question`"
                     class="whitespace-pre-line px-6 pb-[20px] text-[15px] leading-relaxed text-slate-600">
                    {{ faq.answer }}
                </div>
            </li>
        </ul>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {ChevronDownIcon} from '@heroicons/vue/24/outline';

const props = defineProps({
    // [{id, question, answer}] from Users/FaqResource
    faqs: {type: Array, required: true},
});

// One answer open at a time, starting with the first
const openId = ref(props.faqs[0]?.id ?? null);
const isOpen = (id) => openId.value === id;
const toggle = (id) => {
    openId.value = isOpen(id) ? null : id;
};
</script>
