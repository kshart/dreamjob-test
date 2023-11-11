<template>
  <div>
    <v-text-field
      v-model="phone"
      label="Телефон"
      placeholder="+78005553535"
      type="text"
    />
    <v-text-field
      v-model="name"
      label="Имя"
      placeholder="John Doe"
      type="text"
    />
    <v-text-field
      v-model="password"
      :rules="[rules.required, rules.min]"
      type="text"
      name="input-10-1"
      label="Пароль"
      hint="Минимум 5 символов"
      counter
    />
    <v-btn
      variant="flat"
      color="primary"
      @click="userCreate"
    >
      Создать аккаунт
    </v-btn>
  </div>
</template>

<script lang="ts">
import Api from '@/api'
import { defineComponent } from 'vue'

export default defineComponent({
  data () {
    return {
      name: '',
      phone: '',
      password: '',
      showPassword: false,
      rules: {
        required: (value: string) => !!value || 'Объязательное поле.',
        min: (value: string) => value.length >= 4 || 'Минимум 5 символов',
      },
    }
  },
  methods: {
    userCreate () {
      Api.user.create({
        name: this.name,
        phone: this.phone,
        password: this.password,
      })
    },
  }
})
</script>
