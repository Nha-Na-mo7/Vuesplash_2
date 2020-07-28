<!--===============-->
<!--フッター         -->
<!--===============-->

<!--認証状態によりログアウトorログインページリンクのどちらかが表示される-->
<template>
  <footer class="footer">
    
    <!-- ログアウトボタン -->
    <button class="button button--link">Logout!</button>
    
    <!-- ログインリンク -->
    <RouterLink class="button button--link" to="/">Login / Register</RouterLink>
    
  </footer>
</template>
