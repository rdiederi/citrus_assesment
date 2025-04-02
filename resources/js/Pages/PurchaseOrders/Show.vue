// resources/js/Pages/PurchaseOrders/Show.vue
<template>
  <AppLayout :title="`Purchase Order ${purchaseOrder.po_number || ''}`">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Purchase Order {{ purchaseOrder.po_number || '' }}
        </h2>
        <div>
          <Link :href="route('purchase-orders')" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
            Back to List
          </Link>
          <button
            v-if="purchaseOrder.status === 'New' && purchaseOrder.type === 'POD'"
            @click="processOrder"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            Process Order
          </button>
        </div>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Basic Info Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
          <h3 class="text-lg font-medium mb-4">Purchase Order Information</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <p class="text-sm text-gray-600">PO Number</p>
              <p class="font-medium">{{ purchaseOrder.po_number || 'Loading...' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Type</p>
              <p class="font-medium">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="{ 'bg-blue-100 text-blue-800': purchaseOrder.type === 'POD', 'bg-green-100 text-green-800': purchaseOrder.type === 'POS' }">
                {{ purchaseOrder.type === 'POD' ? 'Purchase Order from Distributor' : 'Purchase Order to Supplier' }}
                </span>
              </p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Status</p>
              <p class="font-medium">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="{
                        'bg-yellow-100 text-yellow-800': purchaseOrder.status === 'New',
                        'bg-blue-100 text-blue-800': purchaseOrder.status === 'Processing',
                        'bg-green-100 text-green-800': purchaseOrder.status === 'Completed',
                        'bg-red-100 text-red-800': purchaseOrder.status === 'Rejected'
                      }">
                {{ purchaseOrder.status }}
                </span>
              </p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Created On</p>
              <p class="font-medium">{{ purchaseOrder.created_at ? new Date(purchaseOrder.created_at).toLocaleDateString() : 'Loading...' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-600">{{ purchaseOrder.type === 'POD' ? 'Distributor' : 'Supplier' }}</p>
              <p class="font-medium">{{ partnerName }}</p>
            </div>
            <div v-if="purchaseOrder.parent_po_id">
              <p class="text-sm text-gray-600">Related To</p>
              <p class="font-medium">
                <Link :href="route('purchase-orders.show', purchaseOrder.parent_po_id)" class="text-blue-600 hover:text-blue-800">
                  View Parent PO
                </Link>
              </p>
            </div>
          </div>
        </div>

        <!-- Line Items Card -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h3 class="text-lg font-medium mb-4">Line Items</h3>
          <table class="min-w-full">
            <thead>
              <tr>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Product
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Quantity
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Unit Price
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Total Price
                </th>
                <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                  Delivery Date
                </th>
              </tr>
            </thead>
            <tbody class="bg-white">
              <tr v-for="item in items" :key="item.id">
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  {{ item.product?.name || 'Loading...' }}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  {{ item.quantity }}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  {{ formatCurrency(item.unit_price) }}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  {{ formatCurrency(item.total_price) }}
                </td>
                <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                  {{ new Date(item.delivery_date).toLocaleDateString() }}
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                <td colspan="3" class="px-6 py-4 text-right font-bold">Total:</td>
                <td class="px-6 py-4 font-bold">{{ formatCurrency(totalOrderValue) }}</td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>

        <!-- Supplier Selection for POD -->
        <div v-if="showSupplierSelection" class="mt-6 bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
          <h3 class="text-lg font-medium mb-4">Process Purchase Order</h3>
          <form @submit.prevent="submitPodProcess">
            <div class="mb-6">
              <InputLabel for="supplier_id" value="Select Supplier" />
              <select
                id="supplier_id"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="supplierForm.supplier_id"
                required
              >
                <option value="">-- Select a Supplier --</option>
                <option v-for="supplier in suppliers" :key="supplier.id" :value="supplier.id">
                  {{ supplier.business_name }}
                </option>
              </select>
              <p v-if="supplierForm.error" class="mt-2 text-sm text-red-600">{{ supplierForm.error }}</p>

              <div class="mt-4">
                <InputLabel value="Action" />
                <div class="mt-2 space-x-2">
                  <label class="inline-flex items-center">
                    <input type="radio" v-model="supplierForm.action" value="accept" class="form-radio" />
                    <span class="ml-2">Accept Order</span>
                  </label>
                  <label class="inline-flex items-center">
                    <input type="radio" v-model="supplierForm.action" value="reject" class="form-radio" />
                    <span class="ml-2">Reject Order</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="flex justify-end">
              <Button type="submit" class="bg-blue-500 hover:bg-blue-700">
                Submit
              </Button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';
import InputLabel from '@/Components/InputLabel.vue';
import Button from '@/Components/PrimaryButton.vue';

const props = defineProps({
  id: {
    type: String,
    required: true
  }
});

const purchaseOrder = ref({});
const items = ref([]);
const suppliers = ref([]);
const showSupplierSelection = ref(false);

const supplierForm = ref({
  supplier_id: '',
  action: 'accept',
  error: ''
});

const partnerName = computed(() => {
  if (purchaseOrder.value.type === 'POD') {
    return purchaseOrder.value.distributor?.business_name || 'N/A';
  } else {
    return purchaseOrder.value.supplier?.business_name || 'N/A';
  }
});

const totalOrderValue = computed(() => {
  return items.value.reduce((total, item) => total + parseFloat(item.total_price || 0), 0);
});

const fetchPurchaseOrder = async () => {
  try {
    const response = await axios.get(`/api/purchase-orders/${props.id}`);
    purchaseOrder.value = response.data;
    fetchPurchaseOrderItems();
  } catch (error) {
    console.error('Failed to fetch purchase order:', error);
  }
};

const fetchPurchaseOrderItems = async () => {
  try {
    const response = await axios.get(`/api/purchase-orders/${props.id}/items`);
    items.value = response.data;
  } catch (error) {
    console.error('Failed to fetch purchase order items:', error);
  }
};

const fetchSuppliers = async () => {
  try {
    const response = await axios.get('/api/suppliers');
    suppliers.value = response.data;
  } catch (error) {
    console.error('Failed to fetch suppliers:', error);
  }
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(value || 0);
};

const processOrder = () => {
  showSupplierSelection.value = true;
};

const submitPodProcess = async () => {
  try {
    const response = await axios.post(`/api/purchase-orders/${props.id}/check-supplier`, {
      supplier_id: supplierForm.value.supplier_id,
      action: supplierForm.value.action
    });

    // Refresh the page to show updated status
    router.reload();
  } catch (error) {
    console.error('Failed to process purchase order:', error);
    supplierForm.value.error = error.response?.data?.message || 'Failed to process purchase order';
  }
};

onMounted(() => {
  fetchPurchaseOrder();
  fetchSuppliers();
});
</script>
