<template>
    <!-- Public bundle: Tailwind only, Bootstrap-collision safe (FRONTEND.md). The owner row on property cards. -->
    <Link :href="route('owners.show', owner.id)"
          class="flex min-w-0 items-center gap-2 text-sm text-slate-600 no-underline transition-colors hover:text-sky-500">
        <OwnerAvatar :src="owner.avatar" :name="owner.name"/>
        <span class="truncate">{{ owner.name }}</span>
        <CheckBadgeIcon v-if="owner.verified" class="h-4 w-4 shrink-0 text-sky-500" aria-label="Verified phone"
                        title="Verified phone"/>
    </Link>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import {CheckBadgeIcon} from '@heroicons/vue/24/solid';
import OwnerAvatar from './OwnerAvatar.vue';

defineProps({
    // Public OwnerResource: id, name, avatar, verified
    owner: {type: Object, required: true},
});
</script>
