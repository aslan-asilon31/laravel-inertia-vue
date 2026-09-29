<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';
import { useSidebarStore } from '@/Stores/useSidebarStore';

import * as LucideIcons from 'lucide-vue-next';
const Search = LucideIcons.Search;
const Cube = LucideIcons.Boxes || LucideIcons.Cube || LucideIcons.SquareCode;
const FileText = LucideIcons.FileText;
const ShieldCheck = LucideIcons.ShieldCheck;
const LogOut = LucideIcons.LogOut;

const sidebarStore = useSidebarStore();
const searchMenu = ref('');
</script>

<template>
    <div class="flex flex-col h-full bg-white border-r border-gray-100 w-64 p-4 space-y-4">
        
        <!-- App Brand -->
        <div class="px-2 py-3 flex items-center justify-between border-b border-gray-100">
            <span class="font-black text-xl bg-gradient-to-r from-amber-900 to-orange-700 bg-clip-text text-transparent">
                Umedalife
            </span>
        </div>

        <!-- Input Pencarian -->
        <div class="relative">
            <component :is="Search" class="absolute left-2.5 top-2.5 h-4 w-4 text-gray-400" />
            <Input 
                v-model="searchMenu" 
                placeholder="Cari menu..." 
                class="pl-9 bg-gray-50/50 text-sm" 
            />
        </div>

        <!-- Menu Navigation List (Looping berdasarkan array terstruktur dari MenuService) -->
        <div class="flex-1 overflow-y-auto space-y-6 pr-1">
            <template v-for="(category, index) in sidebarStore.menuStructure" :key="index">
                <div v-if="category.items && category.items.length > 0">
                    
                    <!-- Judul Kategori (Master Data, Transaksi, dll) -->
                    <div class="px-2 mb-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        {{ category.title }}
                    </div>

                    <!-- Items Menu di dalam Kategori -->
                    <div class="space-y-1">
                        <template v-for="(item, itemIndex) in category.items" :key="itemIndex">
                            <Link 
                                :href="item.url" 
                                class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-colors text-gray-600 hover:bg-orange-50 hover:text-orange-900">
                                <component :is="Cube" class="h-4 w-4" />
                                <span>{{ item.name }}</span>
                            </Link>
                        </template>
                    </div>

                </div>
            </template>
        </div>

        <!-- Profil Pengguna & Logout -->
        <div v-if="sidebarStore.user" class="border-t border-gray-100 pt-4 mt-auto">
            <div class="flex items-center justify-between p-2 bg-gray-50 rounded-xl">
                <div class="truncate">
                    <p class="text-sm font-semibold text-gray-800 truncate" :title="sidebarStore.user.name">{{ sidebarStore.user.name }}</p>
                    <p class="text-xs text-gray-500 truncate" :title="sidebarStore.user.position">{{ sidebarStore.user.position ?? 'Employee' }}</p>
                </div>
                <Button variant="ghost" size="icon" as-child class="text-red-500 hover:text-red-700 hover:bg-red-50">
                    <Link href="/logout" method="post" as="button" title="Logout">
                        <component :is="LogOut" class="h-4 w-4" />
                    </Link>
                </Button>
            </div>
        </div>

    </div>
</template>