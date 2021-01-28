import Vue from 'vue'
import VueMeta from 'vue-meta'
import { Inertia } from '@inertiajs/inertia'
import { App, plugin } from '@inertiajs/inertia-vue'

import Quasar from 'quasar'
// import '@quasar/extras/material-icons-sharp/material-icons-sharp.css'
import iconSet from 'quasar/icon-set/material-icons-outlined.js'

import Layout from '@/App'
import AutoImport from './auto-import-components'
import SetPrototype from './set-prototype'
import ConfigLoadingBar from './config-loading-bar'

Vue.use(VueMeta)
Vue.use(plugin)

Vue.use(Quasar, {
  config: {
    loadingBar: {
      skipHijack: true,
      color: 'pink',
      size: '3px',
      position: 'bottom'
    }
  },
  iconSet
})

AutoImport(Vue)

const el = document.getElementById('app')

const vm = new Vue({
  metaInfo: {
    titleTemplate: title => title ? `${title} - Backoffice System` : 'Backoffice System'
  },
  render: h => h(App, {
    props: {
      initialPage: JSON.parse(el.dataset.page),
      resolveComponent: name => import(`./Pages/${name}`)
        .then(({ default: page }) => {
          if (name === 'Login') {
            return page
          }
          page.layout = page.layout === undefined ? Layout : page.layout
          return page
        }),
    },
  }),
}).$mount(el)

SetPrototype(Vue, vm)

ConfigLoadingBar(Inertia, vm)
