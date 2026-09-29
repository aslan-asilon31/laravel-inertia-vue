<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Alert, AlertDescription } from '@/components/ui/alert';
import { Loader2 } from 'lucide-vue-next';

// Inisialisasi state form menggunakan Inertia useForm
const form = useForm({
    username: '',
    password: '',
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login - Umedalife" />

    <div class="min-h-screen flex items-center justify-center bg-gray-50 px-4 sm:px-6 lg:px-8">
        <Card class="w-full max-w-md shadow-xl border-gray-100">
            
            <!-- Header Brand -->
            <CardHeader class="text-center space-y-1">
                <CardTitle class="text-4xl font-black tracking-tight bg-gradient-to-r from-amber-900 to-orange-700 bg-clip-text text-transparent">
                    Umedalife
                </CardTitle>
                <CardDescription class="text-xs uppercase tracking-widest font-medium text-gray-500">
                    Internal System
                </CardDescription>
            </CardHeader>

            <CardContent>
                <!-- Pesan Error Validasi -->
                <Alert v-if="form.errors.username" variant="destructive" class="mb-4">
                    <AlertDescription>
                        {{ form.errors.username }}
                    </AlertDescription>
                </Alert>

                <!-- Form Login -->
                <form @submit.prevent="submit" class="space-y-4">
                    <div class="space-y-2">
                        <Label for="username">Username</Label>
                        <Input 
                            id="username"
                            type="text" 
                            v-model="form.username" 
                            required 
                            placeholder="Masukkan username"
                            class="bg-gray-50/50"
                        />
                    </div>

                    <div class="space-y-2">
                        <Label for="password">Password</Label>
                        <Input 
                            id="password"
                            type="password" 
                            v-model="form.password" 
                            required 
                            placeholder="••••••••"
                            class="bg-gray-50/50"
                        />
                    </div>

                    <!-- Tombol Submit menggunakan Shadcn Button -->
                    <Button 
                        type="submit"
                        :disabled="form.processing"
                        class="w-full mt-2 bg-orange-900 hover:bg-orange-800 text-white transition-all duration-200">
                        
                        <span v-if="!form.processing">Masuk ke Akun</span>

                        <span v-else class="flex items-center gap-2">
                            <Loader2 class="h-4 w-4 animate-spin" />
                            Memproses...
                        </span>
                    </Button>
                </form>
            </CardContent>

            <!-- Footer Copyright -->
            <CardFooter class="border-t border-gray-100 pt-4 text-center justify-center">
                <span class="text-xs text-gray-400">&copy; 2026 Umeda</span>
            </CardFooter>

        </Card>
    </div>
</template>