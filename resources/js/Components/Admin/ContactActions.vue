<template>
    <a v-if="phone" :href="`tel:${phone}`" title="Call" aria-label="Call"
       :class="[BASE, 'hover:bg-slate-100 hover:text-slate-900']">
        <PhoneIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
    </a>
    <a v-if="phone" :href="`https://wa.me/${whatsappNumber}`" target="_blank" rel="noopener" title="WhatsApp" aria-label="Reply on WhatsApp"
       :class="[BASE, 'hover:bg-green-50 hover:text-green-700']">
        <ChatBubbleOvalLeftEllipsisIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
    </a>
    <a v-if="email" :href="`mailto:${email}`" title="Email" aria-label="Reply by email"
       :class="[BASE, 'hover:bg-slate-100 hover:text-slate-900']">
        <EnvelopeIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
    </a>
</template>

<script setup>
import {computed} from 'vue';
import {ChatBubbleOvalLeftEllipsisIcon, EnvelopeIcon, PhoneIcon} from '@heroicons/vue/24/outline';

const BASE = 'grid h-8 w-8 place-content-center rounded-md text-slate-500 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30';

const props = defineProps({
    phone: {type: String, default: ''},
    email: {type: String, default: ''},
});

// Property leads store E.164 (+201…); contact leads still hold local Egyptian numbers (01…)
const whatsappNumber = computed(() => (props.phone.startsWith('+') ? props.phone.slice(1) : `2${props.phone}`));
</script>
