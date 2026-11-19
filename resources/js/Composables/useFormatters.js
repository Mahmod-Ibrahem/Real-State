/** Display helpers shared by admin tables. */

export const formatNumber = (value) => Number(value ?? 0).toLocaleString('en-US');

/** "19 Nov 2026" */
export const formatDate = (value) =>
    value ? new Date(value).toLocaleDateString('en-GB', {day: 'numeric', month: 'short', year: 'numeric'}) : '—';
