<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { watchDebounced } from '@vueuse/core';
import { onUnmounted, ref } from 'vue';
import { toast } from 'vue-sonner';
import Heading from '@/components/Heading.vue';
import { Input } from '@/components/ui/input';
import { Pagination } from '@/components/ui/pagination';
import { Select, SelectTrigger, SelectValue, SelectContent, SelectItem } from '@/components/ui/select';
import {
  Table,
  TableHead,
  TableHeader,
  TableBody,
  TableRow,
  TableCell,
  TableEmpty,
} from '@/components/ui/table';
import AppLayout from '@/layouts/AppLayout.vue';
import type { PaginatedPosts } from '@/types/laravel';
import { formatRelative } from '@/utils/datetime';

interface Props {
  posts: PaginatedPosts,
  filters: { q?: string, status?: string }
}

const props = defineProps<Props>();

const q = ref(props.filters?.q ?? '');
const status = ref(props.filters?.status ?? '');

function submit() {
  router.get('/admin/posts', { q: q.value || undefined, status: status.value || undefined });
}

watchDebounced([q, status], submit, { debounce: 500 });

const breadcrumbs = [
  { title: 'Artikel', href: '/admin/posts' },
];

onUnmounted(router.on('flash', (event) => {
  if (event.detail.flash.success) {
    toast.success(event.detail.flash.success);
    return;
  }

  if (event.detail.flash.error) {
    toast.error(event.detail.flash.error);
    return;
  }
}));
</script>

<template>
  <Head title="Artikel" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="flex justify-between items-start">
        <Heading title="Semua Tulisan" description="Daftar semua tulisan di situs" />
      </div>

      <div class="mt-6 grid gap-3">
        <p class="text-sm text-muted-foreground">Filter tulisan</p>
        <div class="flex items-center gap-2">
          <Input v-model="q" placeholder="Cari berdasarkan judul..." class="w-64" />
          <Select v-model="status">
            <SelectTrigger class="w-60">
              <SelectValue placeholder="Semua" />
            </SelectTrigger>
            <SelectContent>
              <SelectItem value="all">Semua</SelectItem>
              <SelectItem value="published">Diterbitkan</SelectItem>
              <SelectItem value="unpublished">Belum Diterbitkan</SelectItem>
            </SelectContent>
          </Select>
        </div>
      </div>

      <div class="mt-6">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Judul</TableHead>
              <TableHead>Penulis</TableHead>
              <TableHead>Dibuat</TableHead>
              <TableHead>Diterbitkan</TableHead>
              <TableHead class="text-center">Aksi</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="post in posts.data" :key="post.id">
              <TableCell>{{ post.title }}</TableCell>
              <TableCell>{{ post.user?.name ?? '—' }}</TableCell>
              <TableCell>{{ formatRelative(post.created_at) }}</TableCell>
              <TableCell>{{ post.published_at ? formatRelative(post.published_at) : '—' }}</TableCell>
              <TableCell class="text-center">
                <Link :href="`/posts/${post.id}`" class="text-primary">View</Link>
              </TableCell>
            </TableRow>
            <TableEmpty v-if="!posts?.data || posts.data.length === 0" :colspan="5">Belum ada tulisan.</TableEmpty>
          </TableBody>
        </Table>
        <div class="mt-4 flex items-center justify-end">
          <Pagination :links="posts.links" :prev-url="posts.prev_page_url"
            :next-url="posts.next_page_url" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
