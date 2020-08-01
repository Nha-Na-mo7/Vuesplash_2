// ==================
// Vuex・Authのストア
// ==================

const state = {
  // ログイン済みのユーザーを保持するuser
  user: null
}

const getters = {
  check:  state => !! state.user, //二重否定は確実に真偽値を返却させるため。
  username: state => state.user ? state.user.name : '' //userがnullだったとしても空文字が帰る
}


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
  },
  
  //ログインフォームによるログイン
  async login (context, data) {
    const response = await axios.post('/api/login', data)
    context.commit('setUser', response.data)
  },
  
  //ログアウトの場合。ストアのuser情報をnullにしている。
  async logout (context) {
    const response = await axios.post('/api/logout')
    context.commit('setUser', null)
  },
  
  // ログインユーザー維持のAPI
  async currentUser(context) {
    const response = await axios.get('/api/user')
    const user = response.data || null
    context.commit('setUser', user)
  }
  
  
}


export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}

