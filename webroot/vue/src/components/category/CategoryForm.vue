<template>
    <div>
        <h1>Add Category</h1>
        <form @submit.prevent="submitForm">
            <input v-model="category.name" placeholder="Category Name" required />
            <button type="submit">Add</button>
        </form>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import api from '@/services/api.js';
import { useRouter } from 'vue-router';


const router = useRouter();
const category = ref({
    name: '',
});

const submitForm = async () => {
    try {
        await api.post('/categories', category.value);
        await router.push('/');

    } catch (error) {
        console.error('Error saving category:', error);
    }
};
</script>

<style scoped>

</style>
