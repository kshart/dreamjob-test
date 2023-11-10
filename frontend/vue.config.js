const { defineConfig } = require('@vue/cli-service')
const { VuetifyPlugin } = require('webpack-plugin-vuetify')

module.exports = defineConfig({
  transpileDependencies: true,
  parallel: true,
  configureWebpack: {
    plugins: [
      new VuetifyPlugin(),
    ],
  },
  devServer: {
    host: null,
    port: 80,
    client: {
      webSocketURL: {
        hostname: '0.0.0.0',
        pathname: '/ws',
        port: 80,
        protocol: 'ws',
      },
    },
  }
})
