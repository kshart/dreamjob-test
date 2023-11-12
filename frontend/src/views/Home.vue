<template>
  <v-navigation-drawer v-if="!$vuetify.display.mobile" :value="true">
    <v-sheet
      v-if="me"
      color="grey-lighten-4"
      class="pa-4"
    >
      <v-avatar
        class="mb-4"
        color="grey-darken-1"
        size="64"
        image="/assets/logo.png"
      />

      <div>{{ me.name }}</div>
    </v-sheet>
    <v-divider></v-divider>

    <v-list>
      <v-list-item
        v-for="{ icon, title, to }, index in links"
        :key="index"
        :prepend-icon="icon"
        :title="title"
        :to="to"
        link
      />
    </v-list>
    <template v-slot:append>
      <div class="pa-2">
        <v-btn v-if="me" block @click="logout">
          Выход
        </v-btn>
        <v-btn v-else block @click="showLoginWindow = true">
          Вход
        </v-btn>
        <v-dialog
          v-model="showLoginWindow"
          width="700"
        >
          <v-card>
            <v-card-item>
              <div class="d-flex align-center justify-space-between py-6">
                <CreateAccount style="min-width:300px" />
                <Login style="min-width:300px" />
              </div>
            </v-card-item>
          </v-card>
        </v-dialog>
      </div>
    </template>
  </v-navigation-drawer>
  <router-view />
  <v-bottom-navigation v-if="$vuetify.display.mobile">
    <v-btn
      :active="false"
      key="drawer"
      prependIcon="mdi-account"
      text="Me"
      @click="drawer = !drawer"
    />
    <v-btn
      v-for="{ icon, title, to }, index in links"
      :to="to"
      :key="index"
      :prependIcon="icon"
      :text="title"
    />
  </v-bottom-navigation>
  <VBottomSheet v-if="$vuetify.display.mobile" v-model="drawer">
    <v-sheet
      v-if="me"
      class="pa-4"
    >
      <v-avatar
        class="mb-4"
        color="grey-darken-1"
        size="64"
        image="/assets/logo.png"
      />

      <div>{{ me.name }}</div>
      <div class="pa-2">
        <v-btn block color="primary" @click="logout">
          Выход
        </v-btn>
      </div>
    </v-sheet>
    <v-sheet
      v-else
      class="pa-4"
    >
      <CreateAccount class="pb-10" />
      <Login />
    </v-sheet>
  </VBottomSheet>
</template>

<script lang="ts">
import Api from '@/api'
import { Me } from '@/api/site'
import Login from '@/views/auth/Login.vue'
import CreateAccount from '@/views/auth/CreateAccount.vue'
import { defineComponent } from 'vue'
import { VBottomSheet } from 'vuetify/labs/VBottomSheet'

export default defineComponent({
  components: {
    Login,
    VBottomSheet,
    CreateAccount,
  },
  data () {
    return {
      me: null as Me|null,
      drawer: false,
      showLoginWindow: false,
      color: '',
      value: '',
      links: [
        {
          icon: 'mdi-calendar-check-outline',
          title: 'Новости',
          to: '/news',
        }, {
          icon: 'mdi-account-box-multiple-outline',
          title: 'Пользователи',
          to: '/users',
        },
      ],
    }
  },
  beforeMount () {
    this.drawer = !this.$vuetify.display.mobile
    Api.site.me()
      .then(me => {
        this.me = me
      })
  },
  methods: {
    login () {
      console.log('asd')
    },
    logout () {
      Api.site.logout()
        .finally(() => {
          window.location.reload()
        })
    },
  }
})
</script>
