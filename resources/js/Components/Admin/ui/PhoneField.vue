<template>
    <div class="grid grid-cols-[minmax(0,10rem)_minmax(0,1fr)] items-start gap-2">
        <SelectField :model-value="country" label="Country" :options="COUNTRY_OPTIONS" searchable
                     search-placeholder="Search countries…" :required="required" :error="countryError"
                     @update:model-value="emit('update:country', $event)"/>
        <TextField :model-value="modelValue" :label="label" type="tel" inputmode="tel" autocomplete="tel-national"
                   :placeholder="placeholder" :required="required" :error="error"
                   @update:model-value="emit('update:modelValue', $event)"/>
    </div>
</template>

<script setup>
import {computed} from 'vue';
import {getCountries, getCountryCallingCode, getExampleNumber} from 'libphonenumber-js/min';
import examples from 'libphonenumber-js/mobile/examples';
import SelectField from './SelectField.vue';
import TextField from './TextField.vue';

const countryNames = new Intl.DisplayNames(['en'], {type: 'region'});

// Every country libphonenumber knows, by name, shown as "Egypt (+20)"
const COUNTRY_OPTIONS = getCountries()
    .map((iso) => ({id: iso, name: `${countryNames.of(iso) ?? iso} (+${getCountryCallingCode(iso)})`}))
    .sort((a, b) => a.name.localeCompare(b.name));

const props = defineProps({
    // The number as typed; the server turns it into E.164
    modelValue: {type: String, default: ''},
    // ISO 3166-1 alpha-2, e.g. "EG"
    country: {type: String, default: 'EG'},
    label: {type: String, default: 'Mobile number'},
    required: {type: Boolean, default: false},
    error: {type: String, default: ''},
    countryError: {type: String, default: ''},
});
const emit = defineEmits(['update:modelValue', 'update:country']);

const placeholder = computed(() => getExampleNumber(props.country, examples)?.formatNational() ?? '');
</script>
