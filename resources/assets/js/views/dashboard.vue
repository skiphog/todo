<template>

    <div class="container">

        <nav class="pagination is-centered" role="navigation" aria-label="pagination">

            <a class="pagination-previous" v-if="hasPrev()" @click="changePage(prevPage)">Назад</a>
            <a class="pagination-previous" v-if="!hasPrev()" disabled>Назад</a>

            <a class="pagination-next" v-if="hasNext()" @click="changePage(nexPage)">Вперед</a>
            <a class="pagination-next" v-if="!hasNext()" disabled>Вперед</a>

            <ul class="pagination-list">

                <li><a href="#" class="pagination-link" v-if="hasFirst()" @click.prevent="changePage(1)">1</a></li>
                <li><span class="pagination-ellipsis" v-if="hasFirst()">&hellip;</span></li>

                <li v-for="page in pages">
                    <a href="#" @click.prevent="changePage(page)" class="pagination-link" :class="{'is-current': currentPage === page }">
                        {{ page }}
                    </a>
                </li>

                <li><span class="pagination-ellipsis" v-if="hasLast()">&hellip;</span></li>
                <li>
                    <a href="#" class="pagination-link" v-if="hasLast()" @click.prevent="changePage(total)">{{ total }}</a>
                </li>

            </ul>
        </nav>

        <div class="columns">
            <div class="column panel" v-for="(item, j) in lists">
                <p class="panel-heading">
                    {{ item.title }}
                    <router-link class="icon-burger" :to="`/dashboard/${item.id}/edit`"></router-link>
                </p>

                <div class="panel-block" v-for="(note, i ) in item.notes">

                    <div class="level">
                        <div class="level-left">
                            <span class="level__checked" :class="{'checked' : note.checked}" @click="changeChecked(note)">{{ note.content }}</span>
                        </div>
                        <div class="level-right">
                            <button class="delete" @click="deleteNote(j, i)"></button>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>

</template>

<script>
  import { get, post, del } from '../helpers/api';

  export default {
    data () {
      return {
        lists: [],
        total: 0,
        perPage: 5,
        currentPage: 1,
        pageRange: 2
      };
    },
    computed: {
      pages () {
        let arr = [];

        for (let i = this.rangeStart; i <= this.rangeEnd; i++) {
          arr.push(i);
        }

        return arr;
      },
      rangeStart () {
        let start = this.currentPage - this.pageRange;

        return start > 0 ? start : 1;
      },
      rangeEnd () {
        let d = this.currentPage + this.pageRange;

        return d < this.total ? d : this.total;
      },
      nexPage () {
        return this.currentPage + 1;
      },
      prevPage () {
        return this.currentPage - 1;
      }
    },
    created () {
      this.fetchLists(this.currentPage);
    },
    methods: {
      hasFirst () {
        return this.rangeStart !== 1;
      },
      hasLast () {
        return this.rangeEnd < this.total;
      },
      hasPrev () {
        return this.currentPage > 1;
      },
      hasNext () {
        return this.currentPage < this.total;
      },
      fetchLists (page) {

        get(`/api/dashboard?page=${page}`).then((res) => {
          const data = res.data.panel;

          this.lists = data.data;
          this.total = parseInt(data.last_page);
          this.currentPage = page;

        }).catch(/* todo Error */);

      },
      changePage: function (page) {
        this.fetchLists(page);
      },
      changeChecked (note) {
        note.checked = !note.checked;
        post(`/api/note/${note.id}?_method=PUT`, {checked: note.checked}).then((res) => {
          //todo: что-то сдедать
        });
      },
      deleteNote (panelIndex, noteIndex) {
        let id = this.lists[panelIndex].notes[noteIndex].id;
        this.lists[panelIndex].notes.splice(noteIndex, 1);
        del(`/api/note/${id}`).then((res) => {
          // todo:: что-то сделать
        });
      }
    }
  };
</script>