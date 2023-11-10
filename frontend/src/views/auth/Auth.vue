<template>
  <v-layout
    v-if="!$vuetify.display.mobile"
    class="d-flex align-center justify-space-around"
  >
    <CreateAccount style="min-width:300px" />
    <Login style="min-width:300px" />
  </v-layout>
  <v-layout v-else>
    <v-main scrollable>
      <v-window v-model="mobilePanel" class="h-100">
        <v-window-item class="h-100" value="CreateAccount">
          <v-sheet class="h-100 d-flex align-center">
            <CreateAccount class="pa-8 w-100" />
          </v-sheet>
        </v-window-item>
        <v-window-item class="h-100" value="Login">
          <v-sheet class="h-100 d-flex align-center">
            <Login class="pa-8 w-100" />
          </v-sheet>
        </v-window-item>
      </v-window>
    </v-main>
    <v-bottom-navigation order="-1">
      <v-btn
        :active="mobilePanel === 'CreateAccount'"
        prependIcon="mdi-account-plus"
        text="Create user"
        @click="mobilePanel = 'CreateAccount'"
      />
      <v-btn
        :active="mobilePanel === 'Login'"
        prependIcon="mdi-login"
        text="Login"
        @click="mobilePanel = 'Login'"
      />
    </v-bottom-navigation>
  </v-layout>
</template>

<script lang="ts">
import { defineComponent } from 'vue'
import Login from './Login.vue'
import CreateAccount from './CreateAccount.vue'

export default defineComponent({
  components: {
    Login,
    CreateAccount,
  },
  data () {
    return {
      mobilePanel: 'Login' as 'CreateAccount'|'Login'
    }
  },
})
</script>
