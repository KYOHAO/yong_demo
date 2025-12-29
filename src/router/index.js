import { createRouter, createWebHistory } from 'vue-router';
import AdminBoard from '../views/AdminBoard.vue';
import PublicBoard from '../views/PublicBoard.vue';

const routes = [
    {
        path: '/',
        name: 'AdminBoard',
        component: AdminBoard,
    },
    {
        path: '/view',
        name: 'PublicBoard',
        component: PublicBoard,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
