// ==================
// Vuex・Authのストア
// ==================
import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'




// ==================
// ステート
// ==================
const state = {
  // ログイン済みのユーザーを保持するuserステート
  user: null,
  // ログイン済みかどうかを保持するapiStatesステート、bool
  apiStates: null,
  loginErrorMessages: null,
  registerErrorMessages: null
}





// ==================
// ゲッター
// ==================
const getters = {
  check:  state => !! state.user, //二重否定は確実に真偽値を返却させるため。
  username: state => state.user ? state.user.name : '' //userがnullだったとしても空文字が帰る
}





// ==================
// ミューテーション
// ==================
const mutations = {
  //ミューテーションの第1引数は必ずステートになる。
  //ミューテーションを呼び出すときの実引数は仮引数では第二引数以降として渡されます。
  setUser (state, user) {
    state.user = user
  },
  setApiStates(state, status) {
    state.apiStates = status
  },
  setLoginErrorMessages(state, messages) {
    state.loginErrorMessages =  messages
  },
  setRegisterErrorMessages(state, messages) {
    state.registerErrorMessages =  messages
  }
}





// ==================
// アクション
// ==================
const actions = {
  // 会員登録APIを呼び出す
  // 第一引数はcontect。ミューテーションを呼ぶためのcommitメソッドなどが入っている。
  // setUserを使ってuserステートを更新している。
  async register (context, data){
    
    context.commit('setApiStates', null)
    const response = await axios.post('/api/register', data)
  
    if (response.status === CREATED) {
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data) //返却データを使ったステート更新
      
      return false
    }
  
    //apiStatusの通信結果、失敗はfalse
    context.commit('setApiStatus', false)
    // ステータスコードでバリデーションエラーの時の分岐
    if(response.status === UNPROCESSABLE_ENTITY) {
      //ルートコンポーネントには制御を渡さないのでerror/setCodeは呼ばない。
      context.commit('setRegisterErrorMessages', response.data.errors)
    }else{
      //通信失敗時にsetCodeミューテーションをcommitしている。root:trueが必要。
      context.commit('error/setCode', response.status, {root: true})
    }
  
  },
  
  //ログインフォームによるログイン
  async login (context, data) {
    context.commit('setApiStatus', null)
    const response = await axios.post('/api/login', data)
    
    if(response.status === OK) {
      //apiStatusの通信結果、成功はtrue
      context.commit('setApiStatus', true)
      context.commit('setUser', response.data)
  
      return false
    }
  
    //apiStatusの通信結果、失敗はfalse
    context.commit('setApiStatus', false)
    // ステータスコードでバリデーションエラーの時の分岐
    if(response.status === UNPROCESSABLE_ENTITY) {
      //ルートコンポーネントには制御を渡さないのでerror/setCodeは呼ばない。
      context.commit('setLoginErrorMessages', response.data.errors)
    }else{
      //通信失敗時にsetCodeミューテーションをcommitしている。root:trueが必要。
      context.commit('error/setCode', response.status, {root: true})
    }
    
  },
  
  //ログアウトの場合。ストアのuser情報をnullにしている。
  async logout (context) {
    context.commit('setApiStates', null)
    const response = await axios.post('/api/logout')
    
    if(response.status ===  OK) {
      context.commit('setApiStates', true)
      context.commit('setUser', null)
      return false
    }
    
    context.commit('setApiStates', false)
    context.commit('error/setCode', response.status, { root: true })
    
  },
  
  // ログインユーザー維持のAPI
  async currentUser(context) {
    context.commit('setApiStates', true)
    const response = await axios.get('/api/user')
    const user = response.data || null
    
    if(response.status === OK) {
      context.commit('setApiStates', true)
      context.commit('setUser', user)
      return false
    }
    
    context.commit('setApiStates', true)
    context.commit('error/setCode', response.status, { root: true})
  }
}


export default {
  namespaced: true,
  state,
  getters,
  mutations,
  actions
}

