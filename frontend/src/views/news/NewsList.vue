<template>
  <v-app-bar :height="56">
    <v-text-field
      prepend-inner-icon="mdi-magnify"
      v-model="fts"
      label="Поиск"
      :loading="loading"
      single-line
      hide-details
      autocomplete="off"
      @input="changeFts()"
      @keydown.enter="changeFtsNow()"
    />
  </v-app-bar>
  <v-main class="bg-grey-lighten-3">
    <v-container class="py-2 px-6" fluid>
      <VInfiniteScroll class="bg-grey-lighten-3" @load="pullList">
        <div
          v-for="news of newsList"
          :key="news.id"
          class="py-3"
        >
          <NewsItem :news="news" />
        </div>
        <template #empty>
          Загружено {{ newsList.length }} новостей
        </template>
      </VInfiniteScroll>
      <v-layout v-if="$vuetify.display.mobile" justify-center>
        <v-dialog
          v-model="showCreateNews"
          hide-overlay
          fullscreen
          transition="dialog-bottom-transition"
        >
          <Creator
            @close="showCreateNews = false"
            @created="addNews($event)"
          />
        </v-dialog>
      </v-layout>
      <v-layout v-else justify-center>
        <v-dialog
          v-model="showCreateNews"
          width="500"
        >
          <Creator
            @close="showCreateNews = false"
            @created="addNews($event)"
          />
        </v-dialog>
      </v-layout>
    </v-container>
    <div v-if="$vuetify.display.mobile" class="pr-3 bottom-btn">
      <v-btn
        size="large"
        icon="mdi-plus"
        color="primary"
        @click="showCreateNews = true"
      />
    </div>
    <div v-else class="pr-5 bottom-btn">
      <v-btn
        size="x-large"
        icon="mdi-plus"
        color="primary"
        @click="showCreateNews = true"
      />
    </div>
  </v-main>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import type { RouteLocationNormalized, NavigationGuardNext } from 'vue-router'
import Api from '@/api'
import type { News } from '@/api/news'
import Creator from './Creator.vue'
import NewsItem from './NewsItem.vue'
import { VInfiniteScroll } from 'vuetify/labs/VInfiniteScroll'

export default defineComponent({
  components: {
    Creator,
    NewsItem,
    VInfiniteScroll,
  },
  setup () {
    return {
      changeFtsTimeout: undefined as number|undefined,
    }
  },
  data () {
    return {
      showCreateNews: false,
      loading: false,
      page: 1,
      perPage: 5,
      total: 0,
      totalPages: 0,
      newsList: [] as News[],
      fts: '',
      pullConfig: {
        key: +new Date(),
        fts: '',
      }
    }
  },
  beforeRouteEnter (to: RouteLocationNormalized, from: RouteLocationNormalized, next: NavigationGuardNext) {
    const perPage = 5
    const fts = (to.query.fts || '').toString()
    let page = Number(to.query.page || 1)
    if (Number.isNaN(page)) {
      page = 1
    }
    Api.news.search(decodeURIComponent(fts), 1, page * perPage)
      .then(paginator => {
        next((vm: any) => {
          vm.fts = fts
          vm.perPage = perPage
          vm.page = page
          vm.total = paginator.total
          vm.totalPages = Math.ceil(paginator.total / perPage)
          vm.newsList = paginator.data
          vm.pullConfig = {
            key: +new Date(),
            fts,
          }
        })
      })
  },
  beforeUnmount () {
    clearTimeout(this.changeFtsTimeout)
  },
  methods: {
    changeFts () {
      console.log('changeFts')
      clearTimeout(this.changeFtsTimeout)
      this.changeFtsTimeout = setTimeout(() => {
        this.changeFtsTimeout = undefined
        this.reloadList()
      }, 750)
    },
    changeFtsNow () {
      console.log('changeFtsNow')
      clearTimeout(this.changeFtsTimeout)
      this.changeFtsTimeout = undefined
      this.reloadList()
    },
    reloadList () {
      console.log('reloadList')
      this.loading = true
      const fts = this.fts
      Api.news.search(fts, 1, this.perPage)
        .then(paginator => {
          this.pullConfig.fts = fts
          this.pullConfig.key = +new Date()
          this.page = paginator.page
          this.total = paginator.total
          this.totalPages = paginator.totalPages
          this.newsList = paginator.data
          const { page, ...query } = this.$route.query
          return this.$router.replace({
            path: this.$route.path,
            query: {
              ...query,
              fts: encodeURIComponent(fts)
            }
          })
        })
        .finally(() => {
          this.loading = false
        })
    },
    async pullList (options: {
      done: (status: 'ok' | 'empty' | 'loading' | 'error') => void
    }) {
      console.log('pullList', this.page, this.totalPages)
      if (this.page >= this.totalPages) {
        return options.done('empty')
      }
      try {
        const key = this.pullConfig.key
        const paginator = await Api.news.search(this.pullConfig.fts, this.page + 1, this.perPage)
        if (key !== this.pullConfig.key) {
          return options.done('empty')
        }
        this.page = paginator.page
        this.total = paginator.total
        this.totalPages = paginator.totalPages
        this.newsList = this.newsList.concat(paginator.data)
        if (paginator.page >= paginator.totalPages || paginator.data.length <= 0) {
          options.done('empty')
        } else {
          options.done('ok')
        }
        await this.$router.replace({
          path: this.$route.path,
          query: {
            ...this.$route.query,
            page: paginator.page
          }
        })
      } catch (error) {
        console.error(error)
        options.done('error')
      }
    },
    addNews (news: News) {
      this.newsList.unshift(news)
      this.showCreateNews = false
    }
  }
})
</script>

<style scoped>
.bottom-btn {
  position: fixed;
  bottom: 0;
  right: 0;
  transform: translateY(-50%);
  z-index: 2000;
}
</style>
