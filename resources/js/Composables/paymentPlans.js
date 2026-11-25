import {formatNumber} from '@/Composables/useFormatters.js';

// Must match PropertyPaymentPlanService on the server (interest-free plans)
const PAYMENTS_PER_YEAR = {monthly: 12, quarterly: 4};
const PERIOD_LABELS = {monthly: 'month', quarterly: 'quarter'};

export const MAX_PAYMENT_PLANS = 3;

export const emptyPlan = () => ({down_payment_percent: 10, installment_years: 8, installment_frequency: 'monthly'});

/** Amounts of one plan for a price, or null while a field is missing. */
export function planAmounts(price, plan) {
    const total = Number(price);
    const years = Number(plan.installment_years);
    const perYear = PAYMENTS_PER_YEAR[plan.installment_frequency];
    if (!total || !years || !perYear || plan.down_payment_percent === '' || plan.down_payment_percent === null) return null;

    const downPayment = Math.round(total * Number(plan.down_payment_percent) / 100);
    const remaining = total - downPayment;

    return {
        downPayment,
        installment: Math.round(remaining / (years * perYear)),
        monthly: Math.round(remaining / (years * 12)),
    };
}

/** "600,000 EGP down · 168,750 EGP / quarter · 8 years" */
export function describePlan(price, plan) {
    const amounts = planAmounts(price, plan);
    if (!amounts) return '';

    const years = Number(plan.installment_years);
    return `${formatNumber(amounts.downPayment)} EGP down · ${formatNumber(amounts.installment)} EGP / `
        + `${PERIOD_LABELS[plan.installment_frequency]} · ${years} ${years === 1 ? 'year' : 'years'}`;
}
