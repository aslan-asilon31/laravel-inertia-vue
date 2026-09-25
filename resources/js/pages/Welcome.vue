<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import { useProductStore } from '@/Stores/useProductStore';

const productStore = useProductStore();

</script>

<template>
    <Head title="Welcome">
        <link rel="preconnect" href="https://rsms.me/" />
        <link rel="stylesheet" href="https://rsms.me/inter/inter.css" />
    </Head>
    <div>
        welcome

        <div class="bg-gray-50 p-4 rounded border">
                <p><strong>Nama Produk:</strong> {{ productStore.product.name }}</p>
                <p><strong>Harga:</strong> Rp {{ productStore.product.price }}</p>
            </div>
            
    </div>
</template>
