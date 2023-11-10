<template>
  <v-card>
    <v-card-title>
      {{ news.title }}
    </v-card-title>
    <v-card-subtitle>
      12.11.2023 {{ news.user ? news.user.name : news.author_id }}
    </v-card-subtitle>
    <v-card-item>
      {{ news.description }}
    </v-card-item>
  </v-card>
</template>

<script lang="ts">
/* eslint-disable vue/no-mutating-props */
import { defineComponent } from 'vue'
import type { PropType } from 'vue'
import { News } from '@/api/news'

export default defineComponent({
  props: {
    news: Object as PropType<News>,
  },
  methods: {
    create () {
      // Api.news.create({
      //   title: this.title,
      //   description: this.description,
      //   stage: this.stage,
      //   isPrivate: this.isPrivate,
      // })
    },
  }
})
</script>
