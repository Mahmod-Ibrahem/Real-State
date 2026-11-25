/** Display helpers shared by admin tables. */

export const formatNumber = (value) => Number(value ?? 0).toLocaleString('en-US');

/** "19 Nov 2026" */
export const formatDate = (value) =>
    value ? new Date(value).toLocaleDateString('en-GB', {day: 'numeric', month: 'short', year: 'numeric'}) : '—';

const RELATIVE_UNITS = [['year', 31536000], ['month', 2592000], ['week', 604800], ['day', 86400], ['hour', 3600], ['minute', 60]];
const relativeFormat = new Intl.RelativeTimeFormat('en', {numeric: 'auto', style: 'short'});

/** "2 hr. ago", "yesterday", "just now" */
export function formatRelativeTime(value) {
    if (!value) return '—';
    const seconds = (new Date(value).getTime() - Date.now()) / 1000;
    const [unit, size] = RELATIVE_UNITS.find(([, size]) => Math.abs(seconds) >= size) ?? [];

    return unit ? relativeFormat.format(Math.round(seconds / size), unit) : 'just now';
}
