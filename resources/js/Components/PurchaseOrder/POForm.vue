<template>
    <div>
        <form @submit.prevent="submit" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Details -->
                <div class="col-span-2">
                    <h2 class="text-lg font-medium">Basic Details</h2>
                </div>

                <div>
                    <InputLabel for="type" value="Purchase Order Type" />
                    <select
                        id="type"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.type"
                        required
                        @change="onTypeChange"
                    >
                        <option value="POD">
                            Purchase Order from Distributor (POD)
                        </option>
                        <option value="POS">
                            Purchase Order to Supplier (POS)
                        </option>
                    </select>
                    <InputError :message="form.errors.type" class="mt-2" />
                </div>

                <div v-if="form.type === 'POD'">
                    <InputLabel for="distributor_id" value="Distributor" />
                    <select
                        id="distributor_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.distributor_id"
                        required
                    >
                        <option
                            v-for="distributor in distributors"
                            :key="distributor.id"
                            :value="distributor.id"
                        >
                            {{ distributor.business_name }}
                        </option>
                    </select>
                    <InputError
                        :message="form.errors.distributor_id"
                        class="mt-2"
                    />
                </div>

                <div v-if="form.type === 'POS'">
                    <InputLabel for="supplier_id" value="Supplier" />
                    <select
                        id="supplier_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        v-model="form.supplier_id"
                        required
                    >
                        <option
                            v-for="supplier in suppliers"
                            :key="supplier.id"
                            :value="supplier.id"
                        >
                            {{ supplier.business_name }}
                        </option>
                    </select>
                    <InputError
                        :message="form.errors.supplier_id"
                        class="mt-2"
                    />
                </div>

                <!-- Line Items -->
                <div class="col-span-2 mt-6">
                    <h2 class="text-lg font-medium">Line Items</h2>
                    <div
                        v-for="(item, index) in form.items"
                        :key="index"
                        class="border p-4 mt-4 rounded-md"
                    >
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <InputLabel
                                    :for="`product_id_${index}`"
                                    value="Product"
                                />
                                <select
                                    :id="`product_id_${index}`"
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    v-model="item.product_id"
                                    required
                                    @change="updatePrice(index)"
                                >
                                    <option
                                        v-for="product in products"
                                        :key="product.id"
                                        :value="product.id"
                                    >
                                        {{ product.name }}
                                    </option>
                                </select>
                            </div>

                            <div>
                                <InputLabel
                                    :for="`quantity_${index}`"
                                    value="Quantity"
                                />
                                <TextInput
                                    :id="`quantity_${index}`"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="item.quantity"
                                    min="1"
                                    required
                                    @input="calculateTotal(index)"
                                />
                            </div>

                            <div>
                                <InputLabel
                                    :for="`delivery_date_${index}`"
                                    value="Delivery Date"
                                />
                                <TextInput
                                    :id="`delivery_date_${index}`"
                                    type="date"
                                    class="mt-1 block w-full"
                                    v-model="item.delivery_date"
                                    required
                                />
                            </div>

                            <div>
                                <InputLabel
                                    :for="`unit_price_${index}`"
                                    value="Unit Price"
                                />
                                <TextInput
                                    :id="`unit_price_${index}`"
                                    type="number"
                                    class="mt-1 block w-full"
                                    v-model="item.unit_price"
                                    min="0"
                                    step="0.01"
                                    required
                                    @input="calculateTotal(index)"
                                />
                            </div>

                            <div class="col-span-3">
                                <p class="mt-4">
                                    Total:
                                    {{
                                        formatCurrency(calculateLineTotal(item))
                                    }}
                                </p>
                            </div>

                            <div class="flex items-center justify-end">
                                <button
                                    type="button"
                                    class="mt-4 bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                    @click="removeItem(index)"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="mt-4 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                        @click="addItem"
                    >
                        Add Item
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between mt-6">
                <div>
                    <p class="text-lg font-bold">
                        Order Total: {{ formatCurrency(calculateOrderTotal()) }}
                    </p>
                </div>
                <Button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    {{ submitLabel }}
                </Button>
            </div>
        </form>
    </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Button from "@/Components/PrimaryButton.vue";
import axios from "axios";

const props = defineProps({
    purchaseOrder: {
        type: Object,
        default: () => ({}),
    },
    submitLabel: {
        type: String,
        default: "Create Purchase Order",
    },
});

const emit = defineEmits(["submitted"]);

const suppliers = ref([]);
const distributors = ref([]);
const products = ref([]);

// Form setup
const form = reactive({
    type: props.purchaseOrder.type || "POD",
    distributor_id: props.purchaseOrder.distributor_id || "",
    supplier_id: props.purchaseOrder.supplier_id || "",
    items: props.purchaseOrder.items?.length
        ? props.purchaseOrder.items.map((item) => ({
              product_id: item.product_id,
              quantity: item.quantity,
              delivery_date: item.delivery_date,
              unit_price: item.unit_price,
          }))
        : [
              {
                  product_id: "",
                  quantity: 1,
                  delivery_date: new Date().toISOString().split("T")[0],
                  unit_price: 0,
              },
          ],
    errors: {},
});

const fetchSuppliers = async () => {
    try {
        const response = await axios.get("/api/suppliers");
        suppliers.value = response.data;
    } catch (error) {
        console.error("Failed to fetch suppliers:", error);
    }
};

const fetchDistributors = async () => {
    try {
        const response = await axios.get("/api/distributors");
        distributors.value = response.data;
    } catch (error) {
        console.error("Failed to fetch distributors:", error);
    }
};

const fetchProducts = async () => {
    try {
        const response = await axios.get("/api/products");
        products.value = response.data;
    } catch (error) {
        console.error("Failed to fetch products:", error);
    }
};

const addItem = () => {
    form.items.push({
        product_id: "",
        quantity: 1,
        delivery_date: new Date().toISOString().split("T")[0],
        unit_price: 0,
    });
};

const removeItem = (index) => {
    if (form.items.length > 1) {
        form.items.splice(index, 1);
    }
};

// Complete the updatePrice function that was cut off
const updatePrice = (index) => {
    const productId = form.items[index].product_id;
    if (productId) {
        const product = products.value.find(
            (p) => p.id === parseInt(productId)
        );
        if (product) {
            const year = new Date().getFullYear();
            const priceKey = `price_${year}`;
            if (product[priceKey]) {
                form.items[index].unit_price = product[priceKey];
            } else {
                // Fall back to most recent price
                form.items[index].unit_price =
                    product.price_2025 ||
                    product.price_2024 ||
                    product.price_2023 ||
                    0;
            }
            calculateTotal(index);
        }
    }
};

const calculateTotal = (index) => {
    const item = form.items[index];
    item.total = item.quantity * item.unit_price;
};

const calculateLineTotal = (item) => {
    return item.quantity * item.unit_price;
};

const calculateOrderTotal = () => {
    return form.items.reduce(
        (total, item) => total + calculateLineTotal(item),
        0
    );
};

const formatCurrency = (value) => {
    return new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: "USD",
    }).format(value);
};

const onTypeChange = () => {
    // Reset related fields when type changes
    if (form.type === "POD") {
        form.supplier_id = "";
    } else {
        form.distributor_id = "";
    }
};

const submit = () => {
    emit("submitted", form);
};

onMounted(() => {
    fetchSuppliers();
    fetchDistributors();
    fetchProducts();
});
</script>
