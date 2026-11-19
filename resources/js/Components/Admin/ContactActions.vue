<template>
    <!-- Reply shortcuts for a lead: WhatsApp (Egyptian numbers, +20) and email -->
    <a v-if="phone" :href="`https://wa.me/2${phone}`" target="_blank" rel="noopener" title="WhatsApp" aria-label="Reply on WhatsApp"
       :class="[BASE, 'hover:bg-green-50 hover:text-green-700']">
        <ChatBubbleOvalLeftEllipsisIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
    </a>
    <a v-if="email" :href="`mailto:${email}`" title="Email" aria-label="Reply by email"
       :class="[BASE, 'hover:bg-slate-100 hover:text-slate-900']">
        <EnvelopeIcon class="h-[18px] w-[18px]" aria-hidden="true"/>
    </a>
</template>

<script setup>
import {ChatBubbleOvalLeftEllipsisIcon, EnvelopeIcon} from '@heroicons/vue/24/outline';

const BASE = 'grid h-8 w-8 place-content-center rounded-md text-slate-500 transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30';

defineProps({
    phone: {type: String, default: ''},
    email: {type: String, default: ''},
});
</script>
