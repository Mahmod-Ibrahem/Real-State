import {computed, ref} from 'vue';

/**
 * Filter an unpaginated list by a search term (case-insensitive, any of `keys`).
 *
 *   const {search, filtered} = useClientSearch(() => props.propertyTypes.data, ['name']);
 *   <TableCard client-search v-model:search="search"> … v-for="type in filtered"
 */
export function useClientSearch(rows, keys) {
    const search = ref('');

    const filtered = computed(() => {
        const term = search.value.trim().toLowerCase();
        if (!term) return rows();
        return rows().filter((row) => keys.some((key) => String(row[key] ?? '').toLowerCase().includes(term)));
    });

    return {search, filtered};
}
