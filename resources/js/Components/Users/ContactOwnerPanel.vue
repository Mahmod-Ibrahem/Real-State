<template>
    <!-- Public bundle: Tailwind only, Bootstrap-collision safe (FRONTEND.md) -->
    <div class="rounded-xl border-[1px] border-slate-200 bg-white p-6 shadow-[0_1px_3px_rgba(15,23,42,0.08)]">
        <h5 class="m-0 text-center text-lg font-semibold text-slate-900">Contact the owner</h5>

        <div v-if="property.owner_reachable" class="mt-[16px] grid grid-cols-2 gap-2">
            <button type="button" @click="quickSource = 'call'"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg border-[1px] border-slate-300 bg-white text-sm font-medium text-slate-700 transition-colors hover:bg-slate-50">
                <PhoneIcon class="h-5 w-5" aria-hidden="true"/> Call
            </button>
            <button type="button" @click="quickSource = 'whatsapp'"
                    class="inline-flex h-11 items-center justify-center gap-2 rounded-lg bg-green-600 text-sm font-medium text-white transition-colors hover:bg-green-700">
                <ChatBubbleOvalLeftEllipsisIcon class="h-5 w-5" aria-hidden="true"/> WhatsApp
            </button>
        </div>

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

        <QuickContactDialog :source="quickSource" :property="property" @close="quickSource = null"/>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {useForm, usePage} from '@inertiajs/vue3';
import {ChatBubbleOvalLeftEllipsisIcon, PhoneIcon} from '@heroicons/vue/24/outline';
import {useToast} from 'vue-toastification';
import LeadField from './LeadField.vue';
import LeadPhoneField from './LeadPhoneField.vue';
import QuickContactDialog from './QuickContactDialog.vue';

const props = defineProps({
    // Public PropertyResource: slug, owner_reachable
    property: {type: Object, required: true},
});

const toast = useToast();
const page = usePage();
const quickSource = ref(null);

const form = useForm({source: 'form', full_name: '', email: '', phone_country: 'EG', phone: '', message: ''});

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
