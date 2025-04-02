<template>
    <div>
      <form @submit.prevent="submit" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Business Details -->
          <div class="col-span-2">
            <h2 class="text-lg font-medium">Business Details</h2>
          </div>

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

          <div class="col-span-2">
            <InputLabel for="address" value="Address" />
            <textarea
              id="address"
              class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              v-model="form.address"
              rows="3"
              required
            ></textarea>
            <InputError :message="form.errors.address" class="mt-2" />
          </div>

          <!-- Sales Contact -->
          <div class="col-span-2 mt-6">
            <h2 class="text-lg font-medium">Sales Contact</h2>
          </div>

          <div>
            <InputLabel for="sales_contact_name" value="Contact Name" />
            <TextInput
              id="sales_contact_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.sales_contact_name"
              required
            />
            <InputError :message="form.errors.sales_contact_name" class="mt-2" />
          </div>

          <div>
            <InputLabel for="sales_contact_email" value="Email" />
            <TextInput
              id="sales_contact_email"
              type="email"
              class="mt-1 block w-full"
              v-model="form.sales_contact_email"
              required
            />
            <InputError :message="form.errors.sales_contact_email" class="mt-2" />
          </div>

          <div>
            <InputLabel for="sales_contact_phone" value="Phone" />
            <TextInput
              id="sales_contact_phone"
              type="text"
              class="mt-1 block w-full"
              v-model="form.sales_contact_phone"
              required
            />
            <InputError :message="form.errors.sales_contact_phone" class="mt-2" />
          </div>

          <!-- Logistics Contact -->
          <div class="col-span-2 mt-6">
            <h2 class="text-lg font-medium">Logistics Contact</h2>
          </div>

          <div>
            <InputLabel for="logistics_contact_name" value="Contact Name" />
            <TextInput
              id="logistics_contact_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.logistics_contact_name"
              required
            />
            <InputError :message="form.errors.logistics_contact_name" class="mt-2" />
          </div>

          <div>
            <InputLabel for="logistics_contact_email" value="Email" />
            <TextInput
              id="logistics_contact_email"
              type="email"
              class="mt-1 block w-full"
              v-model="form.logistics_contact_email"
              required
            />
            <InputError :message="form.errors.logistics_contact_email" class="mt-2" />
          </div>

          <div>
            <InputLabel for="logistics_contact_phone" value="Phone" />
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

        <div class="flex items-center justify-end mt-6">
          <Button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            {{ submitLabel }}
          </Button>
        </div>
      </form>
    </div>
  </template>

  <script setup>
  import { useForm } from '@inertiajs/vue3';
  import InputError from '@/Components/InputError.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import Button from '@/Components/PrimaryButton.vue';

  const props = defineProps({
    supplier: {
      type: Object,
      default: () => ({})
    },
    submitLabel: {
      type: String,
      default: 'Save'
    }
  });

  const emit = defineEmits(['submitted']);

  const form = useForm({
    business_name: props.supplier.business_name || '',
    address: props.supplier.address || '',
    country: props.supplier.country || '',
    vat_number: props.supplier.vat_number || '',
    sales_contact_name: props.supplier.sales_contact_name || '',
    sales_contact_email: props.supplier.sales_contact_email || '',
    sales_contact_phone: props.supplier.sales_contact_phone || '',
    logistics_contact_name: props.supplier.logistics_contact_name || '',
    logistics_contact_email: props.supplier.logistics_contact_email || '',
    logistics_contact_phone: props.supplier.logistics_contact_phone || '',
  });

  const submit = () => {
    emit('submitted', form);
  };
  </script>
