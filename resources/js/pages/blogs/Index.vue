<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import ArticleCard from '@/components/blog/ArticleCard.vue';
import BlogLayout from '@/layouts/BlogLayout.vue';
import type { PaginatedPosts } from '@/types/laravel';

defineProps<{
  posts: PaginatedPosts
}>()
</script>

<template>
  <Head title="Wacana Blog" />
  <BlogLayout>
    <div class="grid space-y-5">
      <ArticleCard v-for="post in posts.data" v-bind:key="post.id" :article="post" />
    </div>
  </BlogLayout>
</template>