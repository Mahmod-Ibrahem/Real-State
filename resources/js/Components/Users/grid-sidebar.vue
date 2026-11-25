<template>
    <!-- Public bundle: Tailwind only, Bootstrap-collision safe (FRONTEND.md) -->
    <aside class="lg:sticky lg:top-24 lg:self-start">
        <form class="rounded-xl border-[1px] border-slate-200 bg-white p-6 shadow-[0_1px_3px_rgba(15,23,42,0.08)]"
              @submit.prevent="onSubmit">
            <div>
                <label for="filter-search" :class="LABEL">Search properties</label>
                <input id="filter-search" v-model="form.search" type="search" placeholder="Search…" :class="CONTROL">
            </div>

            <fieldset class="mt-[20px]">
                <legend :class="LABEL">Sale or rent</legend>
                <div class="grid grid-cols-3 gap-1 rounded-lg bg-slate-100 p-1">
                    <button v-for="purpose in purposeOptions" :key="purpose.id" type="button"
                            :aria-pressed="form.purpose === purpose.id" @click="form.purpose = purpose.id"
                            :class="[SEGMENT, form.purpose === purpose.id ? SEGMENT_ON : SEGMENT_OFF]">
                        {{ purpose.name }}
                    </button>
                </div>
            </fieldset>

            <div class="mt-[20px]">
                <label for="filter-country" :class="LABEL">Country</label>
                <select id="filter-country" v-model="form.country" :class="CONTROL">
                    <option value="">Any country</option>
                    <option v-for="country in countries" :key="country.id" :value="country.id">{{ country.name }}</option>
                </select>
            </div>
            <div class="mt-[20px]">
                <label for="filter-city" :class="LABEL">City</label>
                <select id="filter-city" v-model="form.city" :class="CONTROL">
                    <option value="">Any city</option>
                    <option v-for="city in visibleCities" :key="city.id" :value="city.id">{{ city.name }}</option>
                </select>
            </div>
            <div class="mt-[20px]">
                <label for="filter-area" :class="LABEL">Area</label>
                <select id="filter-area" v-model="form.area" :class="CONTROL">
                    <option value="">Any area</option>
                    <option v-for="area in visibleAreas" :key="area.id" :value="area.id">{{ area.name }}</option>
                </select>
            </div>
            <div class="mt-[20px]">
                <label for="filter-type" :class="LABEL">Property type</label>
                <select id="filter-type" v-model="form.type" :class="CONTROL">
                    <option value="">Any type</option>
                    <option v-for="type in propertyType" :key="type.id" :value="type.id">{{ type.name }}</option>
                </select>
            </div>
            <div class="mt-[20px]">
                <label for="filter-classification" :class="LABEL">Classification</label>
                <select id="filter-classification" v-model="form.classification" :class="CONTROL">
                    <option value="">Any classification</option>
                    <option v-for="classification in classifications" :key="classification.id" :value="classification.id">
                        {{ classification.name }}
                    </option>
                </select>
            </div>

            <template v-if="form.purpose !== 'rent'">
                <fieldset class="mt-[20px]">
                    <legend :class="LABEL">Payment</legend>
                    <div class="grid grid-cols-3 gap-1 rounded-lg bg-slate-100 p-1">
                        <button v-for="payment in paymentOptions" :key="payment.id" type="button"
                                :aria-pressed="form.payment === payment.id" @click="form.payment = payment.id"
                                :class="[SEGMENT, form.payment === payment.id ? SEGMENT_ON : SEGMENT_OFF]">
                            {{ payment.name }}
                        </button>
                    </div>
                </fieldset>

                <template v-if="form.payment !== 'cash'">
                    <div class="mt-[20px]">
                        <label for="filter-max-monthly" :class="LABEL">Max monthly installment</label>
                        <div class="relative">
                            <input id="filter-max-monthly" v-model="form.max_monthly" type="number" min="0" step="1000"
                                   inputmode="numeric" placeholder="e.g. 40000" :class="[CONTROL, 'pe-[52px]']">
                            <span class="pointer-events-none absolute inset-y-0 end-0 flex items-center pe-[12px] text-xs text-slate-400">EGP</span>
                        </div>
                    </div>
                    <div class="mt-[20px]">
                        <label for="filter-max-down" :class="LABEL">Down payment</label>
                        <select id="filter-max-down" v-model="form.max_down_payment_percent" :class="CONTROL">
                            <option value="">Any down payment</option>
                            <option v-for="option in DOWN_PAYMENT_OPTIONS" :key="option.id" :value="option.id">{{ option.name }}</option>
                        </select>
                        <p class="m-0 mt-1 text-xs text-slate-500">Shows properties with one plan that fits both.</p>
                    </div>
                </template>
            </template>

            <button type="submit" :disabled="form.processing"
                    class="mt-6 inline-flex h-11 w-full items-center justify-center rounded-lg bg-sky-400 text-sm font-semibold text-white transition-colors hover:bg-sky-500 disabled:opacity-60">
                Apply filters
            </button>
        </form>
    </aside>
