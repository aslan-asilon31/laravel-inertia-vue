import { defineStore } from 'pinia';
import axios from 'axios';

export const usePostStore = defineStore('posts', {
  state: () => ({
    posts: [] as Array<{ id: number; title: string; body: string }>,
    isLoading: false,
    isModalOpen: false,  
    isEditMode: false,  
    currentPost: { id: null, title: '', body: '' } as { id: number | null; title: string; body: string },  // Data post yang sedang diproses
  }),

  actions: {
    async fetchPosts() {
    this.isLoading = true;
    try {
        const response = await axios.get('https://jsonplaceholder.typicode.com/posts');
        console.log('API Response:', response);  

        if (response.data && Array.isArray(response.data)) {
        this.posts = response.data;
        } else {
        console.error('Invalid data structure:', response.data);
        }
        console.log('Fetched Posts:', this.posts);  
    } catch (error) {
        console.error('Error fetching posts:', error);
    } finally {
        this.isLoading = false;
    }
    },


    async createPost(newPost: { title: string; body: string }) {
      try {
        const response = await axios.post('https://jsonplaceholder.typicode.com/posts', newPost);
        this.posts.push(response.data);  
      } catch (error) {
        console.error('Error creating post:', error);
      }
    },

    async updatePost(updatedPost: { id: number; title: string; body: string }) {
      try {
        const response = await axios.put(`https://jsonplaceholder.typicode.com/posts/${updatedPost.id}`, updatedPost);
        const index = this.posts.findIndex(post => post.id === updatedPost.id);
        if (index !== -1) {
          this.posts[index] = response.data;  
        }
        
        this.closeModal();
      } catch (error) {
        console.error('Error updating post:', error);
      }
    },

    async deletePost(postId: number) {
      try {
        await axios.delete(`https://jsonplaceholder.typicode.com/posts/${postId}`);
        this.posts = this.posts.filter(post => post.id !== postId);  
      } catch (error) {
        console.error('Error deleting post:', error);
      }
    },

    openModal(post = null) {
      if (post) {
        this.currentPost = { ...post };  
        this.isEditMode = true;  
      } else {
        this.currentPost = { id: null, title: '', body: '' };  
        this.isEditMode = false;  
      }
      this.isModalOpen = true;  
    },

    closeModal() {
      this.isModalOpen = false;
      this.currentPost = { id: null, title: '', body: '' };  
    },
  },
});
