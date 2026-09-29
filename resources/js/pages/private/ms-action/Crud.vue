<script setup>
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Checkbox } from '@/components/ui/checkbox';
import debounce from 'lodash/debounce';

const props = defineProps({
    action: Object,
    cmd: String,
    isReadonly: Boolean,
    statusOptions: Array,
});

const form = useForm({
    name: props.action.name || '',
    ordinal: props.action.ordinal || 0,
    status: props.action.status || 'draf',
    is_activated: props.action.is_activated == 1,
});

// Auto-save reaktif mirip Livewire wire:model.live.blur
const autoSave = debounce(() => {
    if (props.isReadonly) return;
    form.put(route('ms-action.update', props.action.id), {
        preserveScroll: true,
        preserveState: true,
    });
}, 500);

const submitStatus = (newStatus) => {
    form.status = newStatus;
    form.put(route('ms-action.update', props.action.id));
};
</script>

<template>
    <Head :title="`Action - ${cmd.toUpperCase()}`" />

    <div class="p-6 max-w-4xl mx-auto space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold tracking-tight">Form Action ({{ cmd.toUpperCase() }})</h1>
            <Button variant="outline" as-child>
                <Link :href="route('ms-action.list')">Kembali ke Daftar</Link>
            </Button>
        </div>

        <!-- Tombol Pengubahan Status Status (Terbit / Draf / Batal) -->
        <div class="flex gap-2">
            <Button @click="submitStatus('terbit')" variant="default" class="bg-emerald-600 hover:bg-emerald-700 text-white">Terbit</Button>
            <Button @click="submitStatus('draf')" variant="secondary">Draf</Button>
            <Button @click="submitStatus('batal')" variant="destructive">Batal</Button>
        </div>

        <Card>
            <CardHeader>
                <CardTitle>Detail Data Action</CardTitle>
            </CardHeader>
            <CardContent class="space-y-4">
                <div class="space-y-2">
                    <Label for="name">Nama Action <span class="text-red-500">*</span></Label>
                    <Input 
                        id="name" 
                        v-model="form.name" 
                        @input="autoSave" 
                        :disabled="isReadonly" 
                        placeholder="Masukkan nama action..." 
                    />
                    <span v-if="form.errors.name" class="text-xs text-red-500">{{ form.errors.name }}</span>
                </div>

                <div class="space-y-2">
                    <Label for="ordinal">Urutan (Ordinal)</Label>
                    <Input 
                        id="ordinal" 
                        type="number" 
                        v-model="form.ordinal" 
                        @input="autoSave" 
                        :disabled="isReadonly" 
                    />
                </div>

                <div class="flex items-center space-x-2 pt-2">
                    <Checkbox 
                        id="is_activated" 
                        v-model:checked="form.is_activated" 
                        @update:checked="autoSave" 
                        :disabled="isReadonly" 
                    />
                    <Label for="is_activated">Status Aktif</Label>
                </div>
            </CardContent>
        </Card>
    </div>
</template>