<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { useProductStore } from '@/Stores/useProductStore';

// Menerima data props dari Controller Laravel
defineProps({
    title: String,
    description: String,
});

const productStore = useProductStore();
</script>

<template>
    <Head :title="title" />
    <div class="min-h-screen bg-gray-100 p-8">
        <nav class="flex gap-4 mb-8">
            <Link href="/" class="text-gray-600 hover:text-indigo-600">Welcome</Link>
            <Link href="/about" class="text-indigo-600 font-bold">About</Link>
            <Link href="/contact" class="text-gray-600 hover:text-indigo-600">Contact</Link>
        </nav>

        <div class="max-w-xl bg-white p-6 rounded-lg shadow space-y-4">
            <h1 class="text-2xl font-bold text-gray-900">{{ title }}</h1>
            <p class="text-gray-600">{{ description }}</p>
            
            <div class="bg-gray-50 p-4 rounded border">
                <p class="text-sm font-semibold text-gray-500 mb-2">Data Produk Global dari Pinia:</p>
                <p><strong>Nama Produk:</strong> {{ productStore.product.name }}</p>
            </div>
        </div>
    </div>
</template>