<template>
  <Teleport to="body">
    <div class="fixed top-6 right-6 z-[9999] flex flex-col gap-3 max-w-sm w-full pointer-events-none" style="padding:0 0 32px;">
      <TransitionGroup name="toast" tag="div" class="flex flex-col gap-3">
        <div
          v-for="msg in messages"
          :key="msg.id"
          class="pointer-events-auto flex items-start gap-3 rounded-2xl px-4 py-3 shadow-lg border backdrop-blur-sm"
          :class="getToastClass(msg.type)"
        >
          <component :is="getIcon(msg.type)" class="w-5 h-5 flex-shrink-0 mt-0.5" weight="fill" />
          <p class="text-sm font-medium leading-snug">{{ msg.text }}</p>
          <button @click="remove(msg.id)" class="ml-auto flex-shrink-0 opacity-60 hover:opacity-100 transition-opacity">
            <PhX class="w-4 h-4" />
          </button>
        </div>
      </TransitionGroup>
    </div>
  </Teleport>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import {
  PhCheckCircle,
  PhWarningCircle,
  PhInfo,
  PhXCircle,
  PhX,
} from '@phosphor-icons/vue'

const page = usePage()
const messages = ref([])
let counter = 0

function getToastClass(type) {
  return {
    success: 'bg-emerald-50 border-emerald-200 text-emerald-800',
    error:   'bg-red-50 border-red-200 text-red-800',
    warning: 'bg-amber-50 border-amber-200 text-amber-800',
    info:    'bg-blue-50 border-blue-200 text-blue-800',
  }[type] || 'bg-white border-gray-200 text-gray-800'
}

function getIcon(type) {
  return {
    success: PhCheckCircle,
    error:   PhXCircle,
    warning: PhWarningCircle,
    info:    PhInfo,
  }[type] || PhInfo
}

function add(text, type = 'info') {
  const id = ++counter
  messages.value.push({ id, text, type })
  setTimeout(() => remove(id), 5000)
}

function remove(id) {
  messages.value = messages.value.filter(m => m.id !== id)
}

watch(
  () => page.props.flash,
  (flash) => {
    if (!flash) return
    if (flash.success) add(flash.success, 'success')
    if (flash.error)   add(flash.error, 'error')
    if (flash.warning) add(flash.warning, 'warning')
    if (flash.info)    add(flash.info, 'info')
  },
  { immediate: true, deep: true }
)
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active { transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1); }
.toast-enter-from   { opacity: 0; transform: translateX(100%) scale(0.95); }
.toast-leave-to     { opacity: 0; transform: translateX(100%) scale(0.95); }
</style>