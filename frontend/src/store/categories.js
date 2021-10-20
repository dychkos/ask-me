import axios from "axios";

export const categoriesModule = {
    state: () => ({
        categories:[],
        isCategoriesLoading:false,
        error:''

    }),
    getters: {
        getCategories(state){
            return state.categories;
        },
        isCategoriesLoading(state){
            return state.isCategoriesLoading;
        },
        getCategoriesError(state){
            return state.error;
        },

    },
    mutations: {
        setCategories(state, categories) {
            state.categories = categories;
        },
        setLoading(state, bool) {
            state.isCategoriesLoading = bool;
        },
        setCategoriesError(state,error){
            state.error = error;
        }
    },
    actions: {
        async fetchCategories({commit}) {
            try {
                commit('setLoading', true);
                const response = await axios.get('http://192.168.0.103/categories');
                commit('setCategories', response.data.records)
            } catch (e) {
                console.log(e)
            } finally {
                commit('setLoading', false);
            }
        },
        async addCategory({commit,dispatch},data) {
            try {
                let formData = new FormData();
                formData.append("title",data)
                commit('setLoading', true);
                await axios.post('http://192.168.0.103/categories',formData);
                commit("setError","");
                dispatch("fetchCategories");
            } catch (e) {
                commit("setError",e.response.data.message);
            } finally {
                commit('setLoading', false);
            }
        },

        async deleteCategory({commit,dispatch},category_id) {
            try {
                commit('setLoading', true);
                await axios.delete(`http://192.168.0.103/categories/${category_id}`,{

                });
                commit("setCategoriesError","");
                dispatch("fetchCategories");
            } catch (e) {
                console.log(e)
                commit("setCategoriesError","Something went wrong");
            } finally {
                commit('setLoading', false);
            }
        },
    }
}

export default categoriesModule;