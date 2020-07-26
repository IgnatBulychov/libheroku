//main
import Login from './components/auth/Login.vue';
import Home from './components/landing/Home.vue';
import BookPage from './components/landing/BookPage.vue';
import BookView from './components/landing/BookView.vue';

//dashboard
import Dashboard from './components/dashboard/Dashboard.vue';
import BooksMain from './components/dashboard/books/Main.vue';
import BooksList from './components/dashboard/books/List.vue';
import NewBook from './components/dashboard/books/New.vue';
import UpdateBook from './components/dashboard/books/Update.vue';
import TagsList from './components/dashboard/tags/List.vue';

export const routes = [    
    {
        path: '/',
        component: Home,
    },
    {
        path: '/books',
        component: BookView,
        children: [
            {
                path: ':id',
                component: BookView
            }
        ]
    },
    {
        path: '/login',
        component: Login
    },
    {
        path: '/dashboard',
        component: Dashboard,
        meta: {
            requiresAuth: true
        },
        children: [
            {
                path: 'tags',
                component: TagsList,
                meta: {
                    requiresAuth: true
                }
            },
            {
                path: 'books',
                component: BooksMain,
                meta: {
                    requiresAuth: true
                },
                children: [
                    {
                        path: '/',
                        component: BooksList
                    },
                    {
                        path: 'new',
                        component: NewBook
                    },
                    {
                        path: ':id',
                        component: UpdateBook
                    }
                ]
            }           
        ]
    },  
    {
        path: '/:filter',
        component: BookPage
       
    }           
];