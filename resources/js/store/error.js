// ==================
// Vuex・errorのストア
// ==================
const state = {
  // エラーコードを保持するcodeステート
  code: null
}

const getters = {

}

const mutations = {
  setCode (state, code) {
    state.code = code
  }
}

const actions = {

}


export default {
  namespaced: true,
  state,
  mutations
}