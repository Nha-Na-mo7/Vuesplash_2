import Vue from 'vue'
import Vuex from 'vuex'

import auth from './auth'

// プラグインとして登録
Vue.use(Vuex)

const store =  new Vuex.Store({
  modules: {
    auth
  }
})


export default store;

