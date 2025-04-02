<template>
    <AppLayout title="Suppliers">
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Suppliers
          </h2>
          <button @click="openCreateModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add Supplier
          </button>
        </div>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div v-if="suppliers.length">
              <table class="min-w-full">
                <thead>
                  <tr>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                      Business Name
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                      Contact Person
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                      Email
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                      Phone
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <tr v-for="supplier in suppliers" :key="supplier.id">
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      {{ supplier.business_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      {{ supplier.contact_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      {{ supplier.email }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      {{ supplier.phone }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                            :class="{
                              'bg-green-100 text-green-800': supplier.status === 'Active',
                              'bg-red-100 text-red-800': supplier.status === 'Inactive'
                            }">
                        {{ supplier.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      <button @click="editSupplier(supplier)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                      <button @click="deleteSupplier(supplier.id)" class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="p-6">
              No suppliers found.
            </div>
          </div>
        </div>
      </div>

      <!-- Supplier Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
          <h3 class="text-lg font-medium mb-4">{{ isEditing ? 'Edit Supplier' : 'Add Supplier' }}</h3>

          <form @submit.prevent="submitForm">
            <div class="space-y-4">
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

              <div>
                <InputLabel for="contact_name" value="Contact Person" />
                <TextInput
                  id="contact_name"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.contact_name"
                  required
                />
                <InputError :message="form.errors.contact_name" class="mt-2" />
              </div>

              <div>
                <InputLabel for="email" value="Email" />
                <TextInput
                  id="email"
                  type="email"
                  class="mt-1 block w-full"
                  v-model="form.email"
                  required
                />
                <InputError :message="form.errors.email" class="mt-2" />
              </div>

              <div>
                <InputLabel for="phone" value="Phone" />
                <TextInput
                  id="phone"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.phone"
                  required
                />
                <InputError :message="form.errors.phone" class="mt-2" />
              </div>

              <div>
                <InputLabel for="address" value="Address" />
                <textarea
                  id="address"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  v-model="form.address"
                  rows="3"
                ></textarea>
                <InputError :message="form.errors.address" class="mt-2" />
              </div>

              <div>
                <InputLabel for="payment_terms" value="Payment Terms" />
                <TextInput
                  id="payment_terms"
                  type="text"
                  class="mt-1 block w-full"
                  v-model="form.payment_terms"
                />
                <InputError :message="form.errors.payment_terms" class="mt-2" />
              </div>

              <div>
                <InputLabel for="status" value="Status" />
                <select
                  id="status"
                  class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                  v-model="form.status"
                  required
                >
                  <option value="Active">Active</option>
                  <option value="Inactive">Inactive</option>
                </select>
                <InputError :message="form.errors.status" class="mt-2" />
              </div>
            </div>

            <div class="flex justify-end mt-6 space-x-3">
              <button
                type="button"
                class="bg-gray-300 text-gray-800 rounded py-2 px-4 hover:bg-gray-400"
                @click="closeModal"
              >
                Cancel
              </button>
              <Button type="submit" :disabled="processing">
                {{ isEditing ? 'Update' : 'Create' }}
              </Button>
            </div>
          </form>
        </div>
      </div>
    </AppLayout>
  </template>

  <script setup>
  import { ref, reactive, onMounted } from 'vue';
  import AppLayout from '@/Layouts/AppLayout.vue';
  import axios from 'axios';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputError from '@/Components/InputError.vue';
  import Button from '@/Components/PrimaryButton.vue';

  const suppliers = ref([]);
  const showModal = ref(false);
  const isEditing = ref(false);
  const currentSupplierId = ref(null);
  const processing = ref(false);

  const form = reactive({
    business_name: '',
    contact_name: '',
    email: '',
    phone: '',
    address: '',
    payment_terms: '',
    status: 'Active',
    errors: {}
  });

  const resetForm = () => {
    form.business_name = '';
    form.contact_name = '';
    form.email = '';
    form.phone = '';
    form.address = '';
    form.payment_terms = '';
    form.status = 'Active';
    form.errors = {};
  };

  const openCreateModal = () => {
    resetForm();
    isEditing.value = false;
    currentSupplierId.value = null;
    showModal.value = true;
  };

  const editSupplier = (supplier) => {
    resetForm();
    isEditing.value = true;
    currentSupplierId.value = supplier.id;

    form.business_name = supplier.business_name;
    form.contact_name = supplier.contact_name;
    form.email = supplier.email;
    form.phone = supplier.phone;
    form.address = supplier.address || '';
    form.payment_terms = supplier.payment_terms || '';
    form.status = supplier.status;

    showModal.value = true;
  };

  const closeModal = () => {
    showModal.value = false;
  };

  const fetchSuppliers = async () => {
    try {
      const response = await axios.get('/api/suppliers');
      suppliers.value = response.data;
    } catch (error) {
      console.error('Failed to fetch suppliers:', error);
    }
  };

  const submitForm = async () => {
    processing.value = true;
    form.errors = {};

    try {
      if (isEditing.value) {
        await axios.put(`/api/suppliers/${currentSupplierId.value}`, form);
      } else {
        await axios.post('/api/suppliers', form);
      }

      closeModal();
      fetchSuppliers();
    } catch (error) {
      console.error('Failed to submit supplier:', error);
      form.errors = error.response?.data?.errors || {};
    } finally {
      processing.value = false;
    }
  };

  const deleteSupplier = async (id) => {
    if (!confirm('Are you sure you want to delete this supplier?')) return;

    try {
      await axios.delete(`/api/suppliers/${id}`);
      fetchSuppliers();
    } catch (error) {
      console.error('Failed to delete supplier:', error);
    }
  };

  onMounted(fetchSuppliers);
  </script>
