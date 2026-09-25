<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useProductStore } from '@/Stores/useProductStore';

defineProps({
    info: String,
});

const productStore = useProductStore();

// Inertia Form helper untuk POST data ke Controller
const form = useForm({
    name: '',
    email: '',
    message: '',
});

const submit = () => {
    form.post(route('contact.send'), {
        onSuccess: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Contact" />
    <div class="min-h-screen bg-gray-100 p-8">
        <nav class="flex gap-4 mb-8">
            <Link href="/" class="text-gray-600 hover:text-indigo-600">Welcome</Link>
            <Link href="/about" class="text-gray-600 hover:text-indigo-600">About</Link>
            <Link href="/contact" class="text-indigo-600 font-bold">Contact</Link>
        </nav>

        <div class="max-w-xl bg-white p-6 rounded-lg shadow space-y-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Halaman Contact</h1>
                <p class="text-sm text-gray-500">{{ info }}</p>
            </div>

            <!-- Flash Message sukses dari Controller -->
            <div v-if="$page.props.flash?.success" class="p-3 bg-green-100 text-green-700 text-sm rounded">
                {{ $page.props.flash.success }}
            </div>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama</label>
                    <input v-model="form.name" type="text" class="mt-1 block w-full rounded border-gray-300 p-2 border shadow-sm" />
                    <span v-if="form.errors.name" class="text-red-500 text-xs">{{ form.errors.name }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Email</label>
                    <input v-model="form.email" type="email" class="mt-1 block w-full rounded border-gray-300 p-2 border shadow-sm" />
                    <span v-if="form.errors.email" class="text-red-500 text-xs">{{ form.errors.email }}</span>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Pesan</label>
                    <textarea v-model="form.message" rows="3" class="mt-1 block w-full rounded border-gray-300 p-2 border shadow-sm"></textarea>
                    <span v-if="form.errors.message" class="text-red-500 text-xs">{{ form.errors.message }}</span>
                </div>

                <button type="submit" :disabled="form.processing" class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 disabled:opacity-50">
                    Kirim Pesan
                </button>
            </form>

            <div class="bg-gray-50 p-4 rounded border">
                <p class="text-sm font-semibold text-gray-500 mb-1">State Produk Pinia:</p>
                <p class="text-sm">Produk aktif: <strong>{{ productStore.product.name }}</strong></p>
            </div>
        </div>
    </div>
</template>