<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import HeroImage from '@/assets/img/hero.webp';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from "@/components/ui/button";
import { dashboard, login, register } from '@/routes';

withDefaults(
    defineProps<{
        canRegister: boolean;
    }>(),
    {
        canRegister: true,
    },
);
</script>

<template>
    <Head title="Welcome">

        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] text-[#1b1b18] dark:bg-[#0a0a0a] dark:text-[#FDFDFC]">
        <header class="w-full flex justify-between items-center absolute z-10 top-0 left-0 p-4 md:px-8 lg:px-16">
            <div role="app-logo" class="flex gap-2 items-center">
                <AppLogoIcon class="size-12" />
                <span class="hidden md:block font-bold text-lg">Wacana Belaka</span>
            </div>
            <nav class="flex gap-4 justify-end">
                <Button v-if="!$page.props.auth.user" as-child size="sm" class="cursor-pointer">
                    <Link :href="login()">Masuk</Link>
                </Button>
                <Button v-if="$page.props.auth.user" as-child size="sm">
                    <Link :href="dashboard()">Dashboard</Link>
                </Button>
            </nav>
        </header>
        <main class="h-screen">
            <section id="hero" class="relative h-full">
                <img :src="HeroImage" alt="Joyful people collaborating" class="mx-auto mb-8 w-screen lg:mb-12 h-full object-cover opacity-30" />
                <div class="h-full w-screen absolute top-0 flex flex-col items-center justify-center p-6 lg:p-8">
                    <h1 class="text-3xl lg:text-4xl font-bold text-center mb-5 leading-normal max-w-lg">Wahana yang mendukung produktivas dan kreativitas</h1>
                    <p class="mb-5 text-center max-w-md lg:text-lg">Nikmati berbagai sarana yang mendukung produktivas dan kreativitasmu di sini. <br/> <strong>Bergabung sekarang, gratis!</strong></p>
                    <div class="grid grid-cols-2 space-y-3 space-x-4 mt-4 max-w-md">
                        <Button v-if="!$page.props.auth.user" as-child size="lg" class="cursor-pointer">
                            <Link :href="register()">Gabung sekarang!</Link>
                        </Button>
                        <Button variant="secondary" size="lg" class="font-bold">Jelajahi Tulisan</Button>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>
