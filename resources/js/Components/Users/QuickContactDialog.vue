<template>
    <TransitionRoot :show="Boolean(source)" as="template" @after-leave="reset">
        <Dialog as="div" class="relative z-[1050]" @close="emit('close')">
            <TransitionChild as="template" enter="duration-200 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                             leave="duration-150 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-slate-900/50" aria-hidden="true"></div>
            </TransitionChild>

            <div class="fixed inset-0 flex items-center justify-center p-[16px]">
                <TransitionChild as="template" enter="duration-200 ease-out" enter-from="opacity-0" enter-to="opacity-100"
                                 leave="duration-150 ease-in" leave-from="opacity-100" leave-to="opacity-0">
                    <DialogPanel class="w-full max-w-sm rounded-xl bg-white p-6 shadow-[0_20px_40px_rgba(15,23,42,0.2)]">
                        <DialogTitle as="h3" class="m-0 text-lg font-semibold text-slate-900">{{ copy.title }}</DialogTitle>

                        <template v-if="contactUrl">
                            <p class="m-0 mt-2 text-sm text-slate-500">The owner knows you're coming. Tap below to reach them.</p>
                            <a :href="contactUrl" :target="source === 'whatsapp' ? '_blank' : null" rel="noopener"
                               class="mt-6 inline-flex h-11 w-full items-center justify-center gap-2 rounded-lg text-sm font-semibold text-white no-underline transition-colors"
                               :class="source === 'whatsapp' ? 'bg-green-600 hover:bg-green-700' : 'bg-sky-400 hover:bg-sky-500'"
                               @click="emit('close')">
                                <component :is="copy.icon" class="h-5 w-5" aria-hidden="true"/> {{ copy.action }}
                            </a>
                        </template>

                        <form v-else class="mt-[16px] space-y-[12px]" @submit.prevent="submit">
                            <p class="m-0 text-sm text-slate-500">Leave your name and number so the owner knows who's calling.</p>
                            <LeadField v-model="form.full_name" placeholder="Full name" autocomplete="name" :error="form.errors.full_name"/>
                            <LeadField v-model="form.phone" type="tel" placeholder="Phone (e.g. 0100 123 4567)" autocomplete="tel"
                                       :error="form.errors.phone"/>
                            <div class="flex gap-2 pt-2">
                                <button type="button" @click="emit('close')"
                                        class="h-11 flex-1 rounded-lg border-[1px] border-slate-300 bg-white text-sm font-medium text-slate-700 hover:bg-slate-50">
                                    Cancel
                                </button>
                                <button type="submit" :disabled="form.processing"
                                        class="h-11 flex-1 rounded-lg bg-sky-400 text-sm font-semibold text-white hover:bg-sky-500 disabled:opacity-60">
                                    {{ form.processing ? 'Please wait…' : 'Continue' }}
                                </button>
                            </div>
                        </form>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import {computed, ref, watch} from 'vue';
import {useForm, usePage} from '@inertiajs/vue3';
import {Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue';
import {ChatBubbleOvalLeftEllipsisIcon, PhoneIcon} from '@heroicons/vue/24/outline';
import LeadField from './LeadField.vue';

const COPY = {
    call: {title: 'Call the owner', action: 'Call now', icon: PhoneIcon},
    whatsapp: {title: 'Chat on WhatsApp', action: 'Open WhatsApp', icon: ChatBubbleOvalLeftEllipsisIcon},
};

const props = defineProps({
    // 'call' | 'whatsapp' while open, null while closed
    source: {type: String, default: null},
    property: {type: Object, required: true},
});
const emit = defineEmits(['close']);

const page = usePage();
const copy = computed(() => COPY[props.source] ?? COPY.call);
const contactUrl = ref(null);
const form = useForm({source: '', full_name: '', phone: ''});

watch(() => props.source, (source) => source && (form.source = source));

function submit() {
    form.post(route('listing.leads.store', props.property.slug), {
        preserveScroll: true,
        onSuccess: () => (contactUrl.value = page.props.flash.contact_url),
    });
}

function reset() {
    contactUrl.value = null;
    form.reset('full_name', 'phone');
    form.clearErrors();
}
</script>
