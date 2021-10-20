import { createWebHistory, createRouter } from "vue-router";
import Main from "../pages/Main";
import QuestionPage from "@/pages/QuestionPage";
import Login from "@/pages/Login";
import Registration from "@/pages/Registration";
import UserProfile from "@/pages/UserProfile";
import AskQuestion from "@/pages/AskQuestion";
import AdminPanel from "@/pages/AdminPanel";

const routes = [
    {
        path: "/",
        name: "Home",
        component: Main,
    },
    {
        path:"/question/:id",
        name:"Question",
        component: QuestionPage
    },
    {
        path:"/login",
        meta:{withoutAuth:true},
        name:"Login",
        component: Login
    },
    {
        path:"/registration",
        meta:{withoutAuth:true},
        name:"Registration",
        component: Registration
    },
    {
        path:"/user-profile",
        meta:{auth:true},
        name:"UserProfile",
        component: UserProfile
    },
    {
        path:"/ask-question",
        name:"AskQuestion",
        meta:{auth:true},
        component: AskQuestion
    },
    {
        path:"/admin-panel",
        meta:{auth:true,admin:"true"},
        name:"AdminPanel",
        component: AdminPanel
    }

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to,from,next)=> {

    let requireAuth = to.matched.some(record=>record.meta.auth);
    let needAdmin = to.matched.some(record=>record.meta.admin);
    if(requireAuth && localStorage.getItem('jwt') == null ){
        next("/login");
    }else if(needAdmin && localStorage.getItem('ROLE') !== "ADMIN"){
        next("/");
    }else {
        next();
    }

})
// router.beforeEach((to, from, next) => {
//     const currentUser =  Store.getters.getIsUserAuth;
//     const requiresAuth = to.meta.auth;
//
//     if (requiresAuth && !currentUser) {
//         const loginpath = window.location.pathname;
//         next({ name: 'Login', query: { from: loginpath } });
//     } else if (!requiresAuth && currentUser) {
//         next("/");
//     } else {
//         next();
//     }
// });

export default router;