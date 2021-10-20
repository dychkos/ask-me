import {createStore} from "vuex";
import QuestionModule from "./questions"
import {questionItemModule} from "@/store/questionItem";
import {userModule} from "@/store/user";
import categoriesModule from "@/store/categories";

export default createStore({
    modules: {
        questions: QuestionModule,
        questionItem:questionItemModule,
        categories:categoriesModule,
        user:userModule

    }
})