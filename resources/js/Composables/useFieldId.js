let counter = 0;

/** A unique id linking a field's <label> to its control. */
export const useFieldId = (prefix = 'field') => `${prefix}-${++counter}`;

/** Shared control classes for admin text inputs and selects (ADMIN_UI.md §4). */
export const controlClasses = (hasError) => [
    'block w-full rounded-lg border bg-white px-3 text-sm text-slate-900 placeholder:text-slate-400 transition-colors',
    'focus:outline-none focus:ring-2 disabled:cursor-not-allowed disabled:bg-slate-50 disabled:text-slate-400',
    hasError
        ? 'border-red-400 focus:border-red-500 focus:ring-red-500/15'
        : 'border-slate-300 hover:border-slate-400 focus:border-indigo-500 focus:ring-indigo-500/15',
];
