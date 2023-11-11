<template>
  <div>
    <v-text-field
      v-model="phone"
      label="Телефон"
      placeholder="+78005553535"
      type="text"
    />
    <v-text-field
      v-model="password"
      :append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
      :rules="[rules.required, rules.min]"
      :type="showPassword ? 'text' : 'password'"
      name="input-10-1"
      label="Пароль"
      hint="Минимум 5 символов"
      counter
      @click:append="showPassword = !showPassword"
    />
    <v-btn
      variant="flat"
      color="primary"
      @click="login"
    >
      Войти
    </v-btn>
  </div>
</template>

<script lang="ts">
import Api from '@/api'
import { defineComponent } from 'vue'

export default defineComponent({
  data () {
    return {
      phone: '',
      password: '',
      showPassword: false,
      rules: {
        required: (value: string) => !!value || 'Объязательное поле.',
        min: (value: string) => value.length >= 5 || 'Минимум 5 символов',
      },
    }
  },
  methods: {
    login () {
      Api.site.login(this.phone, this.password)
        .then(() => this.$router.push('/'))
    },
  }
})
</script>
