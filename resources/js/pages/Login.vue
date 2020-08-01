<template>
  <div class="container--small">
    
    <!-- 切り替えタブ -->
    <ul class="tab">
      <li class="tab__item"
          v-bind:class="{'tab__item--active' : tab === 1 }"
          v-on:click="tab = 1"
          >Login</li>
      <li class="tab__item"
          v-bind:class="{'tab__item--active' : tab === 2 }"
          v-on:click="tab = 2"
          >Register</li>
    </ul>
    
    
    <!-- ログインフォーム -->
    <div class="panel" v-show="tab === 1">
      <form class="form" @submit.prevent="login">

        <!-- エラーメッセージ欄 -->
        <div class="errors" v-if="loginErrors">
          <ul v-if="loginErrors.email">
            <li v-for="msg in loginErrors.email" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="loginErrors.password">
            <li v-for="msg in loginErrors.password" :key="msg">{{ msg }}</li>
          </ul>
        </div>

        <!--email-->
        <label for="login-email">Email</label>
        
        <input type="text" class="form__item" id="login-email" v-model="loginForm.email">

        <!--パスワード-->
        <label for="login-password">Password</label>
        
        <input type="password" class="form__item" id="login-password" v-model="loginForm.password">

        <!--ログインボタン-->
        <div class="form__button">
          <button type="submit" class="button button--inverse">login</button>
        </div>
      </form>
    </div>
    
    
    <div class="panel" v-show="tab === 2">
      <!-- .preventにより、submitはページのリロードトリガーになりません -->
      <form class="form" @submit.prevent="register">

        <!-- エラーメッセージ欄 -->
        <div v-if="registerErrors" class="errors">
          <ul v-if="registerErrors.name">
            <li v-for="msg in registerErrors.name" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="registerErrors.email">
            <li v-for="msg in registerErrors.email" :key="msg">{{ msg }}</li>
          </ul>
          <ul v-if="registerErrors.password">
            <li v-for="msg in registerErrors.password" :key="msg">{{ msg }}</li>
          </ul>
        </div>

        <label for="username">Name</label>
        
        <input type="text" class="form__item" id="username" v-model="registerForm.name">
        
        <label for="email">Email</label>
        
        <input type="text" class="form__item" id="email" v-model="registerForm.email">
        
        <label for="password">Password</label>
        
        <input type="password" class="form__item" id="password" v-model="registerForm.password">
        
        <label for="password-confirmation">Password (confirm)</label>
        
        <input type="password" class="form__item" id="password-confirmation" v-model="registerForm.password_confirmation">
        <div class="form__button">
          <button type="submit" class="button button--inverse">register</button>
        </div>
      </form>
      
    </div>
    
  </div>
  
</template>


<script>
  import { mapState } from 'vuex'
  export default {
  
    data() {
      return {
        tab: 1,
        loginForm: {
          email: '',
          password: ''
        },
        registerForm: {
          name: '',
          email: '',
          password: '',
          password_confirmation: ''
        }
      }
    },
    computed: mapState({
        apiStatus: state => state.auth.apiStatus,
        loginErrors: state => state.auth.loginErrorMessages,
        registerErrors: state => state.auth.registerErrorMessages
    }),
    methods: {
      async login() {
        //authストアのloginアクションを呼び出す
        await this.$store.dispatch('auth/login', this.loginForm)

        if(this.apiStatus) {
          //apiStatusが成功した場合のみ、トップページに遷移する
          this.$router.push('/')
        }
      },
      async register () {
        // authストアのresigterアクションを呼び出す
        await this.$store.dispatch('auth/register', this.registerForm)
        if (this.apiStatus) {
          // トップページに移動する
          this.$router.push('/')
        }
      },
      clearError() {
        this.$store.commit('auth/setLoginErrorMessages', null)
        this.$store.commit('auth/setRegisterErrorMessages', null)
      }
    },
    created() {
      this.clearError()
    }
  }
</script>
