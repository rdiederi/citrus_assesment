<template>
  <AppLayout title="Create Purchase Order">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Create Purchase Order
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <POForm submit-label="Create Purchase Order" @submitted="createPurchaseOrder" />
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import POForm from '@/Components/PurchaseOrder/POForm.vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';

const createPurchaseOrder = async (form) => {
  try {
    const response = await axios.post('/api/purchase-orders', {
      type: form.type,
      distributor_id: form.type === 'POD' ? form.distributor_id : null,
      supplier_id: form.type === 'POS' ? form.supplier_id : null,
      items: form.items
    });

    if (response.data) {
      router.visit(route('purchase-orders'));
    }
  } catch (error) {
    console.error('Failed to create purchase order:', error);
    form.errors = error.response?.data?.errors || {};
  }
};
</script>
