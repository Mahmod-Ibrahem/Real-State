<template>
    <!-- Public bundle: Tailwind only, Bootstrap-collision safe (FRONTEND.md) -->
    <section class="rounded-xl border-[1px] border-slate-200 bg-white p-6 shadow-[0_1px_3px_rgba(15,23,42,0.08)]"
             aria-labelledby="payment-plans-title">
        <h4 id="payment-plans-title" class="m-0 text-lg font-semibold text-slate-900">Payment plans</h4>

        <ul v-if="plans.length" class="m-0 mt-[16px] grid list-none gap-2 p-0 sm:grid-cols-2">
            <li v-for="(plan, index) in plans" :key="index" class="rounded-lg border-[1px] border-slate-200 p-[16px]">
                <span class="block text-xs font-medium uppercase tracking-wide text-slate-400">
                    {{ plan.down_payment_percent }}% down · {{ plan.installment_years }}
                    {{ plan.installment_years === 1 ? 'year' : 'years' }}
                </span>
                <span class="mt-1 block text-xl font-semibold text-slate-900">
                    {{ formatNumber(plan.installment_amount) }} EGP
                    <span class="text-sm font-normal text-slate-500">/ {{ PERIODS[plan.installment_frequency] }}</span>
                </span>
                <span class="mt-1 block text-sm text-slate-500">
                    {{ formatNumber(plan.down_payment_amount) }} EGP down payment
                    <template v-if="plan.installment_frequency !== 'monthly'">
                        · about {{ formatNumber(plan.monthly_installment) }} EGP a month
                    </template>
                </span>
            </li>
        </ul>
        <span v-else class="mt-2 block text-sm text-slate-500">Cash payment.</span>

        <span v-if="maintenanceDepositPercent !== null" class="mt-[16px] block text-sm text-slate-600">
            Maintenance deposit: <strong class="font-semibold text-slate-900">{{ maintenanceDepositPercent }}%</strong>
            ({{ formatNumber(maintenanceDepositAmount) }} EGP) at handover, not part of the installments.
        </span>
    </section>
</template>

<script setup>
import {formatNumber} from '@/Composables/useFormatters.js';

const PERIODS = {monthly: 'month', quarterly: 'quarter'};

defineProps({
    // Users\PropertyResource payment_plans
    plans: {type: Array, default: () => []},
    maintenanceDepositPercent: {type: Number, default: null},
    maintenanceDepositAmount: {type: Number, default: null},
});
</script>
