// ==================
// Vuex・ルートストア
// ==================



import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'
import message from "./message";

// プラグインとして登録
Vue.use(Vuex)

const store =  new Vuex.Store({
  modules: {
    auth,
    error,
    message
  }
})


export default store

