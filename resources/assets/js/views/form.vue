<template>
    <div class="form">
        <h1 class="subtitle">{{ action }} лист</h1>

        <div class="form__group">
            <label>Заголовок списка</label>
            <input type="text" class="form__control" v-model="form.title">
            <small class="error__control" v-if="error.title">{{error.title[0]}}</small>
        </div>

        <div class="form__edit">
            <h2>Заметки</h2>

            <div v-for="(note, i) in form.notes" class="form__group">
                <input type="text" class="form__control" v-model="note.content"
                        :class="[error[`notes.${i}.content`] ? 'error__bg' : '']">

                <button @click="remove(i)" class="delete"></button>

            </div>
        </div>

        <button @click="addNote" class="btn">Добавить заметку</button>

        <div class="form__group">
            <button @click="save" :disabled="isProcessing" class="btn btn__primary">
                Сохранить
            </button>

            <button class="btn" @click="$router.back()" :disabled="isProcessing">Назад</button>
        </div>

    </div>
</template>

<script>
  import Vue from 'vue';
  import Flash from '../helpers/flash';
  import { get, post } from '../helpers/api';

  export default {
    data () {
      return {
        form: {
          notes: []
        },
        error: {},
        isProcessing: false,
        initUrl: `/api/dashboard/create`,
        storeUrl: `/api/dashboard`,
        action: 'Создать'
      };
    },
    created () {
      if (this.$route.meta.mode === 'edit') {
        this.initUrl = `/api/dashboard/${this.$route.params.id}/edit`;
        this.storeUrl = `/api/dashboard/${this.$route.params.id}?_method=PUT`;
        this.action = 'Редактировать';
      }

      get(this.initUrl).then((res) => {
        Vue.set(this.$data, 'form', res.data.form);
      }).catch();
    },
    methods: {
      save () {
        const form = JSON.parse(JSON.stringify(this.form));

        post(this.storeUrl, form).then((res) => {
          if(res.data.saved) {
            Flash.setSuccess(res.data.message);
            this.$router.push(`/dashboard`);
          }
          this.isProcessing = false
        }).catch((err) => {
          if(err.response.status === 422) {
            this.error = err.response.data.errors
          }
          this.isProcessing = false
        });
      },
      addNote () {
        this.form.notes.push({note: ''});
      },
      remove (i) {
        console.log(this.form);
        this.form.notes.splice(i, 1);
      }
    }

  };
</script>