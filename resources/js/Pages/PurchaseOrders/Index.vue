<template>
    <AppLayout title="Purchase Orders">
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Purchase Orders
          </h2>
          <button
            @click="openCreateModal"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            Add Purchase Order
          </button>
        </div>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <div v-if="purchaseOrders.length">
              <table class="min-w-full">
                <thead>
                  <tr class="bg-gray-50">
                    <!-- Adjust columns as needed -->
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                      PO Number
                    </th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                      Order Number
                    </th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                      Type
                    </th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                      Status
                    </th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                      Supplier ID
                    </th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                      Distributor ID
                    </th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="po in purchaseOrders"
                    :key="po.id"
                    class="border-b"
                  >
                    <td class="px-6 py-4 whitespace-no-wrap">{{ po.po_number }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ po.order_number }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ po.type }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ po.status }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ po.supplier_id || '—' }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ po.distributor_id || '—' }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                      <button
                        @click="editPurchaseOrder(po)"
                        class="text-indigo-600 hover:text-indigo-900 mr-4"
                      >
                        Edit
                      </button>
                      <button
                        @click="deletePurchaseOrder(po.id)"
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="mt-4">
              No purchase orders found.
            </div>
          </div>
        </div>
      </div>

      <!-- Purchase Order Modal (Overlay) -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50"
      >
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
          <h3 class="text-lg font-medium mb-4">
            {{ isEditing ? 'Edit Purchase Order' : 'Add Purchase Order' }}
          </h3>

          <form @submit.prevent="submitForm">
            <div class="space-y-4">

              <!-- PO Number -->
              <div>
                <InputLabel for="po_number" value="PO Number" />
                <TextInput
                  id="po_number"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.po_number"
                  required
                />
                <InputError :message="form.errors.po_number" class="mt-2" />
              </div>

              <!-- Order Number -->
              <div>
                <InputLabel for="order_number" value="Order Number" />
                <TextInput
                  id="order_number"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.order_number"
                  required
                />
                <InputError :message="form.errors.order_number" class="mt-2" />
              </div>

              <!-- Type (POD or POS) -->
              <div>
                <InputLabel for="type" value="Type" />
                <select
                  id="type"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  v-model="form.type"
                  required
                >
                  <option value="POD">POD (From Distributor)</option>
                  <option value="POS">POS (To Supplier)</option>
                </select>
                <InputError :message="form.errors.type" class="mt-2" />
              </div>

              <!-- Status -->
              <div>
                <InputLabel for="status" value="Status" />
                <select
                  id="status"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  v-model="form.status"
                  required
                >
                  <option value="New">New</option>
                  <option value="Accepted">Accepted</option>
                  <option value="In Delivery">In Delivery</option>
                  <option value="Delivered">Delivered</option>
                  <option value="Rejected">Rejected</option>
                  <option value="Cancelled">Cancelled</option>
                </select>
                <InputError :message="form.errors.status" class="mt-2" />
              </div>

              <!-- Supplier ID -->
              <div>
                <InputLabel for="supplier_id" value="Supplier ID" />
                <TextInput
                  id="supplier_id"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="form.supplier_id"
                />
                <InputError :message="form.errors.supplier_id" class="mt-2" />
              </div>

              <!-- Distributor ID -->
              <div>
                <InputLabel for="distributor_id" value="Distributor ID" />
                <TextInput
                  id="distributor_id"
                  type="number"
                  class="mt-1 block w-full"
                  v-model="form.distributor_id"
                />
                <InputError :message="form.errors.distributor_id" class="mt-2" />
              </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end mt-6 space-x-3">
              <button
                type="button"
                class="bg-gray-300 text-gray-800 rounded py-2 px-4 hover:bg-gray-400"
                @click="closeModal"
              >
                Cancel
              </button>
              <Button
                type="submit"
                :disabled="processing"
              >
                {{ isEditing ? 'Update' : 'Create' }}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, reactive, onMounted } from 'vue'
  import axios from 'axios'

  // Layout and Reusable Components
  import AppLayout from '@/Layouts/AppLayout.vue'
  import InputLabel from '@/Components/InputLabel.vue'
  import TextInput from '@/Components/TextInput.vue'
  import InputError from '@/Components/InputError.vue'
  import Button from '@/Components/PrimaryButton.vue'

  // We store the purchase orders in an array
  const purchaseOrders = ref([])
  const showModal = ref(false)
  const isEditing = ref(false)
  const currentPurchaseOrderId = ref(null)
  const processing = ref(false)

  // Form fields (matching your purchase_orders table)
  const form = reactive({
    po_number: '',
    order_number: '',
    type: 'POD',
    status: 'New',
    supplier_id: '',
    distributor_id: '',
    errors: {}
  })

  // Utility to reset the form fields to default
  const resetForm = () => {
    form.po_number = ''
    form.order_number = ''
    form.type = 'POD'
    form.status = 'New'
    form.supplier_id = ''
    form.distributor_id = ''
    form.errors = {}
  }

  // Show modal for creating a new PO
  const openCreateModal = () => {
    resetForm()
    isEditing.value = false
    currentPurchaseOrderId.value = null
    showModal.value = true
  }

  // Show modal for editing an existing PO
  const editPurchaseOrder = (purchaseOrder) => {
    resetForm()
    isEditing.value = true
    currentPurchaseOrderId.value = purchaseOrder.id

    form.po_number      = purchaseOrder.po_number
    form.order_number   = purchaseOrder.order_number
    form.type           = purchaseOrder.type
    form.status         = purchaseOrder.status
    form.supplier_id    = purchaseOrder.supplier_id || ''
    form.distributor_id = purchaseOrder.distributor_id || ''

    showModal.value = true
  }

  // Hide modal
  const closeModal = () => {
    showModal.value = false
  }

  // Fetch existing purchase orders from /api/purchase-orders
  const fetchPurchaseOrders = async () => {
    try {
      const response = await axios.get('/api/purchase-orders')
      purchaseOrders.value = response.data
    } catch (error) {
      console.error('Failed to fetch purchase orders:', error)
    }
  }

  // Create or Update the purchase order
  const submitForm = async () => {
    processing.value = true
    form.errors = {}

    try {
      if (isEditing.value) {
        await axios.put(`/api/purchase-orders/${currentPurchaseOrderId.value}`, form)
      } else {
        await axios.post('/api/purchase-orders', form)
      }
      closeModal()
      fetchPurchaseOrders()
    } catch (error) {
      console.error('Failed to submit purchase order:', error)
      // Store validation errors if present
      form.errors = error.response?.data?.errors || {}
    } finally {
      processing.value = false
    }
  }

  // Delete a PO
  const deletePurchaseOrder = async (id) => {
    if (!confirm('Are you sure you want to delete this purchase order?')) return

    try {
      await axios.delete(`/api/purchase-orders/${id}`)
      fetchPurchaseOrders()
    } catch (error) {
      console.error('Failed to delete purchase order:', error)
    }
  }

  // On component mount, fetch the existing POs
  onMounted(fetchPurchaseOrders)
  </script>

  <style scoped>
  /* optional styling overrides */
  </style>
