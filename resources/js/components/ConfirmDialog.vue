<script setup lang="ts">
import { Button } from '@/components/ui/button'
import { Dialog, DialogContent, DialogHeader, DialogTitle, DialogDescription, DialogFooter } from '@/components/ui/dialog'
import { useConfirmState, resolveConfirm } from '@/composables/useConfirm'

const state = useConfirmState()

function onCancel() {
  resolveConfirm(false)
}

function onConfirm() {
  resolveConfirm(true)
}
</script>

<template>
  <Dialog v-model:open="state.open">
    <DialogContent class="sm:max-w-md">
      <DialogHeader>
        <DialogTitle>{{ state.title }}</DialogTitle>
        <DialogDescription v-if="state.description">{{ state.description }}</DialogDescription>
      </DialogHeader>

      <DialogFooter>
        <Button variant="outline" @click="onCancel">{{ state.cancelText }}</Button>
        <Button :variant="state.destructive ? 'destructive' : undefined" @click="onConfirm">{{ state.confirmText
          }}</Button>
      </DialogFooter>
    </DialogContent>
  </Dialog>
</template>
