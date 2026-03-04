<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { Button } from '@/components/ui/button'
import { computed } from 'vue'

interface Link {
  url: string | null
  label: string
  active?: boolean
}

const props = defineProps<{ links: Link[]; prevUrl?: string | null; nextUrl?: string | null }>()

function visit(url?: string | null) {
  if (!url) return
  router.visit(url)
}

const numericLinks = computed(() => props.links || [])
</script>

<template>
  <nav class="inline-flex items-center space-x-2" aria-label="Pagination">
    <Button size="sm" variant="outline" :disabled="!props.prevUrl" @click.prevent="visit(props.prevUrl)">Prev</Button>

    <template v-for="link in numericLinks" :key="link.label">
      <Button
        v-if="link.url && !link.active"
        size="sm"
        variant="ghost"
        class="px-3"
        v-html="link.label"
        @click.prevent="visit(link.url)">
      </Button>

      <Button
        v-else-if="link.url && link.active"
        size="sm"
        variant="default"
        class="px-3"
        disabled
        v-html="link.label">
      </Button>

      <span v-else class="px-3 py-1 text-muted" v-html="link.label"></span>
    </template>

    <Button size="sm" variant="outline" :disabled="!props.nextUrl" @click.prevent="visit(props.nextUrl)">Next</Button>
  </nav>
</template>
