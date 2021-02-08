<template lang="pug">
  div
    q-tabs.bg-indigo-6.text-white.shadow-1(v-model="tab" dense align="left")
      q-tab(name="list" label="List" @click="$inertia.visit('/master_table/ae/list')")
      q-tab(name="create" label="Create" @click="$inertia.visit('/master_table/ae/create')")

    component(:is="pageComponent")
</template>

<script>
export default {
  props: ['component', 'tabName'],
  data() {
    return {
      tab: this.tabName
    }
  },
  computed: {
    pageComponent() {
      return () => import(`@/Pages/${this.component}`)
    }
  }
}
</script>
