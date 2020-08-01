import Vue from "vue";
import VueRouter from "vue-router";

// ページコンポーネントをインポートする

import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'
import store from './store'

// VueRouterのプラグインを使用する
// これによって、<RouterView />コンポーネントなどを使用できるようになる。
Vue.use(VueRouter)



// パスとコンポーネントのマッピング
const routes = [
  {
    path: '/',
    component: PhotoList
  },
  {
    path: '/login',
    component: Login,
    beforeEnter (to, from, next) {
      //['auth/check']のゲッターでログイン状態をチェック。
      if(store.getters['auth/check']) {
        //next()の引数に指定したページにリダイレクトするように見える動きになる
        next('/')
      } else {
        //next()引数なしだと、そのままページコンポーネントが作られる
        next()
      }
    }
  }
]

// VueRouterのインスタンスを作成する
const router = new VueRouter({
  mode: 'history', //historyモード。mode's'にしないこと。
  routes
})

// VueRouterインスタンスをエクスポートする。
// app.jsでインポートするため。
export default router;