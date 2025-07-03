<script setup lang="ts">
import { computed, watch, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';  // Inertia helper untuk mengakses props
import { usePostStore } from '@/stores/posts';  // Mengimpor Pinia store
import { type BreadcrumbItem } from '@/types';
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';

const postStore = usePostStore();
const { posts } = usePage().props;

onMounted(async () => {
  await postStore.fetchPosts();  
});

const searchQuery = computed(() => {
  const searchParams = new URLSearchParams(window.location.search);
  return searchParams.get('search') || '';
});

const filteredPosts = computed(() => {
  if (!searchQuery.value) return postStore.posts;  
  return postStore.posts.filter(post => post.title.toLowerCase().includes(searchQuery.value.toLowerCase()));
});

const confirmDelete = (postId: number) => {
  postStore.deletePost(postId);
};

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Post',
    href: '/post',
  },
];
</script>

<template>
  <Head title="Post" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="grid auto-rows-min gap-4 md:grid-cols-3">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search posts..."
          class="px-3 py-2 border border-gray-300 rounded-md"
        />
      </div>

      <!-- Tabel Post -->
      <table v-if="filteredPosts.length" class="min-w-full table-auto border-collapse border border-gray-300">
        <thead>
          <tr class="bg-gray-100">
            <th class="px-4 py-2 text-left">Title</th>
            <th class="px-4 py-2 text-left">Body</th>
            <th class="px-4 py-2 text-left">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="post in filteredPosts" :key="post.id" class="border-b hover:bg-gray-50">
            <td class="px-4 py-2">{{ post.title }}</td>
            <td class="px-4 py-2">{{ post.body }}</td>
            <td class="px-4 py-2 space-x-2">
              <!-- Tombol Edit membuka modal -->
              <button @click="postStore.openModal(post)" class="px-3 py-1 bg-green-500 text-white rounded-md hover:bg-green-600">Edit</button>
              <button
                @click="confirmDelete(post.id)"
                class="ml-2 px-3 py-1 bg-red-500 text-white rounded-md hover:bg-red-600"
              >
                Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- Jika tidak ada posts -->
      <div v-else class="text-center text-gray-500">No posts available.</div>

      <!-- Tombol untuk menambah post membuka modal -->
      <button @click="postStore.openModal()" class="mt-6 px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
        Add Post
      </button>
    </div>
  </AppLayout>

  <!-- Modal Form Create / Edit Post -->
  <div v-if="postStore.isModalOpen" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
      <h2 class="text-xl font-semibold mb-4">{{ postStore.isEditMode ? 'Edit Post' : 'Create Post' }}</h2>

      <form @submit.prevent="postStore.isEditMode ? postStore.updatePost(postStore.currentPost) : postStore.createPost(postStore.currentPost)">
        <div class="mb-4">
          <label for="title" class="block">Title</label>
          <input v-model="postStore.currentPost.title" id="title" type="text" class="w-full px-3 py-2 border rounded-md" required />
        </div>

        <div class="mb-4">
          <label for="body" class="block">Body</label>
          <textarea v-model="postStore.currentPost.body" id="body" class="w-full px-3 py-2 border rounded-md" required></textarea>
        </div>

        <div class="flex justify-end space-x-2">
          <button type="button" @click="postStore.closeModal" class="px-4 py-2 bg-gray-400 text-white rounded-md">Cancel</button>
          <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
            {{ postStore.isEditMode ? 'Update' : 'Create' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
