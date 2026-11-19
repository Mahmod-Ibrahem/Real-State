<template>
    <button type="button"
            class="flex flex-col items-center justify-center gap-[6px] w-10 h-10 focus:outline-none"
            :aria-expanded="open"
            aria-controls="mobile-menu"
            :aria-label="open ? 'Close menu' : 'Open menu'"
            @click="$emit('toggle')">
        <span class="block h-0.5 w-6 bg-gray-900 transition-transform duration-300"
              :class="{'translate-y-2 rotate-45': open}"/>
        <span class="block h-0.5 w-6 bg-gray-900 transition-opacity duration-300"
              :class="{'opacity-0': open}"/>
        <span class="block h-0.5 w-6 bg-gray-900 transition-transform duration-300"
              :class="{'-translate-y-2 -rotate-45': open}"/>
    </button>
</template>

<script setup>
defineProps({
    open: {type: Boolean, default: false},
});
defineEmits(['toggle']);
</script>
