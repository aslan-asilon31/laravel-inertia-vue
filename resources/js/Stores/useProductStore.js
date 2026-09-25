import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useProductStore = defineStore('product', () => {
    // State: 1 data product utama
    const product = ref({
        id: 1,
        name: 'Laravel Inertia Starter Kit',
        price: 1500000,
        category: 'Software / Template',
        stock: 25,
    });

    // Action untuk mengubah data product dari halaman manapun
    function updateProductName(newName) {
        product.value.name = newName;
    }

    return {
        product,
        updateProductName,
    };
});