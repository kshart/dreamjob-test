<template>
  <v-card>
    <v-card-title>
      {{ news.title }}
    </v-card-title>
    <v-card-subtitle>
      {{ news.created_at }} {{ news.user ? news.user.name : news.author_id }}
    </v-card-subtitle>
    <v-card-item>
      <MdPreview :modelValue="news.description" />
    </v-card-item>
  </v-card>
</template>

<script lang="ts">
/* eslint-disable vue/no-mutating-props */
import { defineComponent } from 'vue'
import type { PropType } from 'vue'
import { News } from '@/api/news'
import { MdPreview } from 'md-editor-v3'

export default defineComponent({
  components: {
    MdPreview,
  },
  props: {
    news: Object as PropType<News>,
  },
})
</script>
