<template>
    <!-- Public bundle: Tailwind only, Bootstrap-collision safe (FRONTEND.md) -->
    <Head>
        <title>{{ `${owner.name} – Properties on Qeima` }}</title>
        <meta name="description" :content="`Browse ${countLabel} listed by ${owner.name} on Qeima.`">
    </Head>
    <navbar :navLight="'nav-left'"/>

    <main class="mx-auto w-full max-w-6xl px-[16px] pb-16 pt-[120px]">
        <header class="flex flex-col items-start gap-[16px] rounded-xl border-[1px] border-slate-200 bg-white p-6 shadow-[0_1px_3px_rgba(15,23,42,0.08)] sm:flex-row sm:items-center">
            <OwnerAvatar :src="owner.avatar" :name="owner.name" size="xl"/>
            <div class="min-w-0">
                <h1 class="m-0 flex items-center gap-2 text-2xl font-semibold text-slate-900">
                    <span class="truncate">{{ owner.name }}</span>
                    <CheckBadgeIcon v-if="owner.verified" class="h-6 w-6 shrink-0 text-sky-500" aria-label="Verified phone"
                                    title="Verified phone"/>
                </h1>
                <span class="mt-1 block text-sm text-slate-500">
                    <template v-if="owner.member_since">Member since {{ owner.member_since }} · </template>{{ countLabel }} listed
                </span>
            </div>
        </header>

        <h2 class="m-0 mt-[40px] text-lg font-semibold text-slate-900">Properties by {{ owner.name }}</h2>
        <div v-if="properties.data.length" class="mt-[16px]">
            <featured :gridproperty="properties.data"/>
            <pagination :meta="properties.meta"/>
        </div>
        <p v-else class="m-0 mt-[16px] rounded-xl border-[1px] border-dashed border-slate-300 px-6 py-10 text-center text-sm text-slate-500">
            {{ owner.name }} has no properties listed right now.
        </p>
    </main>

    <footers/>
    <backtotop/>
</template>

<script setup>
import {computed} from 'vue';
import {Head} from '@inertiajs/vue3';
import {CheckBadgeIcon} from '@heroicons/vue/24/solid';
import navbar from '@/Components/Users/navbar/navbar.vue';
import featured from '@/Components/Users/featured-properties-two.vue';
import pagination from '@/Components/Users/pagination.vue';
import footers from '@/Components/Users/footer/footer.vue';
import backtotop from '@/Components/Users/back-to-top.vue';
import OwnerAvatar from '@/Components/Users/OwnerAvatar.vue';

const props = defineProps({
    // Public OwnerResource with listed_properties_count
    owner: {type: Object, required: true},
    // Public PropertyResource collection (paginated)
    properties: {type: Object, required: true},
});

const countLabel = computed(() =>
    `${props.owner.listed_properties_count} ${props.owner.listed_properties_count === 1 ? 'property' : 'properties'}`
);
</script>
