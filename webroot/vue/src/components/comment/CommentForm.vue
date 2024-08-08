<template>
    <div>
        <h1>Add Comment</h1>
        <form @submit.prevent="submitForm">
            <div>
                <label for="name">Name:</label>
                <input
                    id="name"
                    v-model="comment.name"
                    placeholder="Name"
                    required
                />
            </div>
            <div>
                <label for="description">Comment:</label>
                <textarea
                    id="description"
                    v-model="comment.description"
                    placeholder="Comment"
                    required
                ></textarea>
            </div>
            <button type="submit">Add</button>
        </form>
    </div>
</template>


<script setup>
import { ref } from 'vue';
import api from '@/services/api.js';
import { useRoute, useRouter } from 'vue-router';

const route = useRoute();
const router = useRouter();

const comment = ref({
    name: '',
    description: '',
    post_id: route.params.id // pega o ID da rota
});

const submitForm = async () => {
    try {
        await api.post('/comments', comment.value);
        await router.push(`/post/${comment.value.post_id}`); // Redireciona para o post associado
    } catch (error) {
        console.error('Error saving comment:', error);
    }
};
</script>


<style scoped>

</style>
