<template>

    <div>

        <div class="uk-container uk-margin-bottom">
            <ul class="uk-pagination uk-flex-center" uk-margin>

                <li v-if="hasPrev()">
                    <a @click.prevent="changePage(prevPage)">
                        <span uk-pagination-previous></span>
                    </a>
                </li>

                <li v-if="hasFirst()"><a @click.prevent="changePage(1)" href="#">1</a></li>

                <li v-if="hasFirst()" class="uk-disabled"><span>...</span></li>


                <li v-for="page in pages">
                    <a href="#" @click.prevent="changePage(page)" :class="{'pagination-active': currentPage === page }">
                        {{ page }}
                    </a>
                </li>

                <li v-if="hasLast()" class="uk-disabled"><span>...</span></li>

                <li v-if="hasLast()"><a @click.prevent="changePage(total)" href="#">{{ total }}</a></li>


                <li v-if="hasNext()">
                    <a @click.prevent="changePage(nexPage)">
                        <span uk-pagination-next></span>
                    </a>
                </li>

            </ul>
        </div>

        <div class="uk-container uk-margin-bottom">

            <div class="uk-child-width-1-2@s uk-child-width-1-3@m" uk-grid>
                <div v-for="(item, j) in lists">


                    <div class="uk-card uk-card-default uk-card-small uk-card-body uk-card-hover">
                        <router-link class="uk-card-badge" :to="`/dashboard/${item.id}/edit`">
                            <span uk-icon="icon: cog"></span></router-link>
                        <h3 :class="{'checked' : item.checked}" class="uk-card-title">{{ item.title }}</h3>
                        <hr>
                        <ul class="uk-list uk-list-divider">
                            <li v-for="(note, i ) in item.notes">
                                <div class="uk-clearfix">
                                    <div class="uk-float-left">
                                        <span class="checked-box" :class="{'checked' : note.checked}" @click="changeChecked(note,item)">{{ note.content }}</span>
                                    </div>
                                    <div class="uk-float-right">
                                        <button  type="button" uk-close @click="deleteNote(j, i)"></button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

        </div>

        <hr class="uk-divider-icon">

        <div class="uk-section">
            <div class="uk-container uk-margin-bottom">
                <div class="uk-flex uk-flex-center">
                    <div class="uk-card uk-card-default uk-card-body" title="Всего списков" uk-tooltip>
                        <span uk-icon="icon: database"></span> {{ info.count}}
                    </div>
                    <div class="uk-card uk-card-default uk-card-body uk-margin-left uk-text-success" title="Выполнено" uk-tooltip>
                        <span uk-icon="icon: check"></span> {{info.complete}}
                    </div>
                    <div class="uk-card uk-card-default uk-card-body uk-margin-left uk-text-danger" title="Не выполнено" uk-tooltip>
                        <span uk-icon="icon: bolt"></span> {{info.incomplete }}
                    </div>
                </div>
            </div>

            <div class="uk-container uk-margin-bottom">
                <div class="uk-child-width-expand@s uk-text-center" uk-grid>
                    <div>
                        <div class="uk-card uk-card-default uk-card-body" title="Дата создания первого списка" uk-tooltip>
                            <span uk-icon="icon: info"></span> {{info.date_start}}
                        </div>
                    </div>

                    <div>
                        <div class="uk-card uk-card-default uk-card-body" title="Дата создания последнего списка" uk-tooltip>
                            <span uk-icon="icon: info"></span> {{info.date_end}}
                        </div>
                    </div>
                </div>
            </div>


        </div>

    </div>

</template>

<script>
  import { get, post, del } from '../helpers/api';
  import Auth from '../helpers/auth';
  export default {
    data () {
      return {
        lists: [],
        info: {
          complete: 0,
          incomplete: 0,
          count: 0,
          date_end: null,
          date_start: null
        },
        total: 0,
        perPage: 5,
        currentPage: 1,
        pageRange: 2,
        auth: Auth.state
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
          this.info = res.data.info;

        }).catch(/* todo Error */);

      },
      changePage: function (page) {
        this.fetchLists(page);
      },
      changeChecked (note, panel) {
        note.checked = !note.checked;
        post(`/api/note/${note.id}?_method=PUT`, {checked: note.checked}).then((res) => {
          //todo: что-то сдедать
        });
        this.reCheck(panel);
      },
      deleteNote (panelIndex, noteIndex) {
        let id = this.lists[panelIndex].notes[noteIndex].id;
        this.lists[panelIndex].notes.splice(noteIndex, 1);
        del(`/api/note/${id}`).then((res) => {
          // todo:: что-то сделать
        });
        this.reCheck(this.lists[panelIndex]);
      },
      reCheck (panel) {
        let res = !(panel.notes.filter((i) => {
          return !i.checked;
        }).length);

        if (!panel.checked && res) {
          panel.checked = res;
          this.info.complete++;
          this.info.incomplete--;
        } else if (panel.checked && !res) {
          this.info.complete--;
          this.info.incomplete++;
          panel.checked = res;
        }
      }
    }
  };
</script>