<script setup lang="ts">
import { Head, router, useForm, Form } from '@inertiajs/vue3';
import { MoreVertical, Edit, Trash2, Plus } from 'lucide-vue-next';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import CategoryController from '@/actions/App/Http/Controllers/CategoryController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog';
import { DropdownMenu, DropdownMenuTrigger, DropdownMenuContent, DropdownMenuItem} from '@/components/ui/dropdown-menu';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Pagination } from '@/components/ui/pagination';
import { Table, TableHeader, TableRow, TableHead, TableBody, TableCell, TableEmpty } from '@/components/ui/table';
import { Textarea } from '@/components/ui/textarea';
import { confirm } from '@/composables/useConfirm';
import AppLayout from '@/layouts/AppLayout.vue';
import type { LaravelPagination } from '@/types/laravel';

interface Category {
  id: number;
  name: string;
  slug?: string | null;
  description?: string | null;
}

type Props = {
  categories: LaravelPagination<Category>;
};

defineProps<Props>();

const isOpen = ref(false);
const editing = ref<Category | null>(null);

const form = useForm<{ name: string | undefined; description: string | undefined }>({ name: '', description: '' });

function openCreate() {
  editing.value = null;
  form.reset();
  form.name = undefined;
  form.description = undefined;
  isOpen.value = true;
}

function openEdit(cat: Category) {
  editing.value = cat;
  form.reset();
  form.name = cat.name || '';
  form.description = cat.description || '';
  isOpen.value = true;
}

async function removeCategory(cat: Category) {
  const ok = await confirm({
    title: 'Hapus kategori',
    description: 'Anda yakin ingin menghapus kategori ini?',
    confirmText: 'Hapus',
    cancelText: 'Batal',
    destructive: true,
  });

  if (!ok) return;

  router.delete(CategoryController.destroy.delete(cat.id).url, {
    onFlash: (flash) => {
      if (flash.success) {
        toast.success(flash.success);
      } else if (flash.error) {
        toast.error(flash.error);
      }
    },
  });
}

const breadcrumbs = [
  { title: 'Settings', href: '/settings' },
  { title: 'Categories', href: '' },
];

function handleSubmit() {
  if (editing.value) {
    form.put(CategoryController.update(editing.value.id).url, {
      onSuccess: () => {
        form.reset();
        isOpen.value = false;
      },
      onFlash: (flash) => {
        if (flash.success) {
          toast.success(flash.success);
        } else if (flash.error) {
          toast.error(flash.error);
        }
      },
    });
  } else {
    form.post(CategoryController.store().url, {
      onSuccess: () => {
        form.reset();
        isOpen.value = false;
      },
      onFlash: (flash) => {
        if (flash.success) {
          toast.success(flash.success);
        } else if (flash.error) {
          toast.error(flash.error);
        }
      },
    });
  }
}
</script>

<template>

  <Head title="Post Categories" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-4">
      <div class="flex justify-between items-start">
        <Heading title="Kategori" description="Kelola kategori postingan" />
        <div role="toolbar">
          <Button @click="openCreate">
            <Plus />
            Tambah
          </Button>
        </div>
      </div>

      <div class="mt-6 w-full md:w-[60%] lg:w-[50%]">
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Nama</TableHead>
              <TableHead>Deskripsi</TableHead>
              <TableHead class="text-end">Aksi</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow v-for="cat in categories.data" :key="cat.id">
              <TableCell>{{ cat.name }}</TableCell>
              <TableCell>{{ cat.description || 'Tidak ada deskripsi.' }}</TableCell>
              <TableCell class="text-end">
                <DropdownMenu>
                  <DropdownMenuTrigger as-child>
                    <Button variant="ghost" size="icon" class="h-8 w-8">
                      <MoreVertical />
                    </Button>
                  </DropdownMenuTrigger>

                  <DropdownMenuContent side="left">
                    <DropdownMenuItem @click="openEdit(cat)">
                      <Edit class="size-4" />
                      Edit
                    </DropdownMenuItem>
                    <DropdownMenuItem @click="removeCategory(cat)" variant="destructive">
                      <Trash2 class="size-4" />
                      Hapus
                    </DropdownMenuItem>
                  </DropdownMenuContent>
                </DropdownMenu>
              </TableCell>
            </TableRow>
            <TableEmpty v-if="categories.data.length === 0" :colspan="3">Belum ada kategori.</TableEmpty>
          </TableBody>
        </Table>
        <div class="mt-4 flex items-center justify-end">
          <Pagination :links="categories.links" :prev-url="categories.prev_page_url"
            :next-url="categories.next_page_url" />
        </div>
      </div>
    </div>

    <Dialog :open="isOpen" @update:open="isOpen = $event">
      <DialogContent class="sm:max-w-md">
        <DialogHeader>
          <DialogTitle>{{ editing ? 'Edit Kategori' : 'Buat Kategori' }}</DialogTitle>
          <DialogDescription>
            {{ editing ? 'Perbarui informasi kategori' : 'Masukkan nama kategori' }}
          </DialogDescription>
        </DialogHeader>

        <Form
          :action="editing ? CategoryController.update(editing?.id).url : CategoryController.store().url"
          :method="editing ? 'put' : 'post'" class="space-y-4">
          <div class="grid space-y-3">
            <Label for="name">Nama</Label>
            <Input id="name" v-model="form.name" required placeholder="Keuangan" />
          </div>
          <div class="grid space-y-3">
            <Label for="description">Deskripsi</Label>
            <Textarea id="description" v-model="form.description" placeholder="Tulisan tentang keuangan" />
          </div>
        </Form>

        <DialogFooter class="flex justify-end gap-2">
          <Button type="button" variant="outline" @click="isOpen = false">Batal</Button>
          <Button @click="handleSubmit" :disabled="form.processing">{{ editing ? 'Simpan' : 'Buat' }}</Button>
        </DialogFooter>
      </DialogContent>
    </Dialog>
  </AppLayout>
</template>
