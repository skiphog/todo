<template>
    <div class="container">
        <div class="navbar">
            <div class="navbar__brand">
                <router-link to="/">TODO: Application</router-link>
            </div>
            <ul class="navbar__list">

                <li class="navbar__item" v-if="!check">
                    <router-link to="/login">Войти</router-link>
                </li>

                <li class="navbar__item" v-if="!check">
                    <router-link to="/register">Регистрация</router-link>
                </li>

                <li class="navbar__item" v-if="check">
                    <router-link to="/dashboard/create">Создать</router-link>
                </li>

                <li class="navbar__item" v-if="check">
                    <router-link to="/dashboard">Мои списки</router-link>
                </li>

                <li class="navbar__item" v-if="check">
                    <a href="#" @click.prevent="logout">Выйти</a>
                </li>
            </ul>
        </div>

        <div class="flash flash__success" v-if="flash.success">
            {{ flash.success }}
        </div>

        <div class="flash flash__error" v-if="flash.error">
            {{ flash.error }}
        </div>

        <router-view></router-view>

    </div>
</template>

<script>
  import Flash from './helpers/flash';
  import Auth from './helpers/auth';
  import { post } from './helpers/api';

  export default {
    created () {
      Auth.initialize();
    },
    data () {
      return {
        flash: Flash.state,
        auth: Auth.state
      };
    },
    computed: {
      check () {
        return !!(this.auth.api_token && this.auth.user_id);
      }
    },
    methods: {
      logout () {
        post(`/api/logout`).then((res) => {
          if (res.data.logged_out) {
            Auth.remove();
            Flash.setSuccess('Вы вышли');
            this.$router.push('/');
          }
        }).catch((err) => {

        });
      }
    }
  };
</script>