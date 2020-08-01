// ==================
// Vuex・ルートストア
// ==================



import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'
import error from './error'

// プラグインとして登録
Vue.use(Vuex)

const store =  new Vuex.Store({
  modules: {
    auth,
    error
  }
})


export default store

