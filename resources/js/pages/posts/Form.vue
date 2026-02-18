<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import StarterKit from '@tiptap/starter-kit';
import { useEditor } from '@tiptap/vue-3';
import { ImagePlus, Upload, Trash2, Save } from 'lucide-vue-next';
import { ref } from 'vue';
import PostController from '@/actions/App/Http/Controllers/PostController';
import Heading from '@/components/Heading.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { Textarea } from '@/components/ui/textarea';
import { TiptapContent, TiptapProvider, TiptapStatusBar, TiptapToolbar } from '@/components/ui/tiptap';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const editor = useEditor({
  extensions: [StarterKit],
  content: '<p>Hello Mom!</p>',
});

// Form handler
const form = useForm({
  title: '',
  abstract: '',
  content: {} as Record<string, any>,
  tags: '',
  image: null as File | null
});

function handleSubmit() {
  form.image = imageFile.value;
  form.content = editor.value?.getJSON() ?? {};
  form.post(PostController.store().url);
}

// Image upload handling
const fileInput = ref<HTMLInputElement | null>(null);
const imageFile = ref<File | null>(null);
const previewUrl = ref<string | null>(null);

function triggerFileInput() {
  fileInput.value?.click();
}

function onFileChange(e: Event) {
  const target = e.target as HTMLInputElement;
  const file = target.files?.[0] ?? null;
  if (file) {
    if (previewUrl.value) {
      URL.revokeObjectURL(previewUrl.value);
    }
    previewUrl.value = URL.createObjectURL(file);
    imageFile.value = file;
  }
}

function removeImage() {
  if (previewUrl.value) {
    URL.revokeObjectURL(previewUrl.value);
  }
  imageFile.value = null;
  previewUrl.value = null;
  if (fileInput.value) {
    fileInput.value.value = '';
  }
}

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Posts',
    href: PostController.index().url,
  },
  {
    title: 'Create',
  },
];
</script>

<template>

  <Head title="Create post" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-8">
      <Heading title="Create post" description="Create a new post" />
      <div class="grid space-y-3 mb-4">
        <Label for="title">Judul</Label>
        <Input type="text" id="title" name="title" v-model="form.title" />
      </div>
      <div class="grid space-y-3 mb-4">
        <Label for="image">Gambar Sampul</Label>
        <div class="p-4 border-2 rounded-lg min-h-50 flex items-center justify-center">
          <div class="w-full flex items-center justify-center">
            <template v-if="previewUrl">
              <div class="relative w-full max-w-md">
                <img :src="previewUrl" alt="Preview" class="w-full h-auto max-h-[60vh] object-contain rounded-md" />
                <div class="absolute top-2 right-2 flex gap-2">
                  <Button size="sm" variant="destructive" @click="removeImage">
                    <Trash2 class="size-4" />
                  </Button>
                </div>
              </div>
            </template>
            <template v-else>
              <div class="grid justify-center items-center py-6">
                <div class="mb-3 text-center">
                  <ImagePlus class="size-9 mx-auto" />
                  <span class="text-sm">Tidak ada gambar</span>
                </div>
                <div class="flex gap-2 justify-center">
                  <Button variant="outline" @click="triggerFileInput">
                    <Upload/>
                    Unggah
                  </Button>
                </div>
              </div>
            </template>
          </div>
        </div>
        <input ref="fileInput" @change="onFileChange" accept="image/*" type="file" id="image" name="image" class="hidden" />
      </div>
      <div class="grid space-y-3 mb-4">
        <Label for="abstract">Abstrak</Label>
        <Textarea id="abstract" name="abstract" v-model="form.abstract" />
      </div>
      <div class="grid space-y-3 mb-4">
        <Label for="content">Konten</Label>
        <div id="content" class="border-2 p-3 rounded-lg bg-input/30">
          <TiptapProvider :editor="editor">
            <TiptapToolbar />
            <TiptapContent />
            <TiptapStatusBar show-word-count />
          </TiptapProvider>
        </div>
      </div>
      <div class="grid space-y-3 mb-4">
        <Label for="title">Tags</Label>
        <Input type="text" id="title" name="title" v-model="form.tags" />
      </div>
      <div class="text-end">
        <Button type="submit" @click="handleSubmit">
          <Spinner v-if="form.processing"/>
          <Save v-else />
          Simpan
        </Button>
      </div>
    </div>
  </AppLayout>
</template>