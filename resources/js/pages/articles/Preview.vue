<script setup lang="ts">
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import SampleImage from '@/assets/img/hero.webp'
import ConfirmDialog from '@/components/ConfirmDialog.vue';
import Button from '@/components/ui/button/Button.vue';
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/components/ui/dialog';
import { Label } from '@/components/ui/label';
import { Select, SelectValue, SelectTrigger, SelectContent, SelectItem } from '@/components/ui/select';
import { Spinner } from '@/components/ui/spinner';
import { confirm } from '@/composables/useConfirm';
import posts from '@/routes/posts';
import type { Post, PostCategory } from '@/types/laravel';
import { formatRelative } from '@/utils/datetime';

const props = defineProps<{
  post: Post,
  categories: PostCategory[]
}>();
const isOpen = ref(false);
const form = useForm<{
  category: number | null
}>({
  category: props.post.post_category_id,
});

const handleSubmit = () => {
  form.patch(posts.togglePublish.url(props.post.id), {
    onSuccess: () => {
      isOpen.value = false;
    }
  });
}
const confirmArchive = async () => {
  const confirmed = await confirm({
    title: 'Arsipkan artikel',
    description: 'Apakah Anda yakin ingin mengarsipkan artikel ini?',
    confirmText: 'Arsipkan',
    cancelText: 'Batal',
  });

  if (!confirmed) return;

  router.delete(posts.destroy.url(props.post.id));
}
</script>

<template>
  <Head title="Preview Artikel" />
  <ConfirmDialog/>
  <header class="py-5 px-8 flex justify-between border-b-2 items-center">
    <p class="italic">Periksa dengan saksama sebelum menerbitkan artikel.</p>
    <div class="flex gap-4">
      <Button @click="isOpen = true">Terbitkan</Button>
      <Button variant="destructive" @click="confirmArchive()">Arsipkan</Button>
    </div>
  </header>
  <div class="container mx-auto py-12">
    <article class="prose prose-lg dark:prose-invert mx-auto">
      <div role="title-block" class="grid mb-12">
        <h1>{{ post.title }}</h1>
        <div class="text-sm">Ditulis oleh: {{ post.user?.name }} &bull; {{ formatRelative(post.created_at) }}</div>
      </div>
      <img :src="post.featured_image?.original_url ?? SampleImage" :alt="'Ilustrasi ' + post.title"
        class="rounded-lg mb-8 object-cover w-full mx-auto max-h-100 block" />
      <div role="excerpt-block" v-if="post.excerpt">
        <hr class="w-[40%] mx-auto mb-2">
        <p class="italic text-center mb-0">
          {{ post.excerpt }}
        </p>
        <hr class="w-[40%] mx-auto mt-2">
      </div>
      <div v-html="post.content"></div>
      <hr class="mb-3 w-[40%] mx-auto">
      <div role="tags-block" class="flex gap-2 items-start">
        <span>Tags:</span>
        <div class="flex flex-wrap gap-2 justify-center">
          <span v-for="(tag, ix) in post.tags" :key="ix" class=" px-3 py-1 rounded-lg text-sm border">
            {{ tag }}
          </span>
        </div>
      </div>
    </article>
  </div>

  <Dialog v-model:open="isOpen">
    <DialogContent>
      <DialogHeader>
        <DialogTitle>Terbitkan artikel</DialogTitle>
        <DialogDescription>Menerbitkan artikel ini.</DialogDescription>
      </DialogHeader>
      <div>
        <div class="grid space-y-3">
          <Label for="category">Kategori artikel</Label>
          <Select id="category" v-model="form.category">
            <SelectTrigger class="w-full">
              <SelectValue placeholder="Uncategorized" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem :value="null">Uncategorized</SelectItem>
              <SelectItem v-for="category in categories" :key="category.id" :value="category.id">
                {{ category.name }}
              </SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>
      <DialogFooter>
        <Button variant="outline" @click="isOpen = false">Batal</Button>
        <Button @click="handleSubmit" :disabled="form.processing">
          <Spinner v-if="form.processing" class="mr-2" />
          Terbitkan
        </Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>