<template>
    <section>
        <div uk-height-viewport="offset-top: true; offset-bottom: true" class="uk-section uk-section-small uk-flex uk-flex-middle uk-text-center uk-card uk-card-default uk-card-small uk-card-body" style="box-sizing: border-box; min-height: calc(100vh - 183px); height: calc(100vh - 183px);">

            <div class="uk-width-1-1">
                <div class="uk-container"><p><span uk-icon="icon: file-edit;ratio: 6"></span></p>
                    <p class="uk-margin-medium uk-text-lead">
                        Сервис для ведения TODO−списков.<br class="uk-visible@s">
                        Создание, удаление, редактирование
                    </p>
                    <div uk-grid class="uk-child-width-auto uk-grid-medium uk-flex-inline uk-flex-center uk-grid">

                        <div class="uk-first-column" v-if="auth.api_token">
                            <router-link  class="uk-button uk-button-primary tm-button-primary uk-button-large tm-button-large uk-visible@s" to="/dashboard">Начать</router-link>
                            <router-link class="uk-button uk-button-primary tm-button-primary uk-hidden@s" to="/dashboard">Начать</router-link>
                        </div>

                        <div class="uk-first-column" v-else="">
                            <router-link  class="uk-button uk-button-primary tm-button-primary uk-button-large tm-button-large uk-visible@s" to="/login">Начать</router-link>
                            <router-link class="uk-button uk-button-primary tm-button-primary uk-hidden@s" to="/login">Начать</router-link>
                        </div>

                    </div>
                </div>
            </div>

        </div>

        <div class="uk-section-small">
            <div class="uk-container uk-container-expand uk-text-center uk-position-relative">
                <ul uk-margin class="uk-subnav tm-subnav uk-flex-inline uk-flex-center uk-margin-remove-bottom">
                    <li class="uk-first-column">
                        <span>Version 0.0.1-alpha :-D</span>
                    </li>
                    <li>
                        <a href="https://github.com/skiphog/todo">
                            <span uk-icon="icon: github" class="uk-margin-small-right uk-icon"></span>
                            Github
                        </a>
                    </li>
                </ul>
            </div>
        </div>

    </section>
</template>

<script>
    import Auth from '../helpers/auth';

    export default {
      data() {
        return {
          auth: Auth.state
        }
      }
    }
</script>