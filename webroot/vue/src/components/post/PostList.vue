<template>
    <div>
        <h1>Post List</h1>
        <ul>
            <li v-for="post in posts" :key="post.id">
                <router-link :to="`/post/${post.id}`">
                    <h2>{{ post.title }}</h2>
                    <p>{{ post.description }}</p>
                  <p>Category: {{ post.category.name }}</p>
                </router-link>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import api from '@/services/api.js';

const posts = ref([]);

onMounted(async () => {
    try {
        const response = await api.get('/posts');
        //console.log('Posts response:', response.data);
        posts.value = response.data.posts;
    } catch (error) {
        console.error('Error fetching posts:', error);
    }
});
</script>
