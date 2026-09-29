<script setup>
import { ref, onMounted } from 'vue';
import { router, Head, Link, usePage } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Sidebar from '@/components/Sidebar.vue';
import { Button } from '@/components/ui/button';
import { Menu } from 'lucide-vue-next';
import { useSidebarStore } from '@/Stores/useSidebarStore';

// Inisialisasi usePage untuk mengambil shared props dari middleware Laravel
const page = usePage();
const sidebarStore = useSidebarStore();
const isMobileSidebarOpen = ref(false);

// Masukkan data global dari Middleware/Inertia ke Pinia Store saat layout dimuat
onMounted(() => {
    const menuStructure = page.props.menuStructure;
    const user = page.props.auth?.user;

    // Set data ke store Pinia
    sidebarStore.setSidebarData(menuStructure, user);
});
</script>

<template>
    <div class="min-h-screen flex bg-gray-50 font-sans antialiased text-gray-900">
        
        <!-- Sidebar Desktop -->
        <aside class="hidden lg:block shrink-0 sticky top-0 h-screen">
            <Sidebar />
        </aside>

        <!-- Wrapper Utama -->
        <div class="flex-1 flex flex-col min-w-0">
            
            <!-- Navbar Mobile -->
            <header class="lg:hidden flex items-center justify-between bg-white border-b border-gray-100 px-4 h-16 sticky top-0 z-30">
                <span class="font-black text-lg text-orange-900">Umedalife</span>
                <Button variant="ghost" size="icon" @click="isMobileSidebarOpen = !isMobileSidebarOpen">
                    <Menu class="h-5 w-5" />
                </Button>
            </header>

            <!-- Sidebar Mobile Drawer Modal -->
            <div v-if="isMobileSidebarOpen" class="fixed inset-0 z-50 flex lg:hidden">
                <div class="fixed inset-0 bg-black/50" @click="isMobileSidebarOpen = false"></div>
                <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white z-50">
                    <Sidebar />
                </div>
            </div>

            <!-- Konten Halaman (Slot Utama) -->
            <main class="flex-1 p-6 md:p-8 overflow-y-auto">
                <slot />
            </main>

            <!-- Footer -->
            <footer class="py-4 text-center text-xs text-gray-400 border-t border-gray-100 bg-white">
                &copy; 2026 Umedalife. All rights reserved.
            </footer>
        </div>

    </div>
</template>