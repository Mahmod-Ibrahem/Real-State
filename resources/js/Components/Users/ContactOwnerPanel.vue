<template>
    <!-- Public bundle: Tailwind only, Bootstrap-collision safe (FRONTEND.md) -->
    <div class="rounded-xl border-[1px] border-slate-200 bg-white p-6 shadow-[0_1px_3px_rgba(15,23,42,0.08)]">
        <h5 class="m-0 text-center text-lg font-semibold text-slate-900">Contact the owner</h5>

        <!-- Plain POST forms, not Inertia: the server saves the lead, then redirects to tel: / wa.me -->
        <div v-if="property.owner_reachable" class="mt-[16px] grid grid-cols-2 gap-2">
            <form v-for="channel in CHANNELS" :key="channel.source" method="post"
                  :action="route('listing.contact', property.slug)" :target="channel.target">
                <input type="hidden" name="_token" :value="page.props.csrf_token">
                <input type="hidden" name="source" :value="channel.source">
                <button type="submit" :class="[BUTTON, channel.classes]">
                    <component :is="channel.icon" class="h-5 w-5" aria-hidden="true"/> {{ channel.label }}
                </button>
            </form>
        </div>

        <p v-if="page.props.errors.database" role="alert" class="m-0 mt-2 text-center text-xs text-red-600">
            {{ page.props.errors.database }}
        </p>

        <p v-if="property.owner_reachable" class="my-[16px] flex items-center gap-2 text-xs text-slate-400">
            <span class="h-px flex-1 bg-slate-200"></span> or send a message <span class="h-px flex-1 bg-slate-200"></span>
        </p>

        <form class="mt-[16px] space-y-[12px]" @submit.prevent="send">
            <LeadField v-model="form.full_name" placeholder="Full name" autocomplete="name" :error="form.errors.full_name"/>
            <LeadField v-model="form.email" type="email" placeholder="Email" autocomplete="email" :error="form.errors.email"/>
            <LeadPhoneField v-model="form.phone" v-model:country="form.phone_country" :error="form.errors.phone"
                            :country-error="form.errors.phone_country"/>
            <LeadField v-model="form.message" type="textarea" placeholder="How can the owner help you?" :error="form.errors.message"/>
            <button type="submit" :disabled="form.processing"
                    class="inline-flex h-11 w-full items-center justify-center rounded-lg bg-sky-400 text-sm font-semibold text-white transition-colors hover:bg-sky-500 disabled:opacity-60">
                {{ form.processing ? 'Sending…' : 'Send message' }}
            </button>
        </form>
    </div>
</template>

<script setup>
import {useForm, usePage} from '@inertiajs/vue3';
import {ChatBubbleOvalLeftEllipsisIcon, PhoneIcon} from '@heroicons/vue/24/outline';
import {useToast} from 'vue-toastification';
import LeadField from './LeadField.vue';
import LeadPhoneField from './LeadPhoneField.vue';

const BUTTON = 'inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg text-sm font-medium transition-colors';
const CHANNELS = [
    {source: 'call', label: 'Call', icon: PhoneIcon, target: null,
        classes: 'border-[1px] border-slate-300 bg-white text-slate-700 hover:bg-slate-50'},
    {source: 'whatsapp', label: 'WhatsApp', icon: ChatBubbleOvalLeftEllipsisIcon, target: '_blank',
        classes: 'bg-green-600 text-white hover:bg-green-700'},
];

const props = defineProps({
    // Public PropertyResource: slug, owner_reachable
    property: {type: Object, required: true},
});

const toast = useToast();
const page = usePage();

const form = useForm({full_name: '', email: '', phone_country: 'EG', phone: '', message: ''});

function send() {
    form.post(route('listing.leads.store', props.property.slug), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('full_name', 'email', 'phone', 'message');
            toast.success(page.props.flash.message);
        },
    });
}
</script>
