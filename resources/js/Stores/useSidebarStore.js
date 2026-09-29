import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useSidebarStore = defineStore('sidebar', () => {
    // State global
    const menuStructure = ref({});
    const user = ref(null);

    // Action untuk mengisi data (biasanya dipanggil dari Layout atau Inertia Shared Props)
    function setSidebarData(newMenuStructure, newUser) {
        console.log('--- SET SIDEBAR DATA ---');
        console.log('Menu Structure Input:', newMenuStructure);
        console.log('User Input:', newUser);
        
        menuStructure.value = newMenuStructure;
        user.value = newUser;
    }

    return {
        menuStructure,
        user,
        setSidebarData,
    };
});