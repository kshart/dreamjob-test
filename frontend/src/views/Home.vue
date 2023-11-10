<template>
  <v-navigation-drawer v-if="!$vuetify.display.mobile" :value="true">
    <v-sheet
      color="grey-lighten-4"
      class="pa-4"
    >
      <v-avatar
        class="mb-4"
        color="grey-darken-1"
        size="64"
        image="/assets/logo.png"
      />

      <div>john@google.com</div>
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
        <v-btn block @click="logout">
          Logout
        </v-btn>
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
      class="pa-4"
    >
      <v-avatar
        class="mb-4"
        color="grey-darken-1"
        size="64"
        image="/assets/logo.png"
      />

      <div>john@google.com</div>
      <div class="pa-2">
        <v-btn block color="primary" @click="logout">
          Logout
        </v-btn>
      </div>
    </v-sheet>
  </VBottomSheet>
</template>

<script lang="ts">
import Api from '@/api'
import { Me } from '@/api/user'
import { defineComponent } from 'vue'
import { VBottomSheet } from 'vuetify/labs/VBottomSheet'

export default defineComponent({
  components: {
    VBottomSheet,
  },
  data () {
    return {
      me: null as Me|null,
      drawer: false,
      color: '',
      value: '',
      links: [
        {
          icon: 'mdi-calendar-check-outline',
          title: 'News',
          to: '/news',
        }, {
          icon: 'mdi-account-box-multiple-outline',
          title: 'Users',
          to: '/users',
        },
      ],
    }
  },
  beforeMount () {
    this.drawer = !this.$vuetify.display.mobile
  },
  methods: {
    logout () {
      Api.user.logout()
        .finally(() => {
          window.location.reload()
        })
    },
  }
})
</script>
