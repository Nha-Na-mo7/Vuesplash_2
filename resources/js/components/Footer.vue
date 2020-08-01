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
export default {
  methods: {
    async logout() {
      //authストアのlogoutアクションを呼び出す。
      await this.$store.dispatch('auth/logout')

      // ログインページにリダイレクト
      this.$router.push('/login')
    }
  },
  computed: {
    isLogin() {
      return this.$store.getters['auth/check']
    }
  }
}
</script>
