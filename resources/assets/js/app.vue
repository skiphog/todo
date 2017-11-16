<template>
    <div class="uk-container">

        <nav class="uk-navbar-container uk-margin uk-navbar uk-box-shadow-medium navbar-todo" uk-navbar>
            <div class="uk-navbar-left">
                <ul class="uk-navbar-nav">
                    <li>
                        <router-link to="/">TODO: Application</router-link>
                    </li>
                </ul>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav">
                    <li v-if="guest">
                        <router-link to="/login">Войти</router-link>
                    </li>

                    <li v-if="guest">
                        <router-link to="/register">Регистрация</router-link>
                    </li>

                    <li v-if="user">
                        <router-link to="/dashboard">Мои списки</router-link>
                    </li>

                    <li v-if="user">
                        <router-link to="/dashboard/create">Создать</router-link>
                    </li>

                    <li v-if="user">
                        <a href="#" @click.prevent="logout">Выйти</a>
                    </li>
                </ul>
            </div>

        </nav>


        <router-view></router-view>

    </div>
</template>

<script>
  import Auth from './helpers/auth';
  import { post, interceptors } from './helpers/api';
  import UIkit from 'uikit';

  export default {
    created () {
      interceptors((err) => {
        if (err.response.status === 401) {
          Auth.remove();
          this.$router.push('/login');
        }

        if (err.response.status === 500) {
          UIkit.notification(err.response.statusText, {status: 'danger'});
        }

        if (err.response.status === 404) {
          this.$router.push('/not-found');
        }
      });
      Auth.initialize();
    },
    data () {
      return {
        auth: Auth.state
      };
    },
    computed: {
      user () {
        return !!(this.auth.api_token && this.auth.user_id);
      },
      guest () {
        return !this.user;
      }
    },
    methods: {
      logout () {
        post(`/api/logout`).then((res) => {
          if (res.data.logged_out) {
            Auth.remove();
            UIkit.notification('Вы вышли', {status: 'success'});
            this.$router.push('/');
          }
        }).catch((err) => {

        });
      }
    }
  };
</script>