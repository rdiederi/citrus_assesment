<!-- resources/js/Pages/Suppliers/Index.vue -->

<template>
    <AppLayout title="Suppliers">
      <!-- Header Slot -->
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Suppliers
          </h2>
          <button
            @click="openCreateModal"
            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
          >
            Add Supplier
          </button>
        </div>
      </template>

      <!-- Main Content -->
      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div v-if="suppliers.length">
              <table class="min-w-full">
                <thead>
                  <tr class="bg-gray-50">
                    <!-- Adjust columns as you see fit -->
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">Business Name</th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">Country</th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">VAT Number</th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">Sales Contact Name</th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">Sales Contact Email</th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">Sales Contact Phone</th>
                    <th class="px-6 py-3 border-b text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <tr
                    v-for="supplier in suppliers"
                    :key="supplier.id"
                    class="border-b"
                  >
                    <td class="px-6 py-4 whitespace-no-wrap">{{ supplier.business_name }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ supplier.country }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ supplier.vat_number }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ supplier.sales_contact_name }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ supplier.sales_contact_email }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">{{ supplier.sales_contact_phone }}</td>
                    <td class="px-6 py-4 whitespace-no-wrap">
                      <button
                        @click="editSupplier(supplier)"
                        class="text-indigo-600 hover:text-indigo-900 mr-4"
                      >
                        Edit
                      </button>
                      <button
                        @click="deleteSupplier(supplier.id)"
                        class="text-red-600 hover:text-red-900"
                      >
                        Delete
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <!-- No suppliers -->
            <div v-else class="mt-4">
              No suppliers found.
            </div>

          </div>
        </div>
      </div>

      <!-- Supplier Modal (Overlay) -->
      <div
        v-if="showModal"
        class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50"
      >
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
          <h3 class="text-lg font-medium mb-4">
            {{ isEditing ? 'Edit Supplier' : 'Add Supplier' }}
          </h3>

          <form @submit.prevent="submitForm">
            <div class="space-y-4">
              <!-- Business Name -->
              <div>
                <InputLabel for="business_name" value="Business Name" />
                <TextInput
                  id="business_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.business_name"
                  required
                />
                <InputError :message="form.errors.business_name" class="mt-2" />
              </div>

              <!-- Address -->
              <div>
                <InputLabel for="address" value="Address" />
                <textarea
                  id="address"
                  rows="2"
                  class="mt-1 block w-full border rounded-md"
                  v-model="form.address"
                  required
                ></textarea>
                <InputError :message="form.errors.address" class="mt-2" />
              </div>

              <!-- Country -->
              <div>
                <InputLabel for="country" value="Country" />
                <TextInput
                  id="country"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.country"
                  required
                />
                <InputError :message="form.errors.country" class="mt-2" />
              </div>

              <!-- VAT Number -->
              <div>
                <InputLabel for="vat_number" value="VAT Number" />
                <TextInput
                  id="vat_number"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.vat_number"
                  required
                />
                <InputError :message="form.errors.vat_number" class="mt-2" />
              </div>

              <!-- Sales Contact Name -->
              <div>
                <InputLabel for="sales_contact_name" value="Sales Contact Name" />
                <TextInput
                  id="sales_contact_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.sales_contact_name"
                  required
                />
                <InputError :message="form.errors.sales_contact_name" class="mt-2" />
              </div>

              <!-- Sales Contact Email -->
              <div>
                <InputLabel for="sales_contact_email" value="Sales Contact Email" />
                <TextInput
                  id="sales_contact_email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.sales_contact_email"
                  required
                />
                <InputError :message="form.errors.sales_contact_email" class="mt-2" />
              </div>

              <!-- Sales Contact Phone -->
              <div>
                <InputLabel for="sales_contact_phone" value="Sales Contact Phone" />
                <TextInput
                  id="sales_contact_phone"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.sales_contact_phone"
                  required
                />
                <InputError :message="form.errors.sales_contact_phone" class="mt-2" />
              </div>

              <!-- Logistics Contact Name -->
              <div>
                <InputLabel for="logistics_contact_name" value="Logistics Contact Name" />
                <TextInput
                  id="logistics_contact_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.logistics_contact_name"
                  required
                />
                <InputError :message="form.errors.logistics_contact_name" class="mt-2" />
              </div>

              <!-- Logistics Contact Email -->
              <div>
                <InputLabel for="logistics_contact_email" value="Logistics Contact Email" />
                <TextInput
                  id="logistics_contact_email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.logistics_contact_email"
                  required
                />
                <InputError :message="form.errors.logistics_contact_email" class="mt-2" />
              </div>

              <!-- Logistics Contact Phone -->
              <div>
                <InputLabel for="logistics_contact_phone" value="Logistics Contact Phone" />
                <TextInput
                  id="logistics_contact_phone"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.logistics_contact_phone"
                  required
                />
                <InputError :message="form.errors.logistics_contact_phone" class="mt-2" />
              </div>
            </div>

            <!-- Form Buttons -->
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
  import AppLayout from '@/Layouts/AppLayout.vue'
  import axios from 'axios'

  // Reusable components (Tailwind-based)
  import InputLabel from '@/Components/InputLabel.vue'
  import TextInput from '@/Components/TextInput.vue'
  import InputError from '@/Components/InputError.vue'
  import Button from '@/Components/PrimaryButton.vue'

  // Reactive state
  const suppliers = ref([])
  const showModal = ref(false)
  const isEditing = ref(false)
  const currentSupplierId = ref(null)
  const processing = ref(false)

  // Match the fields in your controller's validation
  //   - business_name, address, country, vat_number
  //   - sales_contact_name, sales_contact_email, sales_contact_phone
  //   - logistics_contact_name, logistics_contact_email, logistics_contact_phone
  const form = reactive({
    business_name: '',
    address: '',
    country: '',
    vat_number: '',
    sales_contact_name: '',
    sales_contact_email: '',
    sales_contact_phone: '',
    logistics_contact_name: '',
    logistics_contact_email: '',
    logistics_contact_phone: '',
    errors: {}
  })

  // Reset the form fields
  const resetForm = () => {
    form.business_name = ''
    form.address = ''
    form.country = ''
    form.vat_number = ''
    form.sales_contact_name = ''
    form.sales_contact_email = ''
    form.sales_contact_phone = ''
    form.logistics_contact_name = ''
    form.logistics_contact_email = ''
    form.logistics_contact_phone = ''
    form.errors = {}
  }

  // Open the modal for new supplier
  const openCreateModal = () => {
    resetForm()
    isEditing.value = false
    currentSupplierId.value = null
    showModal.value = true
  }

  // Open the modal for editing an existing supplier
  const editSupplier = (supplier) => {
    resetForm()
    isEditing.value = true
    currentSupplierId.value = supplier.id

    form.business_name = supplier.business_name
    form.address = supplier.address
    form.country = supplier.country
    form.vat_number = supplier.vat_number
    form.sales_contact_name = supplier.sales_contact_name
    form.sales_contact_email = supplier.sales_contact_email
    form.sales_contact_phone = supplier.sales_contact_phone
    form.logistics_contact_name = supplier.logistics_contact_name
    form.logistics_contact_email = supplier.logistics_contact_email
    form.logistics_contact_phone = supplier.logistics_contact_phone

    showModal.value = true
  }

  // Close the modal
  const closeModal = () => {
    showModal.value = false
  }

  // Fetch all suppliers (plain JSON API)
  const fetchSuppliers = async () => {
    try {
      // If behind auth, ensure withCredentials: true or set axios.defaults.withCredentials = true globally
      const response = await axios.get('/api/suppliers')
      console.log('response data:', response.data);
      suppliers.value = response.data
    } catch (error) {
      console.error('Failed to fetch suppliers:', error)
    }
  }

  // Create / Update the supplier
  const submitForm = async () => {
    processing.value = true
    form.errors = {}

    try {
      // If editing, do PUT /api/suppliers/:id; else POST /api/suppliers
      if (isEditing.value) {
        await axios.put(`/api/suppliers/${currentSupplierId.value}`, form, { withCredentials: true })
      } else {
        await axios.post('/api/suppliers', form, { withCredentials: true })
      }
      closeModal()
      fetchSuppliers()
    } catch (error) {
      console.error('Failed to submit supplier:', error)
      // If validation errors, store them in form.errors
      form.errors = error.response?.data?.errors || {}
    } finally {
      processing.value = false
    }
  }

  // Delete a supplier
  const deleteSupplier = async (id) => {
    if (!confirm('Are you sure you want to delete this supplier?')) return

    try {
      await axios.delete(`/api/suppliers/${id}`, { withCredentials: true })
      fetchSuppliers()
    } catch (error) {
      console.error('Failed to delete supplier:', error)
    }
  }

  // On component mount, fetch existing suppliers
  onMounted(fetchSuppliers)
  </script>
