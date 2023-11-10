<template>
  <v-card :loading="loading">
    <v-card-title>Create news</v-card-title>
    <v-card-item>
      <v-form ref="form">
        <v-text-field
          v-model="title"
          :rules="titleRules"
          label="Title"
        />
        <v-textarea
          v-model="description"
          :rules="descriptionRules"
          label="Description"
        />
        <v-checkbox v-model="isDraft" label="Черновик" />
      </v-form>
    </v-card-item>
    <v-card-actions>
      <v-list-item class="w-100">
        <template v-slot:append>
          <v-btn size="large" @click="$emit('close')">Close</v-btn>
          <v-btn size="large" color="primary" variant="elevated" @click="create">Create</v-btn>
        </template>
      </v-list-item>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Api from '@/api'

export default defineComponent({
  data () {
    return {
      loading: false,
      title: '',
      description: '',
      isDraft: false,
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
          title: this.title,
          description: this.description,
          is_draft: this.isDraft,
        })
        model.can_edit = true
        this.loading = false
        this.$emit('created', model)
      }
    },
  }
})
</script>
