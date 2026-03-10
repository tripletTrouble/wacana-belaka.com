<script setup lang="ts">
import { Head, useForm, Form } from '@inertiajs/vue3';
import StarterKit from '@tiptap/starter-kit';
import { useEditor } from '@tiptap/vue-3';
import { ImagePlus, Upload, Trash2, Save } from 'lucide-vue-next';
import { ref, watch, onBeforeUnmount } from 'vue';
import PostController from '@/actions/App/Http/Controllers/PostController';
import Heading from '@/components/Heading.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Spinner } from '@/components/ui/spinner';
import { TagsInput, TagsInputInput, TagsInputItem, TagsInputItemDelete, TagsInputItemText } from '@/components/ui/tags-input';
import { Textarea } from '@/components/ui/textarea';
import { TiptapContent, TiptapProvider, TiptapStatusBar, TiptapToolbar } from '@/components/ui/tiptap';
import AppLayout from '@/layouts/AppLayout.vue';
import type { Post } from '@/types/laravel';
import type { BreadcrumbItem } from '@/types/navigation';

const props = defineProps<{
  post?: Post
}>()
const editor = useEditor({
  extensions: [StarterKit],
  content: props.post?.content || {}
});

// Form handler
const form = useForm({
  title: props.post?.title || '',
  '_method': props.post ? 'put' : 'post',
  excerpt: props.post?.excerpt || '',
  content: props.post?.content || {} as Record<string, any>,
  tags: props.post?.tags || [] as string[],
  image: null as File | null,
  remove_featured_image: false
});

function handleSubmit() {
  form.image = imageFile.value;
  // ensure remove flag is sent when user intentionally removed existing image
  form.remove_featured_image = form.remove_featured_image || false;
  form.content = editor.value?.getJSON() ?? {};
  
  if (props.post) {
    form.post(PostController.update(props.post.id).url, {
      forceFormData: true
    });
  } else {
    form.post(PostController.store().url, {
      forceFormData: true
    });
  }
}

// Image upload handling
const fileInput = ref<HTMLInputElement | null>(null);
const imageFile = ref<File | null>(null);
const previewUrl = ref<string | null>(null);

// Initialize previewUrl reactively when `props.post` becomes available
watch(
  () => props.post,
  (post) => {
    if (!post) return;
    const p = post as any;
    // Prefer explicit featured image fields, then common media properties.
    let url: any = p.featured_image_url ?? p.image_url ?? null;
    if (!url && Array.isArray(p.media) && p.media.length > 0) {
      const m = p.media[0] as any;
      url = m.original_url;
    }

    if (url) {
      previewUrl.value = url;
    }
  },
  { immediate: true }
);

onBeforeUnmount(() => {
  if (previewUrl.value && imageFile.value) {
    // revoke only object URLs created via `URL.createObjectURL`
    try {
      URL.revokeObjectURL(previewUrl.value);
    } catch (e) {
      console.warn('Failed to revoke object URL:', e);
    }
  }
});

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
    // user selected a new image, cancel any remove flag
    form.remove_featured_image = false;
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
  // If editing an existing post, mark that the featured image should be removed
  if (props.post) {
    form.remove_featured_image = true;
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
    <Form @submit="handleSubmit" v-slot="{ processing }">
      <div class="p-8">
      <Heading title="Tulisan baru" description="Buat tulisan terbaikmu, sekarang!" />
      <div class="grid space-y-3 mb-4">
        <Label for="title">Judul</Label>
        <Input type="text" id="title" name="title" v-model="form.title" />
        <InputError :message="form.errors.title" />
      </div>
      <div class="grid space-y-3 mb-4">
        <Label for="image">Gambar Sampul</Label>
        <div class="p-4 border-2 rounded-lg min-h-50 flex items-center justify-center">
          <div class="w-full flex items-center justify-center">
            <template v-if="previewUrl">
              <div class="relative w-full max-w-md">
                <img :src="previewUrl" alt="Preview" class="w-full h-auto max-h-[60vh] object-contain rounded-md" />
                <div class="absolute top-2 right-2 flex gap-2">
                  <Button type="button" size="sm" variant="destructive" @click="removeImage">
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
                  <Button type="button" variant="outline" @click="triggerFileInput">
                    <Upload />
                    Unggah
                  </Button>
                </div>
              </div>
            </template>
          </div>
        </div>
        <input ref="fileInput" @change="onFileChange" accept="image/*" type="file" id="image" name="image"
          class="hidden" />
        <InputError :message="form.errors.image" />
      </div>
      <div class="grid space-y-3 mb-4">
        <Label for="excerpt">Ringkasan</Label>
        <Textarea id="excerpt" name="excerpt" v-model="form.excerpt" />
        <InputError :message="form.errors.excerpt" />
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
        <InputError :message="form.errors.content" />
      </div>
      <div class="grid space-y-3 mb-4">
        <Label for="tags">Tags</Label>
        <TagsInput v-model:modelValue="form.tags">
          <template #default="{ modelValue }">
            <div class="flex flex-wrap gap-2 items-center">
              <TagsInputItem v-for="(tag, idx) in modelValue" :key="idx" :value="tag">
                <TagsInputItemText>{{ tag }}</TagsInputItemText>
                <TagsInputItemDelete />
              </TagsInputItem>
              <TagsInputInput placeholder="Tambah tag" />
            </div>
          </template>
        </TagsInput>
        <InputError :message="form.errors.tags" />
      </div>
      <div class="text-end">
        <Button type="submit" :disabled="processing">
          <Spinner v-if="processing" />
          <Save v-else />
          Simpan
        </Button>
      </div>
    </div>
    </Form>
  </AppLayout>
</template>