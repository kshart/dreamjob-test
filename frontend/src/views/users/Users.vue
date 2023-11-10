<template>
  <v-main class="h-100 bg-grey-lighten-3" scrollable>
    <v-container class="py-8 px-6" fluid>
      <VSkeletonLoader
        :loading="loading"
        height="240"
        type="card, list-item"
        class="w-100"
      >
        <VInfiniteScroll class="w-100 bg-grey-lighten-3" @load="load">
          <v-card
            v-for="user in users"
            class="mb-5 bg-white"
            :key="user.id"
            :title="user.name"
            :subtitle="`#${user.id}`"
            variant="elevated"
          >
            <v-card-text>
              <v-list-item
                density="compact"
                prepend-icon="mdi-phone"
              >
                <v-list-item-subtitle>{{ user.phone }}</v-list-item-subtitle>
              </v-list-item>
            </v-card-text>
            <v-card-actions>
              <v-btn-group>
                <v-btn>set admin</v-btn>
                <v-btn>delete</v-btn>
              </v-btn-group>
            </v-card-actions>
          </v-card>
        </VInfiniteScroll>
      </VSkeletonLoader>
    </v-container>
  </v-main>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Api from '@/api'
import { User } from '@/api/user'
import { VSkeletonLoader } from 'vuetify/labs/VSkeletonLoader'
import { VInfiniteScroll } from 'vuetify/labs/VInfiniteScroll'

export default defineComponent({
  components: {
    VSkeletonLoader,
    VInfiniteScroll,
  },
  data () {
    return {
      loading: true,
      page: 1,
      perPage: 20,
      total: 0,
      totalPages: 0,
      users: [] as User[],
    }
  },
  beforeMount () {
    Api.user.index(this.page, this.perPage)
      .then(paginator => {
        this.page = paginator.page
        this.total = paginator.total
        this.totalPages = paginator.totalPages
        this.users = paginator.data
      })
      .finally(() => {
        this.loading = false
      })
  },
  methods: {
    async load ({ done = (status: 'ok' | 'empty' | 'loading' | 'error') => undefined }) {
      if (this.page >= this.totalPages) {
        return done('empty')
      }
      try {
        const paginator = await Api.user.index(this.page + 1, this.perPage)
        this.page = paginator.page
        this.total = paginator.total
        this.totalPages = paginator.totalPages
        this.users = this.users.concat(paginator.data)
        this.loading = false
        if (paginator.page >= paginator.totalPages || paginator.data.length <= 0) {
          done('empty')
        } else {
          done('ok')
        }
      } catch (error) {
        console.error(error)
        done('error')
      }
    }
  }
})
</script>
