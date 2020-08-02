// ==================
// Vuex・Messageのストア
// ==================


// ==================
// ステート
// ==================
const state = {
  content: ''
}





// ==================
// ゲッター
// ==================
const getters = {

}

// ==================
// ミューテーション
// ==================
const mutations = {
  setContent(state, { content, timeout }) {
    state.content = content
    
    if(typeof timeout === 'undefined') {
      timeout = 3000
    }
    
    setTimeout(() => (state.content = ''), timeout)
  }
}

// ==================
// アクション
// ==================



export default {
  namespaced: true,
  state,
  mutations
}