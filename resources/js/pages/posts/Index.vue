<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { MoreVertical, Edit, Archive, Plus } from 'lucide-vue-next';
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
import type { PaginatedPosts } from '@/types/laravel';
import type { BreadcrumbItem } from '@/types/navigation';
import { formatRelative } from '@/utils/datetime';

interface Props {
  posts: PaginatedPosts
};

defineProps<Props>();

async function openDelete(post: any) {
  const confirmed = await confirm({
    title: 'Arsipkan tulisan',
    description: 'Anda yakin ingin mengarsipkan tulisan ini?',
    confirmText: 'Arsipkan',
    cancelText: 'Batal',
    destructive: true,
  })

  if (!confirmed) return

  // perform deletion via Inertia so page state / flash messages are preserved
  router.visit(PostController.destroy.delete(post.id).url, { method: 'delete' })
}



const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Artikel',
    href: PostController.index().url,
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

  <Head title="Posts" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="flex justify-between items-start">
        <Heading title="Tulisan" description="Daftar semua tulisanmu" />
        <div role="toolbar">
          <Button as-child>
            <Link :href="PostController.create().url" class="flex items-center gap-2">
              <Plus />
              Tambah
            </Link>
          </Button>
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
              <TableCell>{{ formatRelative(post.published_at) }}</TableCell>
              <TableCell class="text-center">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="icon" class="h-8 w-8">
                      <MoreVertical />
                    </Button>
                  </DropdownMenuTrigger>

                  <DropdownMenuContent side="left">
                    <DropdownMenuItem as-child>
                      <Link :href="PostController.edit(post.id).url" class="flex items-center gap-2">
                        <Edit class="size-4" />
                        Edit
                      </Link>
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="openDelete(post)" variant="destructive">
                      <Archive class="size-4" />
                      Arsipkan
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
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