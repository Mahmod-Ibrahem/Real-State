<template>
    <!-- Public bundle: Tailwind only, Bootstrap-collision safe (FRONTEND.md) -->
    <section class="rounded-xl border-[1px] border-slate-200 bg-white p-6 shadow-[0_1px_3px_rgba(15,23,42,0.08)]"
             aria-labelledby="listed-by-title">
        <h5 id="listed-by-title" class="m-0 text-xs font-semibold uppercase tracking-wider text-slate-400">Listed by</h5>
        <div class="mt-[12px] flex items-center gap-[12px]">
            <OwnerAvatar :src="owner.avatar" :name="owner.name" size="lg"/>
            <div class="min-w-0">
                <span class="flex items-center gap-1 text-base font-semibold text-slate-900">
                    <Link :href="route('owners.show', owner.id)"
                          class="truncate text-slate-900 no-underline transition-colors hover:text-sky-500">{{ owner.name }}</Link>
                    <CheckBadgeIcon v-if="owner.verified" class="h-5 w-5 shrink-0 text-sky-500" aria-label="Verified phone"
                                    title="Verified phone"/>
                </span>
                <span class="mt-1 block text-xs text-slate-500">
                    <template v-if="owner.member_since">Member since {{ owner.member_since }}</template>
                    <template v-if="owner.member_since && owner.listed_properties_count !== undefined"> · </template>
                    <template v-if="owner.listed_properties_count !== undefined">
                        {{ owner.listed_properties_count }} {{ owner.listed_properties_count === 1 ? 'property' : 'properties' }} listed
                    </template>
                </span>
            </div>
        </div>
        <Link :href="route('owners.show', owner.id)"
              class="mt-[16px] inline-flex h-10 w-full items-center justify-center rounded-lg border-[1px] border-slate-300 text-sm font-medium text-slate-700 no-underline transition-colors hover:bg-slate-50">
            View all their properties
        </Link>
    </section>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import {CheckBadgeIcon} from '@heroicons/vue/24/solid';
import OwnerAvatar from './OwnerAvatar.vue';

defineProps({
    // Public PropertyResource `owner`: name, avatar, verified, member_since, listed_properties_count
    owner: {type: Object, required: true},
});
</script>
