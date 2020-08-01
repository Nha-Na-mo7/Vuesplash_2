<!--===============-->
<!--フッター         -->
<!--===============-->

<!--認証状態によりログアウトorログインページリンクのどちらかが表示される-->
<template>
  <footer class="footer">
    
    <!-- ログアウトボタン -->
    <button v-if="isLogin" class="button button--link" @click="logout">Logout!</button>
    
    <!-- ログインリンク -->
    <RouterLink v-else class="button button--link" to="/">Login / Register</RouterLink>
    
  </footer>
</template>


<script>
import { mapState, mapGetters } from 'vuex'
export default {
  computed: {
    ...mapState({
      apiStatus: state => state.auth.apiStatus
    }),
    ...mapGetters({
      isLogin: 'auth/check'
    })
  },
  methods: {
    async logout() {
      //authストアのlogoutアクションを呼び出す。
      await this.$store.dispatch('auth/logout')

      // ログインページにリダイレクト
      if(this.apiStatus) {
        this.$router.push('/login')
      }
    }
  }
}
</script>
