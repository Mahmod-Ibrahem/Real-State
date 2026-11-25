<template>
    <div class="space-y-3">
        <div v-for="(plan, index) in plans" :key="index" class="rounded-lg border border-slate-200 p-4">
            <div class="flex items-start gap-3">
                <div class="grid flex-1 gap-4 sm:grid-cols-3">
                    <TextField :model-value="plan.down_payment_percent" label="Down payment (%)" type="number" min="0" max="90"
                               required :error="errorFor(index, 'down_payment_percent')"
                               @update:model-value="update(index, 'down_payment_percent', $event)"/>
                    <TextField :model-value="plan.installment_years" label="Years" type="number" min="1" max="15" required
                               :error="errorFor(index, 'installment_years')"
                               @update:model-value="update(index, 'installment_years', $event)"/>
                    <SelectField :model-value="plan.installment_frequency" label="Paid" :options="frequencies" required
                                 :error="errorFor(index, 'installment_frequency')"
                                 @update:model-value="update(index, 'installment_frequency', $event)"/>
                </div>
                <button type="button" :aria-label="`Remove plan ${index + 1}`" title="Remove plan" @click="remove(index)"
                        class="mt-6 grid h-8 w-8 shrink-0 place-content-center rounded-md text-slate-400 transition-colors hover:bg-red-50 hover:text-red-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500/30">
                    <TrashIcon class="h-4 w-4" aria-hidden="true"/>
                </button>
            </div>
            <p class="mt-3 text-xs text-slate-500">
                {{ describePlan(price, plan) || 'Enter the price, down payment and years to see the installments.' }}
            </p>
        </div>

        <p v-if="!plans.length" class="text-sm text-slate-500">Cash only. Add a plan if buyers can pay in installments.</p>
        <p v-if="errors.payment_plans" class="text-xs text-red-600">{{ errors.payment_plans }}</p>

        <Button v-if="plans.length < MAX_PAYMENT_PLANS" variant="ghost" size="sm" :icon="PlusIcon" @click="add">
            Add plan
        </Button>
    </div>
</template>

<script setup>
import {PlusIcon} from '@heroicons/vue/20/solid';
import {TrashIcon} from '@heroicons/vue/24/outline';
import Button from '@/Components/Admin/ui/Button.vue';
import SelectField from '@/Components/Admin/ui/SelectField.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import {MAX_PAYMENT_PLANS, describePlan, emptyPlan} from '@/Composables/paymentPlans.js';

const props = defineProps({
    // The property price, for the preview line
    price: {type: [String, Number], default: ''},
    // InstallmentFrequency options [{id, name}]
    frequencies: {type: Array, required: true},
    // The whole form's errors; rows read 'payment_plans.N.field'
    errors: {type: Object, default: () => ({})},
});
const plans = defineModel({type: Array, default: () => []});

const errorFor = (index, field) => props.errors[`payment_plans.${index}.${field}`] ?? '';

const add = () => (plans.value = [...plans.value, emptyPlan()]);
const remove = (index) => (plans.value = plans.value.filter((_, position) => position !== index));
const update = (index, field, value) =>
    (plans.value = plans.value.map((plan, position) => (position === index ? {...plan, [field]: value} : plan)));
</script>
