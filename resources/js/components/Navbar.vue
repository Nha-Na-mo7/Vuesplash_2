<!--========================-->
<!--ヘッダー用のナビゲーションバー-->
<!--========================-->

<template>
  
  <nav class="navbar">
    <RouterLink class="navbar__brand" to="/">
      Vuesplash!!!
    </RouterLink>
    
    <div class="navbar__menu">
    
      <!-- 写真投稿ボタン -->
      <div v-if="isLogin" @click="showForm = ! showForm" class="navbar__item">
        <button class="button">
          <i class="icon ion-md-add"></i>
          Submit a PHOTO!
        </button>
      </div>
      
      <!-- ユーザーネーム -->
      <span v-if="isLogin" class="navbar__item">
        {{ username }}
      </span>
      
      <!-- ログインリンク(ログイン済みなら表示されない) -->
      <div v-else class="navbar__item">
        <RouterLink class="button button--link" to="/login">
          ログイン / 会員登録
        </RouterLink>
      </div>
      
    </div>

    <!--インポートしたPhotoForm.vue、showFormをpropsとして渡しているので表示非表示が変わる-->
    <PhotoForm v-model="showForm" />
  </nav>

</template>

<script>
import PhotoForm from "./PhotoForm";

export default {
  components: {
    PhotoForm
  },
  data() {
    return {
      showForm: false
    }
  },
  computed: {
    isLogin() {
      return this.$store.getters['auth/check']
    },
    username() {
      return this.$store.getters['auth/username']
    }
  }
}
</script>
