<template>
    <div class="register-form">
        <h1>Register</h1>
        <form @submit.prevent="handleSubmit">
            <div>
                <label for="name">Name:</label>
                <input type="text" id="name" v-model="form.name" required />
            </div>

            <div>
                <label for="email">Email:</label>
                <input type="email" id="email" v-model="form.email" required />
            </div>

            <div>
                <label for="password">Password:</label>
                <input type="password" id="password" v-model="form.password" required />
            </div>

            <div>
                <label for="passwordConfirmation">Confirm Password:</label>
                <input type="password" id="passwordConfirmation" v-model="form.passwordConfirmation" required />
            </div>

            <button type="submit">Register</button>
        </form>

        <p v-if="error" class="error">{{ error }}</p>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import api from '@/services/api.js';

const form = ref({
    name: '',
    email: '',
    password: '',
    passwordConfirmation: ''
});
const error = ref(null);
const router = useRouter();

const handleSubmit = async () => {

    if (form.value.password !== form.value.passwordConfirmation) {
        error.value = 'Passwords do not match';
        return;
    }

    try {
        await api.post('/register', {
            name: form.value.name,
            email: form.value.email,
            password: form.value.password
        });
        router.push('/login');
    } catch (err) {
        error.value = 'Registration failed. Please try again.';
    }
};
</script>

<style scoped>
.register-form {
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

input {
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
