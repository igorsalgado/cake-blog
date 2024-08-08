<template>
    <div>
        <h2>Login</h2>
        <form @submit.prevent="login">
            <input v-model="email" type="email" placeholder="Email" required />
            <input v-model="password" type="password" placeholder="Password" required />
            <button type="submit">Login</button>
            <p v-if="errorMessage" class="error">{{ errorMessage }}</p>
        </form>
    </div>
</template>

<script>
import api from '@/services/api.js';

export default {
    data() {
        return {
            email: '',
            password: '',
            errorMessage: '',
        };
    },
    methods: {
        async login() {
            try {
                const response = await api.post('/login', {
                    email: this.email,
                    password: this.password,
                });
                this.$router.push('/');
            } catch (error) {
                this.errorMessage = 'Login failed. Please check your credentials.';
                console.error('Error logging in:', error);
            }
        },
    },
};
</script>

<style scoped>
.error {
    color: red;
}
</style>
