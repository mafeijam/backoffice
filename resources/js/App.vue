<template lang="pug">
  q-layout(view="hHh Lpr lff")
    q-header.shadow-1
      q-toolbar.bg-indigo-10
        q-btn(flat @click="drawer = !drawer" round dense icon="o_menu")
        q-toolbar-title Backoffice System
        .q-gutter-sm
          q-btn(flat round dense icon="o_notifications")
          q-btn(flat round dense icon="o_more_vert")

    q-drawer(v-model="drawer" bordered :width="270" content-class="bg-white")
      q-list.text-grey-9
        q-item(
          clickable v-ripple
          @click="$inertia.visit('/')"
          :active="$page.url == '/'"
          active-class="bg-indigo-1 text-indigo-10 text-weight-bold"
        )
          q-item-section(avatar)
            q-icon(name="o_dashboard")
          q-item-section Home

        q-expansion-item(
          group="main"
          expand-separator
          icon="o_supervisor_account"
          :header-class="$page.url.startsWith('/client') ? 'text-indigo-10 text-weight-bold' : ''"
          label="Customer Service"
          :default-opened="$page.url.startsWith('/client')"
        )
          q-list(dense)
            q-item(
              clickable v-ripple
              @click="$inertia.visit('/client/list')"
              :active="$page.url.startsWith('/client/list')"
              :inset-level="1"
              active-class="bg-indigo-1 text-indigo-10"
            )
              q-item-section Client List
            q-item(
              clickable v-ripple
              @click="$inertia.visit('/client/create')"
              :active="$page.url.startsWith('/client/create')"
              :inset-level="1"
              active-class="bg-indigo-1 text-indigo-10"
            )
              q-item-section Create Client
            q-item(
              clickable v-ripple
              @click="$inertia.visit('/client/pending')"
              :active="$page.url.startsWith('/client/pending')"
              :inset-level="1"
              active-class="bg-indigo-1 text-indigo-10"
            )
              q-item-section Approve Client
                q-badge(color="orange" floating style="right: 3px;" v-if="$page.props.count.approve") {{ $page.props.count.approve }}
            q-item(
              clickable v-ripple
              @click="$inertia.visit('/client/rejected')"
              :active="$page.url.startsWith('/client/rejected')"
              :inset-level="1"
              active-class="bg-indigo-1 text-indigo-10"
            )
              q-item-section Rejected Client
                q-badge(color="orange" floating style="right: 3px;" v-if="$page.props.count.reject") {{ $page.props.count.reject }}

        q-expansion-item(
          group="main"
          expand-separator
          icon="o_compare_arrows"
          :header-class="$page.url.startsWith('/trade') ? 'text-indigo-10' : ''"
          label="Trade"
        )
          q-list(dense)
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Task
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Client Trade
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Broker Trade

        q-expansion-item(
          group="main"
          expand-separator
          icon="o_table_rows"
          :header-class="$page.url.startsWith('/setting') ? 'text-indigo-10' : ''"
          label="Master Table"
        )
          q-list(dense)
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Task
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Client Trade
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Broker Trade

        q-expansion-item(
          group="main"
          expand-separator
          icon="o_settings"
          :header-class="$page.url.startsWith('/setting') ? 'text-indigo-10' : ''"
          label="System Setting"
        )
          q-list(dense)
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Task
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Client Trade
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Broker Trade

        q-expansion-item(
          group="main"
          expand-separator
          icon="o_account_circle"
          :header-class="$page.url.startsWith('/user') ? 'text-indigo-10' : ''"
          label="User"
        )
          q-list(dense)
            q-item(clickable v-ripple :inset-level="1")
              q-item-section List
            q-item(clickable v-ripple :inset-level="1")
              q-item-section Role And Permission

    q-page-container.bg-grey-1
      q-page
        slot
</template>

<script>
export default {
  data() {
    return {
      drawer: true,
    }
  },
  watch: {
    '$page.props.flash.success': {
      handler(message) {
        this.$page.props.flash.success = null
        if (message) {
          this.$q.notify({
            type: 'positive',
            position: 'bottom-left',
            progress: true,
            html: true,
            actions: [
              { icon: 'o_close', color: 'pink', dense: true, round: true, size: 'sm' }
            ],
            message,
          })
        }
      },
      immediate: true
    },
    '$page.props.flash.error': {
      handler(message) {
        this.$page.props.flash.error = null
        if (message) {
          this.$q.notify({
            type: 'negative',
            position: 'bottom-left',
            progress: true,
            html: true,
            timeout: 100000,
            actions: [
              { icon: 'o_close', color: 'pink-1', dense: true, round: true, size: 'sm' }
            ],
            message,
          })
        }
      },
      immediate: true
    }
  }
}
</script>
