<template>
    <div v-if="post">
        <h1>{{ post.title }}</h1>
        <p v-if="post.user">Author: {{ post.user.name }}</p>
        <p>{{ post.description }}</p>
        <p v-if="post.category">Category: {{ post.category.name }}</p>

        <div v-if="post.comments && post.comments.length > 0">
            <h2>Comments</h2>
            <ul>
                <li v-for="comment in post.comments" :key="comment.id">
                    <p><strong>{{ comment.name }}</strong>: {{ comment.description }}</p>
                </li>
            </ul>
        </div>
        <p v-else>No comments yet.</p>

        <form @submit.prevent="addComment">
            <input v-model="newComment.name" placeholder="Name" required />
            <textarea v-model="newComment.description" placeholder="Comment" required></textarea>
            <button type="submit">Add Comment</button>
        </form>
    </div>
    <div v-else>
        <p>Loading posts...</p>
    </div>
</template>

<script setup>
import {ref, onMounted} from 'vue';
import {useRoute} from 'vue-router';
import api from '@/services/api.js';

const route = useRoute();
const post = ref(null);
const newComment = ref({
    name: '',
    description: '',
    post_id: route.params.id
});

const fetchPost = async (postId) => {
    try {
        const response = await api.get(`/posts/${postId}`);
        post.value = response.data.post;
    } catch (error) {
        console.error('Error fetching post details:', error);
    }
};

onMounted(() => {
    fetchPost(route.params.id);
});

const addComment = async () => {
    try {
        const response = await api.post(`comments`, newComment.value);
        //console.log(response.data)
        await post.value.comments.push(response.data); // Adiciona o novo comentário à lista
        newComment.value.name = '';
        newComment.value.description = '';
    } catch (error) {
        console.error('Error adding comment:', error);
    }
};
</script>

