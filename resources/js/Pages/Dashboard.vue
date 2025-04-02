<template>
    <AppLayout title="Dashboard">
      <template #header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Dashboard
        </h2>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <!-- Main Content Card -->
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-medium">
              Welcome to your Dashboard
            </h3>
            <p class="mt-2 text-sm text-gray-600">
              Below are some quick stats and actions.
            </p>

            <!-- Stats Section -->
            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
              <div class="bg-gray-100 p-4 rounded shadow flex flex-col">
                <h4 class="font-semibold mb-2">Suppliers</h4>
                <p class="text-2xl font-bold">{{ stats.suppliers }}</p>
                <Link href="/suppliers" class="mt-auto inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                  View
                </Link>
              </div>

              <div class="bg-gray-100 p-4 rounded shadow flex flex-col">
                <h4 class="font-semibold mb-2">Distributors</h4>
                <p class="text-2xl font-bold">{{ stats.distributors }}</p>
                <Link href="/distributors" class="mt-auto inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                  View
                </Link>
              </div>

              <div class="bg-gray-100 p-4 rounded shadow flex flex-col">
                <h4 class="font-semibold mb-2">Products</h4>
                <p class="text-2xl font-bold">{{ stats.products }}</p>
                <Link href="/products" class="mt-auto inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                  View
                </Link>
              </div>

              <div class="bg-gray-100 p-4 rounded shadow flex flex-col">
                <h4 class="font-semibold mb-2">Purchase Orders</h4>
                <p class="text-2xl font-bold">{{ stats.purchaseOrders }}</p>
                <Link href="/purchase-orders" class="mt-auto inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center">
                  View
                </Link>
              </div>
            </div>

            <!-- CTA Section -->
            <div class="mt-8">
              <h4 class="text-md font-semibold">Actions</h4>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <Link href="/suppliers/create" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded block text-center">
                  Create Supplier
                </Link>
                <Link href="/products" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded block text-center">
                  Create or Manage Products
                </Link>
                <Link href="/purchase-orders/create" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded block text-center">
                  Create Purchase Order
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import AppLayout from '@/Layouts/AppLayout.vue';
  import { Link } from '@inertiajs/vue3';

  // We receive the stats prop from the controller
  const props = defineProps({
    stats: {
      type: Object,
      default: () => ({
        suppliers: 0,
        distributors: 0,
        products: 0,
        purchaseOrders: 0,
      }),
    },
  });
  </script>
