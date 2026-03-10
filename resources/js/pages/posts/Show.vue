<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import StarterKit from '@tiptap/starter-kit';
import { useEditor } from '@tiptap/vue-3';
import { onBeforeUnmount, ref, watch } from 'vue';
import PostController from '@/actions/App/Http/Controllers/PostController';
import Heading from '@/components/Heading.vue';
import AppLayout from '@/layouts/AppLayout.vue';

type Post = any;

const props = defineProps<{
  post: Post
}>();

// read-only editor for rendering TipTap JSON
const editor = useEditor({
  extensions: [StarterKit],
  content: props.post?.content || {},
  editable: false,
});

onBeforeUnmount(() => {
  if (editor.value) {
    editor.value.destroy();
  }
});

// featured image helper
const featuredUrl = ref<string | null>(null);
watch(
  () => props.post,
  (p) => {
    if (!p) return;
    let url = (p as any).featured_image_url ?? (p as any).image_url ?? null;
    if (!url && Array.isArray((p as any).media) && (p as any).media.length > 0) {
      url = (p as any).media[0].original_url || null;
    }
    featuredUrl.value = url;
  },
  { immediate: true }
);

const breadcrumbs = [
  { title: 'Posts', href: PostController.index().url },
  { title: props.post?.title ?? 'Post' },
];
</script>

<template>
  <Head :title="props.post?.title ?? 'Post'" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-8">
      <Heading :title="props.post.title" :description="props.post.excerpt || ''" />

      <template v-if="featuredUrl">
        <div class="my-6">
          <img :src="featuredUrl" alt="Featured image" class="w-full h-auto rounded-md object-contain max-h-[60vh]" />
        </div>
      </template>

      <div v-if="props.post.tags && props.post.tags.length" class="mt-6">
        <div class="flex gap-2 flex-wrap">
          <span v-for="(t, i) in props.post.tags" :key="i" class="text-sm px-2 py-1 bg-muted/30 rounded">{{ t }}</span>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
