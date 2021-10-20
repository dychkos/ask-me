import axios from "axios";
import {getUID} from "@/helpers";

export const questionsModule = {
    state: () => ({
        questions: [],
        isQuestionsLoading: false,
        selectedSort: 'date',
        searchQuery: '',
        selectedCategory: 'All questions',
        page: 1,
        sortOptions: [
            {value: 'name', name: 'name'},
            {value: 'popular', name: 'popular'},
            {value: 'date', name: ' date'},
        ]
    }),
    getters: {
        sortedQuestions(state) {
            switch (state.selectedSort) {
                case "title":{
                    return [...state.questions].sort((question1, question2) => question1[state.selectedSort]?.localeCompare(question2[state.selectedSort]));
                }
                case "popular":{
                    return [...state.questions].sort((q1,q2)=>q2.answersCount-q1.answersCount);

                }
                case "date":{
                    return [...state.questions].sort((q1,q2)=>new Date(q2.createdAt) - new Date(q1.createdAt));
                }
                default:{
                    return state.questions;
                }

            }
        },
        getCurrentSort(state){
            return state.selectedSort;
        },
        allQuestions(state){
            return state.questions;
        },
        searchQuery(state){
            return state.searchQuery;
        },
        isQuestionsLoading(state){
            return state.isQuestionsLoading;
        },
        selectItems(state){
            return state.sortOptions;
        },
        getSelectedCategory(state){
            return state.selectedCategory;
        }
    },
    mutations: {
        setQuestions(state, questions) {
            state.questions = questions;
        },
        setLoading(state, bool) {
            state.isQuestionsLoading = bool
        },
        setPage(state, page) {
            state.page = page
        },
        setSelectedSort(state, selectedSort) {
            state.selectedSort = selectedSort;
        },
        setSearchQuery(state, searchQuery) {
            state.searchQuery = searchQuery
        },
        setSelectedCategory(state, selectedCategory) {
            state.selectedCategory = selectedCategory
        },
        toInitialQuestion(state) {
            state.searchQuery="";
            state.selectedSort="date";
            state.selectedCategory="All questions";
        }
    },
    actions: {
        async fetchQuestions({commit}) {
                commit('toInitialQuestion');
            try {
                commit('setLoading', true);
                commit('setPage',1);
                const response = await axios.get('http://192.168.0.103/questions', {
                    params: {
                        page: 1,
                    }
                });
                commit('setQuestions', response.data.records)
            } catch (e) {
                console.log(e)
            } finally {
                commit('setLoading', false);
            }
        },
        async loadMoreQuestions({state, commit}) {
            try {
                commit('setPage', state.page + 1)
                const response = await axios.get('http://192.168.0.103/questions', {
                    params: {
                        page: state.page
                    }
                });
                commit('setQuestions', [...state.questions, ...response.data.records]);
            } catch (e) {
                commit('setPage', state.page - 1)
            }
        },
        async searchQuestions({state, commit}) {
            commit('setLoading', true);
            try {
                const response = await axios.get('http://192.168.0.103/questions', {
                    params: {
                        search: state.searchQuery
                    }
                });
                commit('setQuestions',response.data.records);
            } catch (e) {
                commit("setQuestions",[]);
                console.log(e);
            }finally {
                commit('setLoading', false);
            }
        },

        async fetchQuestionsByCategory({state, commit}) {
            commit('setLoading', true);
            try {
                const response = await axios.get('http://192.168.0.103/questions', {
                    params: {
                        category: state.selectedCategory
                    }
                });
                commit('setQuestions',response.data.records);
            } catch (e) {
                commit("setQuestions",[]);
                console.log(e);
            }finally {
                commit('setLoading', false);
            }
        },

        async fetchQuestionsForCurrentUser({commit}) {
            commit('setLoading', true);
            try {
                const response = await axios.get('http://192.168.0.103/questions', {
                    params: {
                        user:  getUID()
                    }
                });
                commit('setQuestions',response.data.records);
            } catch (e) {
                commit("setQuestions",[]);
                console.log(e);
            }finally {
                commit('setLoading', false);
            }
        },
        
        async addQuestion(state,data) {
            state.commit('setLoading', true);
            let formData = new FormData();
            formData.append("user_id", getUID());
            formData.append("category_id",data.category_id);
            formData.append("title",data.title);
            formData.append("description",data.description);
            formData.append("isDraft",Number(data.isDraft));

            try {
                 await axios.post('http://192.168.0.103/questions',formData, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'multipart/form-data'
                    }
                });
            } catch (e) {
                console.log(e);
                throw e;
            } finally {
                state.commit('setLoading', false);
            }
        },

        async removeQuestion(state,question_id) {
            state.commit('setLoading', true);
            try {
                await axios.delete(`http://192.168.0.103/questions/${question_id}`);
                state.dispatch("fetchQuestionsForCurrentUser");

            } catch (e) {
                console.log(e);
                throw e;
            } finally {
                state.commit('setLoading', false);
            }
        },
        async makeQuestionPublic(state,question_id) {
            state.commit('setLoading', true);
            let formData = new FormData();
            formData.append("make_public", question_id);
            try {
               await axios.post('http://192.168.0.103/questions',formData, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'multipart/form-data'
                    }
                });
                state.dispatch("fetchQuestionsForCurrentUser");
            } catch (e) {
                console.log(e);
                throw e;
            } finally {
                state.commit('setLoading', false);
            }
        },

    }
}

export default questionsModule;