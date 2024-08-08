import { createRouter, createWebHistory } from 'vue-router';
import PostList from '@/components/post/PostList.vue';
import PostDetail from '@/components/post/PostDetail.vue';
import PostForm from '@/components/post/PostForm.vue';
import Login from '@/components/user/Login.vue';
import RegisterForm from '@/components/user/RegisterForm.vue';
import CategoryForm from '@/components/category/CategoryForm.vue';

const routes = [
    {
        path: '/',
        name: 'PostList',
        component: PostList,
    },
    {
        path: '/login',
        name: 'Login',
        component: Login,
    },
    {
        path: '/register',
        name: 'RegisterForm',
        component: RegisterForm,
    },
    {
        path: '/post/new',
        name: 'PostForm',
        component: PostForm,
    },
    {
        path: '/category/new',
        name: 'CategoryForm',
        component: CategoryForm,
    },
    {
        path: '/post/:id',
        name: 'PostDetail',
        component: PostDetail,
    },

    ];

    const router = createRouter({
        history: createWebHistory(),
        routes,
    });

    export default router;
