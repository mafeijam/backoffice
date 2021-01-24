import Vue from 'vue'
import VueMeta from 'vue-meta'
import { App, plugin } from '@inertiajs/inertia-vue'

import Quasar from 'quasar'
// import '@quasar/extras/material-icons-sharp/material-icons-sharp.css'
import iconSet from 'quasar/icon-set/material-icons-outlined.js'

import Layout from '@/App'

import DatePick from '@/Components/DatePick'

Vue.use(VueMeta)
Vue.use(plugin)

Vue.use(Quasar, {
  config: {
    loadingBar: {
      skipHijack: false,
      color: 'pink',
      size: '3px',
      position: 'bottom'
    }
  },
  iconSet
})

Vue.component('date-pick', DatePick)

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

Vue.prototype.$can = permission => {
  if (vm.$page.auth.roles.indexOf('super amdin') !== -1) {
    return true
  }
  for (let p of permission.split('|')) {
    if (vm.$page.auth.permissions.indexOf(p) !== -1) {
      return true
    }
  }
  return false
}

Vue.prototype.$onRequest = (props, component) => {
  const { page, rowsPerPage, sortBy, descending } = props.pagination
  const order = descending ? 'desc' : 'asc'

  component.$inertia.visit(`${component.data.path}?page=${page}`, {
    headers: {
      'X-PerPage': rowsPerPage === 0 ? component.data.total : rowsPerPage,
      'X-Sort': sortBy,
      'X-Desc': order
    },
    preserveState: true,
    onSuccess() {
      component.pagination.page = page
      component.pagination.rowsPerPage = rowsPerPage
      component.pagination.sortBy = sortBy
      component.pagination.descending = descending
    }
  })
}
