<template>
    <form class="form" @submit.prevent="register">
        <h1 class="form__title">Регистрация</h1>

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
            <label>Подтвердите пароль</label>
            <input type="password" class="form__control" v-model="form.password_confirmation">
        </div>

        <div class="form__group">
            <button :disabled="isProcessing" class="btn btn__primary">
                Зарегистрироваться
            </button>
        </div>

    </form>
</template>

<script>
  import Flash from '../../helpers/flash';
  import { post } from '../../helpers/api';

  export default {
    data () {
      return {
        form: {
          login: '',
          password: '',
          password_confirmation: ''
        },
        error: {},
        isProcessing: false
      };
    },
    methods: {
      register () {
        this.isProcessing = true;
        this.error = {};
        post(`/api/register`, this.form).then((res) => {
          if (res.data.registered) {
            Flash.setSuccess('Аккаунт успешно создан');
            this.$router.push('/login');
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