</template>

<script setup>
import {useForm} from "@inertiajs/vue3";
import {computed, watch} from "vue";

const LABEL = 'mb-2 block text-sm font-semibold text-slate-900';
const CONTROL = 'block h-11 w-full rounded-lg border-[1px] border-slate-300 bg-white px-[12px] text-sm text-slate-900 placeholder:text-slate-400 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-400/20';
const SEGMENT = 'h-9 rounded-md text-xs font-medium transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-400/40';
const SEGMENT_ON = 'bg-white text-slate-900 shadow-[0_1px_2px_rgba(15,23,42,0.1)]';
const SEGMENT_OFF = 'text-slate-500 hover:text-slate-900';
const DOWN_PAYMENT_OPTIONS = [
    {id: 0, name: 'No down payment'},
    {id: 5, name: 'Up to 5%'},
    {id: 10, name: 'Up to 10%'},
    {id: 20, name: 'Up to 20%'},
    {id: 30, name: 'Up to 30%'},
];

const props = defineProps({
    cities: Object,
    propertyType: Object,
    purposes: Array,
    classifications: Object,
    areas: Object,
    countries: Object,
    // PaymentType options [{id, name}]
    paymentTypes: {type: Array, default: () => []},
    // Currently applied filters (from the URL), so the form shows what the results are filtered by.
    filters: {type: Object, default: () => ({})},
})

const form = useForm({
    search: props.filters.search ?? '',
    purpose: props.filters.purpose ?? '',
    type: props.filters.type ?? '',
    city: props.filters.city ?? '',
    area: props.filters.area ?? '',
    country: props.filters.country ?? '',
    classification: props.filters.classification ?? '',
    payment: props.filters.payment ?? '',
    max_monthly: props.filters.max_monthly ?? '',
    max_down_payment_percent: props.filters.max_down_payment_percent ?? '',
})

const purposeOptions = computed(() => [{id: '', name: 'All'}, ...props.purposes]);
const paymentOptions = computed(() => [{id: '', name: 'Any'}, ...props.paymentTypes]);

// Filters are optional: each list narrows only when its parent is chosen.
const visibleCities = computed(() =>
    form.country ? props.cities.filter(city => city.countryId === form.country) : props.cities
);
const visibleAreas = computed(() => {
    if (form.city) return props.areas.filter(area => area.cityId === form.city);
    const cityIds = visibleCities.value.map(city => city.id);
    return form.country ? props.areas.filter(area => cityIds.includes(area.cityId)) : props.areas;
});
watch(() => form.country, () => {
    if (form.city && !visibleCities.value.some(city => city.id === form.city)) form.city = '';
});
watch(visibleAreas, () => {
    if (form.area && !visibleAreas.value.some(area => area.id === form.area)) form.area = '';
});

// Payment plans exist only on sale listings, and a cash search has no budget to match.
watch(() => form.purpose, (purpose) => {
    if (purpose === 'rent') form.payment = '';
});
watch(() => [form.purpose, form.payment], ([purpose, payment]) => {
    if (purpose === 'rent' || payment === 'cash') {
        form.max_monthly = '';
        form.max_down_payment_percent = '';
    }
});

function onSubmit() {
    // Keep the URL clean and shareable: /listing?purpose=sale&city=3 instead of every empty field.
    form
        .transform(data => Object.fromEntries(Object.entries(data).filter(([, value]) => value !== '' && value !== null)))
        .get(route('listing.index'), {preserveState: true, preserveScroll: true});
}
</script>
