<script setup>
import { ref } from 'vue';
import { router, Head, Link, onMounted } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Checkbox } from '@/components/ui/checkbox';
import { Badge } from '@/components/ui/badge';
import debounce from 'lodash/debounce';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';

// Definisi Props dari Controller
const props = defineProps({
    records: Object,
    filters: Object,
    success: String,
    error: String,
    menuStructure: Object,
    user: Object,
});

const searchForm = ref({
    name: props.filters.name || '',
    id: props.filters.id || '',
});

const selectedIds = ref([]);

const handleSearch = debounce(() => {
    router.get(route('ms-action.list'), searchForm.value, {
        preserveState: true,
        replace: true,
    });
}, 300);

const toggleSelectAll = (e) => {
    if (e.target.checked) {
        selectedIds.value = props.records.data.map(item => item.id);
    } else {
        selectedIds.value = [];
    }
};

const deleteBulk = () => {
    if (confirm('Yakin ingin menghapus data terpilih?')) {
        router.post(route('ms-action.bulk-destroy'), { ids: selectedIds.value }, {
            onSuccess: () => selectedIds.value = []
        });
    }
};

const deleteItem = (id) => {
    if (confirm('Yakin ingin menghapus data ini?')) {
        router.delete(route('ms-action.destroy', id));
    }
};


onMounted(() => {
    console.log('--- MENU STRUCTURE PROPS ---');
    console.log(props.menuStructure);
});
</script>


<template>
    <AuthenticatedLayout :menu-structure="menuStructure" :user="user">
        <Head title="Action List" />

        <div class="p-6 max-w-7xl mx-auto space-y-6">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold tracking-tight">Action List</h1>
                <Button as-child class="bg-orange-900 hover:bg-orange-800 text-white">
                    <Link :href="route('ms-action.create')">Tambah Action</Link>
                </Button>
            </div>

            <!-- Notifikasi Sukses -->
            <div v-if="success" class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg text-sm">
                {{ success }}
            </div>

            <!-- Filter Area -->
            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm grid grid-cols-1 md:grid-cols-3 gap-4">
                <Input 
                    v-model="searchForm.name" 
                    @input="handleSearch" 
                    placeholder="Cari berdasarkan nama..." 
                />
                <div class="flex gap-2 items-center">
                    <Button v-if="selectedIds.length > 0" variant="destructive" @click="deleteBulk">
                        Hapus Pilihan ({{ selectedIds.length }})
                    </Button>
                </div>
            </div>

            <!-- Tabel Data -->
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-12">
                                <input type="checkbox" @change="toggleSelectAll" />
                            </TableHead>
                            <TableHead>ID</TableHead>
                            <TableHead>Nama</TableHead>
                            <TableHead>Status</TableHead>
                            <TableHead>Urutan</TableHead>
                            <TableHead class="text-right">Aksi</TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="item in records.data" :key="item.id">
                            <TableCell>
                                <input type="checkbox" :value="item.id" v-model="selectedIds" />
                            </TableCell>
                            <TableCell class="font-mono text-xs">{{ item.id }}</TableCell>
                            <TableCell class="font-medium">{{ item.name }}</TableCell>
                            <TableCell>
                                <Badge variant="outline">{{ item.status }}</Badge>
                            </TableCell>
                            <TableCell>{{ item.ordinal }}</TableCell>
                            <TableCell class="text-right space-x-2">
                                <Button variant="ghost" size="sm" as-child>
                                    <Link :href="route('ms-action.edit', item.id)">Edit</Link>
                                </Button>
                                <Button variant="ghost" size="sm" class="text-red-600 hover:text-red-700" @click="deleteItem(item.id)">
                                    Hapus
                                </Button>
                            </TableCell>
                        </TableRow>
                        <TableRow v-if="records.data.length === 0">
                            <TableCell colspan="6" class="text-center py-6 text-gray-400">Tidak ada data ditemukan.</TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
    </AuthenticatedLayout>
</template>