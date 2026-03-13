<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import AppLogoIcon from '@/components/AppLogoIcon.vue';
import { Button } from '@/components/ui/button';

const isScrolled = ref(false);
const year = ref(new Date().getFullYear());

const onScroll = () => {
  isScrolled.value = window.scrollY > 8;
};

onMounted(() => {
  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll();
});

onUnmounted(() => {
  window.removeEventListener('scroll', onScroll);
});

</script>

<template>
  <div class="min-h-screen flex flex-col">
    <header class="w-screen flex justify-center border-b sticky top-0 z-50 transition-shadow duration-200"
      :class="{ 'shadow-md backdrop-blur-sm': isScrolled }">
      <div class="w-[90%] md:w-[70%] py-4 flex items-center justify-between">
        <AppLogoIcon class="size-12" />
        <Button size="sm">Bergabung!</Button>
      </div>
    </header>

    <main class="flex-1">
      <div class="w-[90%] md:w-[70%] mx-auto mt-5">
        <slot />
      </div>
    </main>

    <footer class="mt-auto border-t w-screen">
      <div class="py-3 w-[90%] md:w-[70%] mx-auto">
        <p class="text-sm text-center text-muted-foreground">© {{ year }} Wacana Belaka <br> All rights reserved</p>
      </div>
    </footer>
  </div>
</template>