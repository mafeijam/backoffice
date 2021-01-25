<template lang="pug">
  q-input(v-bind="$attrs" v-model="model" :bg-color="diffKey === null ? 'teal-1' : diffVal ? 'pink-1' : ''")
    template(v-slot:append)
      q-btn(flat dense icon="o_event" :disable="disable" rounded)
        q-menu(:offset="[10, 15]" anchor="bottom right" self="top right" ref="m")
          q-date(v-model="model" minimal mask="YYYY-MM-DD"
            @input="() => $refs.m.hide()" :options="minDate")
    template(v-slot:hint v-if="diffVal")
      .q-gutter-sm.flex
        .text-grey BEFORE:
        .text-weight-medium.text-pink {{ diffVal }}
</template>

<script>
export default {
  props: ['value', 'disable', 'minDate', 'hint', 'diffKey', 'diffVal'],
  computed: {
    model: {
      get() { return this.value },
      set(val) { this.$emit('input', val) }
    }
  }
}
</script>
