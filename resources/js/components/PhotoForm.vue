<!--========================-->
<!--  写真送信用のフォーム      -->
<!--========================-->

<template>

  <div v-show="value" class="photo-form">
    <h2 class="title">Submit a Photo!!!</h2>

    <div v-show="loading" class="panel">
      <Loader>Sending your photo ... ...</Loader>
    </div>

    <form v-show="! loading" class="form" @submit.prevent="submit">

      <!--エラーメッセージ-->
      <div class="errors" v-if="errors">
        <ul v-if="errors">
          <li v-for="msg in errors.photo" :key="msg">{{ msg }}</li>
        </ul>
      </div>

      <input type="file" class="form__item" @change="onFileChange">
      <!--プレビュー表示領域-->
      <output class="form__output" v-if="preview">
        <img :src="preview" alt="">
      </output>
      <!--送信ボタン-->
      <div class="form__button">
        <button type="submit" class="button button__inverse">SUBMIT!</button>
      </div>
    </form>
  </div>

</template>

<script>
import { CREATED, UNPROCESSABLE_ENTITY } from "../util";
import Loader from './Loader.vue'

export default {
  components: {
    Loader
  },
  props: {
    value: {
      type: Boolean,
      required: true
    }
  },
  data() {
    return {
      loading: false,
      preview: null,
      photo: null,
      errors: null
    }
  },
  methods: {
    // フォームでファイルが選択されたら実行される
    onFileChange(event) {

      // 何も選択されていなかった場合、処理を中断する
      if(event.target.files.length === 0) {
        this.reset()
        return false
      }

      // ファイルが画像ではなかった場合、処理を中断する
      if(! event.target.files[0].type.match('image.*')){
        this.reset()
        return false
      }

      // FileRenderクラスのインスタンスを取得する
      const reader = new FileReader()


      // ファイルを読み込み終わったタイミングで実行する処理
      reader.onload = e => {
        // previewに読み込み結果(データURL)を代入する
        // previewに値が入ると、<output>に付与したv-ifがtrue判定になる
        // また、<output>内部の<img>のsrc属性はpreviewの値を参照しているので、結果として画像が表示される
        this.preview = e.target.result
      }

      // ファイルを読み込む
      // 読み込まれたファイルはデータURL形式で受け取れる(上記onloadを参照)
      reader.readAsDataURL(event.target.files[0])

      this.photo = event.target.files[0]
    },
    // 入力欄の値とプレビュー表示をクリアするメソッド。これを各所で使う。
    // this.$elはコンポーネントそのもののDOM要素のこと
    reset() {
      this.preview = ''
      this.photo = null
      this.$el.querySelector('input[type="file"]').value = null
    },
    async submit() {
      // ローディング開始
      this.loading = true

      // 通信処理
      const formData = new FormData()
      formData.append('photo', this.photo)
      const response = await axios.post('api/photos', formData)

      // ローディング終了
      this.loading = false

      if(response.status ===  UNPROCESSABLE_ENTITY) {
        this.errors = response.data.errors
        return false
      }

      this.reset()
      this.$emit('input', false)

      if(response.status !== CREATED) {
        this.$store.commit('error/setCode', response.status)
        return false
      }

      // フラッシュメッセージの登録
      this.$store.commit('message/setContent', {
        content: '写真は無事に投稿されました',
        timeout: 5000
      })

      //リダイレクト
      this.$router.push('/photos/${response.data.id}')
    }
  }
}
</script>