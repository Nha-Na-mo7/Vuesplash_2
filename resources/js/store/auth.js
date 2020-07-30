// ==================
// Vuex・Authのストア
// ==================

const state = {
  // ログイン済みのユーザーを保持するuser
  user: null
}

const getters = {}

const mutations = {
  //ミューテーションの第1引数は必ずステートになる。
  //ミューテーションを呼び出すときの実引数は仮引数では第二引数以降として渡されます。
  setUser (state, user) {
    state.user = user
  }
}

const actions = {
  // 会員登録APIを呼び出す
  // 第一引数はcontect。ミューテーションを呼ぶためのcommitメソッドなどが入っている。
  // setUserを使ってuserステートを更新している。
  async register (context, data){
    const response = await axios.post('/api/register', data)
    context.commit('setUser', response.data) //返却データを使ったステート更新
  }
}


export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}

