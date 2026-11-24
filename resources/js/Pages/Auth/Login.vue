<template>
    <Head :title="title"/>

    <!-- Original template look kept on purpose; see ADMIN_UI.md §5 "Login is the exception" -->
    <section class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-16">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" :style="{backgroundImage: `url(${background})`}"
             aria-hidden="true"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-transparent to-black" aria-hidden="true"></div>

        <div class="relative w-full max-w-[400px] rounded-md bg-white p-6 shadow-md">
            <Link :href="route('home')" class="block">
                <img :src="logo" alt="Qeima" class="mx-auto h-16 w-auto object-contain"/>
            </Link>
            <h1 class="my-6 text-xl font-semibold text-slate-900">{{ title }}</h1>

            <p v-if="status" class="mb-4 rounded-md bg-green-50 px-3 py-2 text-sm text-green-700">{{ status }}</p>

            <form class="space-y-4" @submit.prevent="submit">
                <TextField v-model="form.email" label="Email Address" type="email" placeholder="name@example.com"
                           autocomplete="username" required autofocus :error="form.errors.email || form.errors.database"/>
                <TextField v-model="form.password" label="Password" type="password" placeholder="Password"
                           autocomplete="current-password" required :error="form.errors.password"/>

                <div class="flex items-center justify-between">
                    <Checkbox v-model="form.remember" name="remember">
                        <span class="text-sm text-slate-400">Remember me</span>
                    </Checkbox>
                    <Link v-if="canResetPassword" :href="route('password.request')"
                          class="text-sm text-slate-400 hover:text-slate-600">
                        Forgot password ?
                    </Link>
                </div>

                <Button type="submit" class="w-full" :loading="form.processing">Login / Sign in</Button>

                <p v-if="registerRoute" class="text-center text-sm">
                    <span class="me-2 text-slate-400">Don't have an account ?</span>
                    <Link :href="route(registerRoute)" class="font-bold text-slate-900">Sign Up</Link>
                </p>
            </form>
        </div>
    </section>
</template>

<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import Button from '@/Components/Admin/ui/Button.vue';
import Checkbox from '@/Components/Admin/ui/Checkbox.vue';
import TextField from '@/Components/Admin/ui/TextField.vue';
import background from '@/assets/images/bg/01.jpg';
import logo from '@/assets/images/logo.jpg';

const props = defineProps({
    title: {type: String, default: 'Login'},
    loginRoute: {type: String, default: 'login'},
    registerRoute: {type: String, default: null},
    canResetPassword: {type: Boolean, default: false},
    status: {type: String, default: ''},
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => form.post(route(props.loginRoute), {onFinish: () => form.reset('password')});
</script>
