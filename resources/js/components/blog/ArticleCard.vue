<script setup lang="ts">
import {Badge} from '@/components/ui/badge';
import type { Post } from '@/types/laravel';

defineProps<{
  article: Post
}>()
</script>

<template>
  <div class="flex flex-col">
    <Badge variant="outline" class="mb-1">{{ article.category?.name ?? 'Uncategorized' }}</Badge>
    <h2 class="font-bold text-lg">{{ article.title }}</h2>
    <img :src="article.featured_image?.original_url ?? ''" alt="" v-if="article.featured_image" class="w-full h-48 object-cover rounded-md my-2">
    <p>{{ article.excerpt }}</p>
  </div>
  <hr>
</template>