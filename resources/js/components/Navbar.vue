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
      <div class="navbar__item">
        <button class="button">
          <i class="icon ion-md-add"></i>
          Submit a PHOTO!
        </button>
      </div>
      
      <!-- ユーザーネーム -->
      <span class="navbar__item">
        UserName!!!
      </span>
      
      <!-- ログインリンク(ログイン済みなら表示されない) -->
      <div class="navbar__item">
        <RouterLink class="button button--link" to="/login">
          Login / Register
        </RouterLink>
      </div>
      
    </div>
  </nav>

</template>
