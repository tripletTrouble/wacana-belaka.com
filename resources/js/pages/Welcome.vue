<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from "@/components/ui/button";
import { dashboard, login } from '@/routes';

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
        class="flex min-h-screen flex-col items-center bg-[#FDFDFC] p-6 text-[#1b1b18] lg:justify-center lg:p-8 dark:bg-[#0a0a0a] dark:text-[#FDFDFC]">
        <header class="w-full flex justify-between items-center">
            <div role="app-logo" class="flex gap-2 items-center">
                <AppLogoIcon class="size-12" />
                <span class="hidden md:block font-bold text-lg">Wacana Belaka</span>
            </div>
            <nav class="flex gap-4 justify-end">
                <Button v-if="!$page.props.auth.user" as-child size="sm" class="cursor-pointer">
                    <Link :href="login()">Gabung</Link>
                </Button>
                <Button v-if="$page.props.auth.user" as-child size="sm">
                    <Link :href="dashboard()">Dashboard</Link>
                </Button>
            </nav>
        </header>
    </div>
</template>
