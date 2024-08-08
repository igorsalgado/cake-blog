<template>
    <div class="post-form">
        <h1>Add Post</h1>
        <form @submit.prevent="handleSubmit">
            <div>
                <label for="title">Title:</label>
                <input type="text" id="title" v-model="form.title" required/>
            </div>

            <div>
                <label for="description">Description:</label>
                <textarea id="description" v-model="form.description" required />
            </div>

            <div>
                <label for="category_id">Category:</label>
                <select id="category_id" v-model="form.category_id" required>
                    <option value="" disabled>Select Category</option>
                    <option v-for="category in categories" :key="category.id" :value="category.id">
                        {{ category.name }}
                    </option>
                </select>
            </div>

            <button type="submit">Add</button>
        </form>

        <p v-if="error" class="error">{{ error }}</p>
    </div>
</template>

<script>
import api from '@/services/api.js';

export default {
    name: 'PostForm',
    data() {
        return {
            form: {
                title: '',
                description: '',
                category_id: null,
                user_id: 1,  // Aqui deveria pegar o user autenticado
            },
            categories: [],
            error: null,
        };
    },
    created() {
        this.fetchCategories();
    },
    methods: {
        async fetchCategories() {
            try {
                const response = await api.get('/categories');
                this.categories = response.data.categories;

            } catch (err) {
                console.error('Error fetching categories:', err);
                this.error = 'Error fetching categories. Please try again.';
            }
        },
        async handleSubmit() {
            try {
                const postData = { ...this.form };

                await api.post('/posts', postData);
                this.$router.push('/');

            } catch (err) {
                console.error('Error saving post:', err);
                this.error = 'Error saving post. Please try again.';
            }
        }
    }
};
</script>

<style scoped>
.post-form {
    max-width: 400px;
    margin: auto;
}

form div {
    margin-bottom: 1rem;
}

label {
    display: block;
    margin-bottom: .5rem;
}

input, textarea, select {
    width: 100%;
    padding: .5rem;
    box-sizing: border-box;
}

button {
    padding: .5rem 1rem;
}

.error {
    color: red;
}
</style>
