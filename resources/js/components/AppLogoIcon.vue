<script setup lang="ts">
import type { HTMLAttributes } from 'vue';
import AppLogoImage from '@/assets/img/512.png';

defineOptions({
    inheritAttrs: false,
});

type Props = {
    className?: HTMLAttributes['class'];
};

defineProps<Props>();
</script>

<template>
    <img :src="AppLogoImage" alt="Logo Wacana Belaka" v-bind="$attrs" />
</template>
