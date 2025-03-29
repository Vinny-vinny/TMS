import {useAuthStore} from "../store/auth.js";

const AuthenticatedLayout = () => import('../layouts/Authenticated.vue')

function requireLogin(to, from, next) {
    const auth = useAuthStore()
    let isLogin = false;
    isLogin = !!auth.authenticated;

    if (isLogin) {
        next()
    } else {
        next('/login')
    }
}
export default [
    {
        path: '/login',
        name: 'auth.login',
        component: () => import('../pages/auth/Login.vue')
    },

    {
        path: '/',
        component: AuthenticatedLayout,
        beforeEnter: requireLogin,
        children: [
            {
                name: 'home.index',
                path: '',
                component: () => import('../pages/home/Index.vue'),
                meta: { breadCrumb: 'Home' }
            },
            {
                name: 'tasks.index',
                path: 'tasks',
                component: () => import('../pages/tasks/Index.vue'),
                meta: { breadCrumb: 'Tasks' }
            },
            {
                name: 'tasks.create',
                path: 'tasks/create',
                component: ()  => import('../pages/tasks/Create.vue'),
                meta: { breadCrumb: 'Add new task' }
            },
            {
                name: 'tasks.edit',
                path: 'tasks/edit/:id',
                component: () => import('../pages/tasks/Edit.vue'),
                meta: { breadCrumb: 'Edit task' }
            },
            {
                name: 'tasks.view',
                path: 'tasks/view/:id',
                component: () => import('../pages/tasks/View.vue'),
                meta: { breadCrumb: 'View task' }
            }
        ]
    },
    {
        path: "/:pathMatch(.*)*",
        name: 'NotFound',
        component: () => import("../pages/errors/404.vue"),
    },
];
