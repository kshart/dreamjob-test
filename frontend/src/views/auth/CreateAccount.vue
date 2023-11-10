<template>
  <div>
    <v-text-field
      v-model="phone"
      label="Phone"
      placeholder="+78005553535"
      type="text"
    />
    <v-text-field
      v-model="name"
      label="Username"
      placeholder="John Doe"
      type="text"
    />
    <v-text-field
      v-model="password"
      :rules="[rules.required, rules.min]"
      type="text"
      name="input-10-1"
      label="Password"
      hint="At least 4 characters"
      counter
    />
    <v-btn
      variant="flat"
      color="primary"
      @click="userCreate"
    >
      Create account
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
        required: (value: string) => !!value || 'Required.',
        min: (value: string) => value.length >= 4 || 'Min 4 characters',
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
