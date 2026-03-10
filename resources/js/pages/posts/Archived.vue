<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { MoreVertical } from 'lucide-vue-next';
import { onUnmounted } from 'vue';
import { toast } from 'vue-sonner';
import PostController from '@/actions/App/Http/Controllers/PostController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import {
  DropdownMenu,
  DropdownMenuTrigger,
  DropdownMenuContent,
  DropdownMenuItem,
} from '@/components/ui/dropdown-menu';
import { Pagination } from '@/components/ui/pagination';
import {
  Table,
  TableHead,
  TableHeader,
  TableBody,
  TableRow,
  TableCell,
  TableEmpty,
} from '@/components/ui/table';
import { confirm } from '@/composables/useConfirm';
import AppLayout from '@/layouts/AppLayout.vue';
import type { PaginatedPosts, Post } from '@/types/laravel';
import type { BreadcrumbItem } from '@/types/navigation';
import { formatRelative } from '@/utils/datetime';

interface Props {
  posts: PaginatedPosts
};

defineProps<Props>();

async function openRestore(post: Post) {
  const confirmed = await confirm({
    title: 'Pulihkan tulisan',
    description: 'Anda yakin ingin memulihkan tulisan ini?',
    confirmText: 'Pulihkan',
    cancelText: 'Batal',
  })

  if (!confirmed) return

  router.post(PostController.restore(post.id), { method: 'post' })
}

async function openForceDelete(post: Post) {
  const confirmed = await confirm({
    title: 'Hapus permanen',
    description: 'Menghapus permanen akan menghilangkan tulisan ini tanpa bisa dikembalikan. Lanjutkan?',
    confirmText: 'Hapus Permanen',
    cancelText: 'Batal',
    destructive: true,
  })

  if (!confirmed) return

  router.delete(PostController.forceDelete(post.id), {
    onFlash: (flash) => {
      if (flash.success) {
        toast.success(flash.success);
        return;
      }

      if (flash.error) {
        toast.error(flash.error);
        return;
      }
    },
  });
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Arsip',
    href: '/posts',
  },
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

  <Head title="Archived Posts" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="flex justify-between items-start">
        <Heading title="Arsip tulisan" description="Daftar tulisan yang telah diarsipkan" />
      </div>
      <div class="mt-6">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Judul</TableHead>
              <TableHead>Penulis</TableHead>
              <TableHead>Dibuat</TableHead>
              <TableHead>Diarsipkan</TableHead>
              <TableHead class="text-center">Aksi</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="post in posts.data" :key="post.id">
              <TableCell>{{ post.title }}</TableCell>
              <TableCell>{{ post.user?.name ?? '—' }}</TableCell>
              <TableCell>{{ formatRelative(post.created_at) }}</TableCell>
              <TableCell>{{ formatRelative(post.deleted_at) }}</TableCell>
              <TableCell class="text-center">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="icon" class="h-8 w-8">
                      <MoreVertical />
                    </Button>
                  </DropdownMenuTrigger>

                  <DropdownMenuContent side="left">
                    <DropdownMenuItem @click="openRestore(post)">
                      Pulihkan
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="openForceDelete(post)" variant="destructive">
                      Hapus Permanen
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </TableCell>
            </TableRow>
            <TableEmpty v-if="!posts?.data || posts.data.length === 0" :colspan="5">No archived posts found.
            </TableEmpty>
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
