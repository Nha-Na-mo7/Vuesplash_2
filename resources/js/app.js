import './bootstrap'
import Vue from 'vue'
import router from './router'
import store from './store'
import App from './App.vue' // HTML部分の読み込み(App.vueで指定すること)



Vue.component('example-component', require('./components/ExampleComponent.vue').default);


const createApp = async () =>{
  // Vueインスタンス作成前にstoreのアクションを呼び出す
  await store.dispatch('auth/currentUser')
  
  new Vue({
    el: '#app',
    router, //ルーティングの定義を読み込み
    store, // ストア
    components: { App }, //ルートコンポーネントの使用を宣言
    template: '<App />' //ルートコンポーネントを描画する
  })
};
createApp();