import axios from "axios";

function saveData(jwt,ROLE,uid){
    localStorage.setItem("jwt",jwt);
    localStorage.setItem("ROLE",ROLE);
    localStorage.setItem("uid",uid);
}

function clearData(){
    localStorage.removeItem("jwt");
    localStorage.removeItem("ROLE");
    localStorage.removeItem("uid");
}


export const userModule = {
    state: () => ({
        user: {},
        isAuth: false,
        isUserLoading: false,
        usersList:[],
        error:""
    }),
    getters: {
        getUserInfo(state){
            return state.user;
        },
        getUsersList(state){
            return state.usersList;
        },

        getUserError(state){
            return state.error;
        },
        getIsUserAuth(state){
            return state.isAuth;
        },
        getIsUserLoading(state){
            return state.isUserLoading;
        },
    },
    mutations: {
        setLoading(state, bool) {
            state.isUserLoading = bool;
        },
        setUserInfo(state, userInfo) {
            state.user = userInfo;
        },
        setUsersList(state, users) {
            state.usersList = users;
        },
        setIsAuth(state, bool) {
            state.isAuth = bool;
        },
        setError(state,error){
            state.error = error;
        }

    },
    actions: {
        async fetchUsersList(state) {
            try {
                state.commit('setLoading', true);
                const response = await axios.get(`http://192.168.0.103/users/`);
                state.commit('setUsersList', response.data.records)
            } catch (e) {
                console.log(e);
                state.commit('setUsersList', []);
            } finally {
                state.commit('setLoading', false);
            }
        },
        async doLogin(state,data) {
            state.commit('setLoading', true);
            let formData = new FormData();
            formData.append("email",data.email);
            formData.append("password",data.password);
            try {
                const response = await axios.post('http://192.168.0.103/users/login',formData, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'multipart/form-data'
                    }
                });

                state.commit('setUserInfo', response.data.user);
                state.commit("setIsAuth",true);
                state.commit("setError","");

                saveData(response.data.jwt,response.data.user.ROLE,response.data.user.id);


            } catch (e) {
                state.commit("setError",e.response.data.message);
            } finally {
                state.commit('setLoading', false);
            }
        },

        doLogout(state) {
            clearData();
            state.commit("setIsAuth",false);
            state.commit('setUserInfo', {});

        },
        async doRegistration(state,data) {
            state.commit('setLoading', true);
            let formData = new FormData();
            formData.append("name",data.name);
            formData.append("email",data.email);
            formData.append("image",data.image);
            formData.append("password",data.password);
            try {
                await axios.post('http://192.168.0.103/users/register',formData, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'multipart/form-data'
                    }
                });
                state.commit("setError","");
            } catch (e) {
                state.commit("setError",e.response.data.message);
            } finally {
                state.commit('setLoading', false);
            }
        },

        async validateAuth({commit}){
            try {
                commit('setLoading', true);
                let jwt = localStorage.getItem("jwt");
                if(!jwt){
                    commit('setUserInfo',[]);
                    commit('setIsAuth',false);

                }else{
                    const response = await axios.get(`http://192.168.0.103/users/`,{
                        params:{
                            validate:jwt
                        }
                    });
                    commit('setUserInfo', response.data.data)
                    commit('setIsAuth',true);
                }

            } catch (e) {
                console.log(e);
                commit('setUserInfo',[]);
                commit('setIsAuth',false);
                localStorage.removeItem('jwt');
            } finally {
                commit('setAppReady', true, { root: true })
                commit('setLoading', false);
            }
        }
    }
}

export default userModule;