<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';

// Halaman Index bersih dari logika sidebar/store karena sudah di-handle Layout
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Employee List" />

        <div class="space-y-6 p-6">
            <h1 class="text-2xl font-bold tracking-tight">Employee List</h1>
        </div>
    </AuthenticatedLayout>
</template>