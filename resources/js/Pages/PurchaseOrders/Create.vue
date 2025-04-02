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

// resources/js/Pages/PurchaseOrders/Index.vue
<template>
  <AppLayout title="Purchase Orders">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Purchase Orders
        </h2>
        <Link :href="route('purchase-orders.create')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Create Purchase Order
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div v-if="purchaseOrders.length">
            <table class="min-w-full">
              <thead>
                <tr>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    PO Number
                  </th>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Type
                  </th>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Status
                  </th>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Partner
                  </th>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Date Created
                  </th>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white">
                <tr v-for="po in purchaseOrders" :key="po.id">
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    {{ po.po_number }}
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{ 'bg-blue-100 text-blue-800': po.type === 'POD', 'bg-green-100 text-green-800': po.type === 'POS' }">
                      {{ po.type }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                          :class="{
                            'bg-yellow-100 text-yellow-800': po.status === 'New',
                            'bg-blue-100 text-blue-800': po.status === 'Processing',
                            'bg-green-100 text-green-800': po.status === 'Completed',
                            'bg-red-100 text-red-800': po.status === 'Rejected'
                          }">
                      {{ po.status }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    {{ po.supplier?.business_name || po.distributor?.business_name || 'N/A' }}
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    {{ new Date(po.created_at).toLocaleDateString() }}
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    <Link :href="route('purchase-orders.show', po.id)" class="text-indigo-600 hover:text-indigo-900 mr-4">View</Link>
                    <button @click="deletePO(po.id)" class="text-red-600 hover:text-red-900">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="p-6">
            No purchase orders found.
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const purchaseOrders = ref([]);

const fetchPurchaseOrders = async () => {
  try {
    const response = await axios.get('/api/purchase-orders');
    purchaseOrders.value = response.data;
  } catch (error) {
    console.error('Failed to fetch purchase orders:', error);
  }
};

const deletePO = async (id) => {
  if (!confirm('Are you sure you want to delete this purchase order?')) return;

  try {
    await axios.delete(`/api/purchase-orders/${id}`);
    await fetchPurchaseOrders();
  } catch (error) {
    console.error('Failed to delete purchase order:', error);
  }
};

onMounted(fetchPurchaseOrders);
</script>
