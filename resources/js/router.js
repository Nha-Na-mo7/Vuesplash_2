import Vue from "vue";
import VueRouter from "vue-router";

// ページコンポーネントをインポートする

import PhotoList from './pages/PhotoList.vue'
import Login from './pages/Login.vue'

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
    component: Login
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