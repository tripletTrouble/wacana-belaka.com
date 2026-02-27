import { reactive } from 'vue'

type ConfirmOptions = {
  title?: string
  description?: string
  confirmText?: string
  cancelText?: string
  destructive?: boolean
}

type InternalState = {
  open: boolean
  title: string
  description: string
  confirmText: string
  cancelText: string
  destructive: boolean
  resolve: ((v: boolean) => void) | null
}

const state = reactive<InternalState>({
  open: false,
  title: 'Confirm',
  description: '',
  confirmText: 'OK',
  cancelText: 'Cancel',
  destructive: false,
  resolve: null,
})

export function confirm(options: ConfirmOptions = {}): Promise<boolean> {
  return new Promise((resolve) => {
    state.title = options.title ?? 'Confirm'
    state.description = options.description ?? ''
    state.confirmText = options.confirmText ?? (options.destructive ? 'Delete' : 'OK')
    state.cancelText = options.cancelText ?? 'Cancel'
    state.destructive = !!options.destructive
    state.resolve = (v: boolean) => {
      resolve(v)
    }
    state.open = true
  })
}

export function useConfirmState() {
  return state
}

export function resolveConfirm(value: boolean) {
  if (state.resolve) {
    state.resolve(value)
    state.resolve = null
  }
  state.open = false
}

export default confirm
