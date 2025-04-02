<!-- resources/js/Layouts/AppLayout.vue -->
<template>
    <!-- Inertia page title -->
    <Head :title="title" />

    <div class="min-h-screen bg-gray-100">
      <!-- Example Navigation Bar -->
      <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-16">
          <div class="flex items-center">
            <!-- Brand / Logo -->
            <div class="shrink-0 flex items-center">
              <a href="/">
                <!-- Replace with your logo if desired -->
                <span class="font-bold text-xl">Citrus</span>
              </a>
            </div>
          </div>
          <div class="flex items-center">
            <!-- Example: Some nav links (customize for your app) -->
            <a href="/dashboard" class="px-3 py-2 text-sm text-gray-800 hover:bg-gray-200 rounded">
              Dashboard
            </a>
            <a href="/suppliers" class="px-3 py-2 text-sm text-gray-800 hover:bg-gray-200 rounded">
              Suppliers
            </a>
            <a href="/products" class="px-3 py-2 text-sm text-gray-800 hover:bg-gray-200 rounded">
              Products
            </a>
            <!-- etc. -->
          </div>
        </div>
      </nav>

      <!-- Optional Header Slot (only rendered if header slot is used) -->
      <header class="bg-white shadow" v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Main Content -->
      <main>
        <slot />
      </main>
    </div>
  </template>

  <script setup>
  import { Head } from '@inertiajs/vue3'

  /**
   * Props
   * - title: string (used for the page's title in the browser tab)
   */
  const props = defineProps({
    title: {
      type: String,
      default: '',
    },
  })
  </script>

  <style scoped>
  /* Add or customize any layout-specific styling here */
  </style>
