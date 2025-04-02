<template>
  <AppLayout title="Products">
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Products
        </h2>
        <button @click="openCreateModal" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
          Add Product
        </button>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div v-if="products.length">
            <table class="min-w-full">
              <thead>
                <tr>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    SKU
                  </th>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Name
                  </th>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Category
                  </th>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Price (2024)
                  </th>
                  <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white">
                <tr v-for="product in products" :key="product.id">
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    {{ product.sku }}
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    {{ product.name }}
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    {{ product.category }}
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    {{ formatCurrency(product.price_2024) }}
                  </td>
                  <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                    <button @click="editProduct(product)" class="text-indigo-600 hover:text-indigo-900 mr-4">Edit</button>
                    <button @click="deleteProduct(product.id)" class="text-red-600 hover:text-red-900">Delete</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div v-else class="p-6">
            No products found.
          </div>
        </div>
      </div>
    </div>

    <!-- Product Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full p-6">
        <h3 class="text-lg font-medium mb-4">{{ isEditing ? 'Edit Product' : 'Add Product' }}</h3>

        <form @submit.prevent="submitForm">
          <div class="space-y-4">
            <div>
              <InputLabel for="sku" value="SKU" />
              <TextInput
                id="sku"
                type="text"
                class="mt-1 block w-full"
                v-model="form.sku"
                required
              />
              <InputError :message="form.errors.sku" class="mt-2" />
            </div>

            <div>
              <InputLabel for="name" value="Name" />
              <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
              />
              <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <div>
              <InputLabel for="description" value="Description" />
              <textarea
                id="description"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.description"
                rows="3"
              ></textarea>
              <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <div>
              <InputLabel for="category" value="Category" />
              <TextInput
                id="category"
                type="text"
                class="mt-1 block w-full"
                v-model="form.category"
                required
              />
              <InputError :message="form.errors.category" class="mt-2" />
            </div>

            <div>
              <InputLabel for="price_2023" value="Price (2023)" />
              <TextInput
                id="price_2023"
                type="number"
                step="0.01"
                class="mt-1 block w-full"
                v-model="form.price_2023"
              />
              <InputError :message="form.errors.price_2023" class="mt-2" />
            </div>

            <div>
              <InputLabel for="price_2024" value="Price (2024)" />
              <TextInput
                id="price_2024"
                type="number"
                step="0.01"
                class="mt-1 block w-full"
                v-model="form.price_2024"
                required
              />
              <InputError :message="form.errors.price_2024" class="mt-2" />
            </div>

            <div>
              <InputLabel for="price_2025" value="Price (2025)" />
              <TextInput
                id="price_2025"
                type="number"
                step="0.01"
                class="mt-1 block w-full"
                v-model="form.price_2025"
              />
              <InputError :message="form.errors.price_2025" class="mt-2" />
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

const products = ref([]);
const showModal = ref(false);
const isEditing = ref(false);
const currentProductId = ref(null);
const processing = ref(false);

const form = reactive({
  sku: '',
  name: '',
  description: '',
  category: '',
  price_2023: '',
  price_2024: '',
  price_2025: '',
  errors: {}
});

const resetForm = () => {
  form.sku = '';
  form.name = '';
  form.description = '';
  form.category = '';
  form.price_2023 = '';
  form.price_2024 = '';
  form.price_2025 = '';
  form.errors = {};
};

const openCreateModal = () => {
  resetForm();
  isEditing.value = false;
  currentProductId.value = null;
  showModal.value = true;
};

const editProduct = (product) => {
  resetForm();
  isEditing.value = true;
  currentProductId.value = product.id;

  form.sku = product.sku;
  form.name = product.name;
  form.description = product.description || '';
  form.category = product.category;
  form.price_2023 = product.price_2023 || '';
  form.price_2024 = product.price_2024 || '';
  form.price_2025 = product.price_2025 || '';

  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const fetchProducts = async () => {
  try {
    const response = await axios.get('/api/products');
    products.value = response.data;
  } catch (error) {
    console.error('Failed to fetch products:', error);
  }
};

const formatCurrency = (value) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(value || 0);
};

const submitForm = async () => {
  processing.value = true;
  form.errors = {};

  try {
    if (isEditing.value) {
      await axios.put(`/api/products/${currentProductId.value}`, form);
    } else {
      await axios.post('/api/products', form);
    }

    closeModal();
    fetchProducts();
  } catch (error) {
    console.error('Failed to submit product:', error);
    form.errors = error.response?.data?.errors || {};
  } finally {
    processing.value = false;
  }
};

const deleteProduct = async (id) => {
  if (!confirm('Are you sure you want to delete this product?')) return;

  try {
    await axios.delete(`/api/products/${id}`);
    fetchProducts();
  } catch (error) {
    console.error('Failed to delete product:', error);
  }
};

onMounted(fetchProducts);
</script>
