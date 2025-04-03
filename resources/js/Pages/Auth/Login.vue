<template>
    <Head title="Login" />

    <div
        class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100"
    >
        <!-- App Logo / Title -->
        <div>
            <h1 class="text-2xl font-bold">MyApp Login</h1>
        </div>

        <div
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg"
        >
            <form @submit.prevent="submitLogin" class="space-y-6">
                <!-- Email Address -->
                <div>
                    <label
                        for="email"
                        class="block font-medium text-sm text-gray-700"
                        >Email</label
                    >
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        required
                        autofocus
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    />
                    <InputError :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div class="mt-4">
                    <label
                        for="password"
                        class="block font-medium text-sm text-gray-700"
                        >Password</label
                    >
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        required
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    />
                    <InputError :message="form.errors.password" />
                </div>

                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input
                            id="remember_me"
                            type="checkbox"
                            v-model="form.remember"
                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                        />
                        <span class="ml-2 text-sm text-gray-600"
                            >Remember me</span
                        >
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex items-center justify-end mt-4">
                    <Button
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Log in
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
/**
 * 1) We import necessary Inertia/Vue utilities.
 * 2) We'll call Sanctum's CSRF endpoint manually before attempting to log in.
 */
import { Head, useForm } from "@inertiajs/vue3";
import axios from "axios";
import InputError from "@/Components/InputError.vue";
import Button from "@/Components/PrimaryButton.vue";

/**
 * We create an Inertia form object for the login fields.
 * - email
 * - password
 * - remember
 */
const form = useForm({
    email: "",
    password: "",
    remember: false,
});

/**
 * The main "submit" method:
 * 1) Fetch CSRF cookie from /sanctum/csrf-cookie
 * 2) Post to the 'login' route with Inertia (which sets session cookie on success)
 * 3) If successful, user is redirected (by default) to /dashboard or wherever
 */
const submitLogin = async () => {
    try {
        // Step 1: Fetch Sanctum CSRF cookie (important for new custom login flows)
        await axios.get("/sanctum/csrf-cookie", { withCredentials: true });

        try {
            const response = await axios.post('/api/login', {
                email: form.email,
                password: form.password,
            });
            localStorage.setItem('token', response.data.token); // Store the token in local storage
            // Redirect or perform actions after login
        } catch (error) {
            console.error('Login error:', error);
        }

        // Step 2: Post credentials to Laravel's default /login route
        await form.post(route("login"), {
            onError: (errors) => {
                console.error("Login error:", errors);
            },
            onSuccess: (message) => {
                console.log(message);
            },
        });
    } catch (error) {
        console.error("Failed to log in:", error);
    }
};
</script>
