<template>
    <form class="form" @submit.prevent="log_in">
        <h1 class="form__title">Войти</h1>

        <div class="form__group">
            <label>Логин</label>
            <input type="text" class="form__control" v-model="form.login">
            <small class="error__control" v-if="error.login">{{error.login[0]}}</small>
        </div>

        <div class="form__group">
            <label>Пароль</label>
            <input type="password" class="form__control" v-model="form.password">
            <small class="error__control" v-if="error.password">{{error.password[0]}}</small>
        </div>

        <div class="form__group">
            <button :disabled="isProcessing" class="btn btn__primary">
                Войти
            </button>
        </div>

    </form>
</template>

<script>
  import Flash from '../../helpers/flash';
  import Auth from '../../helpers/auth';
  import { post } from '../../helpers/api';

  export default {
    data () {
      return {
        form: {
          login: 'demo',
          password: '123456'
        },
        error: {},
        isProcessing: false
      };
    },
    methods: {
      log_in () {
        this.isProcessing = true;
        this.error = {};
        post(`/api/login`, this.form).then((res) => {
          if (res.data.authenticated) {
            Auth.set(res.data.api_token, res.data.user_id);
            Flash.setSuccess('Вы успешно вошли');
            this.$router.push('/dashboard');
          }
          this.isProcessing = false;
        }).catch((err) => {
          if (err.response.status === 422) {
            this.error = err.response.data.errors;
            this.isProcessing = false;
          }
        });
      }
    }
  };
</script>