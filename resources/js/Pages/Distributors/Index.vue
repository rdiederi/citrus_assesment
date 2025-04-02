<template>
    <AppLayout title="Distributors">
      <template #header>
        <div class="flex justify-between items-center">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Distributors
          </h2>
          <button @click="openCreateModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Add Distributor
          </button>
        </div>
      </template>

      <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
            <div v-if="distributors.length">
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
                  <tr v-for="distributor in distributors" :key="distributor.id">
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      {{ distributor.business_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      {{ distributor.contact_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      {{ distributor.email }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      {{ distributor.phone }}
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                            :class="{
                              'bg-green-100 text-green-800': distributor.status === 'Active',
                              'bg-red-100 text-red-800': distributor.status === 'Inactive'
                            }">
                        {{ distributor.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                      <button @click="editDistributor(distributor)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                      <button @click="deleteDistributor(distributor.id)" class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div v-else class="p-6">
              No distributors found.
            </div>
          </div>
        </div>
      </div>

      <!-- Distributor Modal -->
      <div v-if="showModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
          <h3 class="text-lg font-medium mb-4">{{ isEditing ? 'Edit Distributor' : 'Add Distributor' }}</h3>

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

  const distributors = ref([]);
  const showModal = ref(false);
  const isEditing = ref(false);
  const currentDistributorId = ref(null);
  const processing = ref(false);

  const form = reactive({
    business_name: '',
    contact_name: '',
    email: '',
    phone: '',
    address: '',
    status: 'Active',
    errors: {}
  });

  const resetForm = () => {
    form.business_name = '';
    form.contact_name = '';
    form.email = '';
    form.phone = '';
    form.address = '';
    form.status = 'Active';
    form.errors = {};
  };

  const openCreateModal = () => {
    resetForm();
    isEditing.value = false;
    currentDistributorId.value = null;
    showModal.value = true;
  };

  const editDistributor = (distributor) => {
    resetForm();
    isEditing.value = true;
    currentDistributorId.value = distributor.id;

    form.business_name = distributor.business_name;
    form.contact_name = distributor.contact_name;
    form.email = distributor.email;
    form.phone = distributor.phone;
    form.address = distributor.address || '';
    form.status = distributor.status;

    showModal.value = true;
  };

  const closeModal = () => {
    showModal.value = false;
  };

  const fetchDistributors = async () => {
    try {
      const response = await axios.get('/api/distributors');
      distributors.value = response.data;
    } catch (error) {
      console.error('Failed to fetch distributors:', error);
    }
  };

  const submitForm = async () => {
    processing.value = true;
    form.errors = {};

    try {
      if (isEditing.value) {
        await axios.put(`/api/distributors/${currentDistributorId.value}`, form);
      } else {
        await axios.post('/api/distributors', form);
      }

      closeModal();
      fetchDistributors();
    } catch (error) {
      console.error('Failed to submit distributor:', error);
      form.errors = error.response?.data?.errors || {};
    } finally {
      processing.value = false;
    }
  };

  const deleteDistributor = async (id) => {
    if (!confirm('Are you sure you want to delete this distributor?')) return;

    try {
      await axios.delete(`/api/distributors/${id}`);
      fetchDistributors();
    } catch (error) {
      console.error('Failed to delete distributor:', error);
    }
  };

  onMounted(fetchDistributors);
  </script>
