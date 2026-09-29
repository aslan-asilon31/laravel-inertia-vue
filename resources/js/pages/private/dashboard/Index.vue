<script setup>
import { onMounted } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Card, CardContent } from '@/components/ui/card';
import { UserCheck } from 'lucide-vue-next';
import { useSidebarStore } from '@/Stores/useSidebarStore';

const props = defineProps({
    employeeName: String,
    user: Object,
    menuStructure: Object,
});

const sidebarStore = useSidebarStore();

// Masukkan data ke Pinia Store saat halaman dashboard dimuat
onMounted(() => {
    sidebarStore.setSidebarData(props.menuStructure, props.user);
});
</script>

<template>
    <Head title="Dashboard" />

    <!-- Menggunakan layout utama yang otomatis memuat Sidebar dari Pinia -->
    <AuthenticatedLayout :menu-structure="menuStructure" :user="user">
        
        <div class="grid grid-rows-[auto_1fr] min-h-[calc(100vh-8.5rem)] pb-4 space-y-6">
            
            <!-- Page Header -->
            <div class="flex justify-between items-center border-b pb-4">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Dashboard</h1>
                    <p class="text-sm text-gray-500">Selamat datang kembali di sistem internal.</p>
                </div>
            </div>

            <!-- Content Card Utama -->
            <Card class="flex flex-col overflow-hidden shadow-sm h-full border-gray-100 relative isolate justify-center items-center">
                
                <!-- Background Gradient Decoration -->
                <div class="absolute -right-20 -top-20 -z-10 h-64 w-64 rounded-full bg-orange-500/10 blur-3xl"></div>
                <div class="absolute -bottom-24 -left-20 -z-10 h-64 w-64 rounded-full bg-amber-500/10 blur-3xl"></div>

                <CardContent class="flex flex-col items-center justify-center px-6 py-16 text-center sm:px-10">
                    
                    <!-- Welcome Icon -->
                    <div class="mb-7 flex h-24 w-24 items-center justify-center rounded-3xl bg-orange-50 text-orange-900 ring-8 ring-orange-500/5">
                        <UserCheck class="h-12 w-12" />
                    </div>

                    <!-- User Greeting Name -->
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl lg:text-5xl">
                        Selamat Datang,
                        <span class="text-orange-900 block mt-2">
                            {{ employeeName }}
                        </span>
                    </h1>

                    <!-- Divider -->
                    <div class="my-8 h-px w-16 bg-orange-900/30"></div>
                </CardContent>

            </Card>

            <!-- Card Bawah Kosong / Tambahan Statistik -->
            <Card class="h-16 flex items-center px-6 text-sm text-gray-400 border-gray-100">
                Sistem aktif & terhubung dengan baik.
            </Card>

        </div>

    </AuthenticatedLayout>
</template>