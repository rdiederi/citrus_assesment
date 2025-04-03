<template>
    <AppLayout title="Create Supplier">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Supplier
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"
                >
                    <SupplierForm
                        submit-label="Create Supplier"
                        @submitted="createSupplier"
                    />
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import SupplierForm from "@/Components/Supplier/SupplierForm.vue";
import { router } from "@inertiajs/vue3";

const createSupplier = (form) => {
    form.post(route("suppliers.store"), {
        onError: (errors) => {
            console.error("Login error:", errors);
        },
        onSuccess: () => {
            console.log("Supplier created successfully");

            form.reset();
        },
    });
};
</script>
