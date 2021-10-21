import axios from "axios";
import {getUID} from "@/helpers";


export const questionItemModule = {
    state:{
      question:{}, answers:[],
      isQuestionPageLoading:false

    },
    getters:{
        questionBody(state){
            return state.question;
        },
        isQuestionPageLoading(state){
            return state.isQuestionPageLoading;
        },
        getAnswers(state){
            return state.answers;
        }

    },
    mutations:{
        setQuestionBody(state, question) {
            state.question = question;
        },
        setLoading(state, bool) {
            state.isQuestionPageLoading = bool
        },
        setAnswers(state, answers) {
            state.answers = answers;
        },
        incrementAnswersCount(state){
            state.question.answersCount++;
        }
    },
    actions:{
        async fetchQuestionBody(state,router) {
            try {
                state.commit('setLoading', true);
                const response = await axios.get(`http://192.168.0.103//questions/${router}`);
                state.commit('setQuestionBody', response.data)
            } catch (e) {
                console.log(e);
            } finally {
                state.commit('setLoading', false);
            }
        },
        async fetchAnswers({commit},question_id) {
            try {
                commit('setLoading', true);
                const response = await axios.get('http://192.168.0.103//answers', {
                    params: {
                        q_id: question_id,
                        u_id: getUID() ?? 0
                    }
                });
                console.log('answer',response.data)
                commit('setAnswers', response.data.records)
            } catch (e) {
                console.log(e);
                commit('setAnswers',[]);

            } finally {
                commit('setLoading', false);
            }
        },

        async addAnswer({commit,dispatch},data) {
            commit('setLoading', true);
            let formData = new FormData();
            formData.append("user_id", getUID());
            formData.append("question_id",data.question_id);
            formData.append("title",data.title);
            try {
                await axios.post('http://192.168.0.103/answers',formData, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'multipart/form-data'
                    }
                });

                commit('incrementAnswersCount');
                dispatch("fetchAnswers",data.question_id);

            } catch (e) {
                console.log(e);
            } finally {
                commit('setLoading', false);
            }
        },

        async likeAnswer({commit,dispatch},answer) {
            commit('setLoading', true);
            let formData = new FormData();
            formData.append("user_id", getUID());
            formData.append("answer_id",answer.id);

            try {
               await axios.post('http://192.168.0.103//answers/like',formData, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'multipart/form-data'
                    }
                });
                dispatch("fetchAnswers",answer.question_id);

            } catch (e) {
                console.log(e);
            } finally {
                commit('setLoading', true);
            }
        },

        async unlikeAnswer({commit,dispatch},answer) {
            commit('setLoading', true);
            let formData = new FormData();
            formData.append("user_id", getUID());
            formData.append("answer_id",answer.id);

            try {
                await axios.post('http://192.168.0.103//answers/unlike',formData, {
                    headers: {
                        Accept: 'application/json',
                        'Content-Type': 'multipart/form-data'
                    }
                });
                dispatch("fetchAnswers",answer.question_id);
            } catch (e) {
                console.log(e);
            } finally {
                commit('setLoading', true);
            }
        },

    }
}