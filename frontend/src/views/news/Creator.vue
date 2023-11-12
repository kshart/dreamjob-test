<template>
  <v-card :loading="loading">
    <v-card-title>Новая новость</v-card-title>
    <v-card-item>
      <v-form ref="form">
        <v-text-field
          v-model="slug"
          :rules="slugRules"
          label="slug"
        />
        <v-text-field
          v-model="title"
          :rules="titleRules"
          label="Заголовок"
        />
        <MdEditor v-model="description" language="ru" />
        <v-checkbox v-model="isDraft" label="Черновик" />
      </v-form>
    </v-card-item>
    <v-card-actions>
      <v-list-item class="w-100">
        <template v-slot:append>
          <v-btn size="large" @click="$emit('close')">Отмена</v-btn>
          <v-btn size="large" color="primary" variant="elevated" @click="create">Создать</v-btn>
        </template>
      </v-list-item>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Api from '@/api'
import { MdEditor, config } from 'md-editor-v3'
import 'md-editor-v3/lib/style.css'
import ru from '@vavt/cm-extension/dist/locale/ru'

config({
  editorConfig: {
    languageUserDefined: {
      ru
    }
  }
})
export default defineComponent({
  components: {
    MdEditor,
  },
  data () {
    return {
      loading: false,
      slug: '',
      title: '',
      description: '',
      isDraft: true,
      slugRules: [(v: string) => !!v || 'Slug is required'],
      titleRules: [(v: string) => !!v || 'Title is required'],
      descriptionRules: [(v: string) => !!v || 'Description is required'],
    }
  },
  methods: {
    async create () {
      if (!this.$refs.form) {
        return
      }
      const { valid } = await (this.$refs.form as any).validate()

      if (valid) {
        this.loading = true
        const model = await Api.news.create({
          slug: this.slug,
          title: this.title,
          description: this.description,
          is_draft: this.isDraft,
        })
        this.loading = false
        if (model) {
          this.$emit('created', model)
        }
      }
    },
  }
})
</script>
