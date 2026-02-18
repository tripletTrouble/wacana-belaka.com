<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Plus } from 'lucide-vue-next';
import PostController from '@/actions/App/Http/Controllers/PostController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import type { PaginatedPosts } from '@/types/laravel';

interface Props {
  posts: PaginatedPosts
};

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Posts',
    href: PostController.index().url,
  },
];
</script>

<template>
  <Head title="Posts" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="flex justify-between items-start">
        <Heading title="Post" description="List of all my post"/>
        <div role="toolbar">
          <Button as-child>
            <Link :href="PostController.create().url" class="flex items-center gap-2">
              <Plus />
              Create
            </Link>
          </Button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>