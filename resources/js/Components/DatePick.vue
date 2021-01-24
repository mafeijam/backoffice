<template lang="pug">
  q-input(v-bind="$attrs" v-model="model")
    template(v-slot:append)
      q-btn(flat dense icon="o_event" :disable="disable" rounded)
        q-menu(:offset="[10, 15]" anchor="bottom right" self="top right" ref="m")
          q-date(v-model="model" minimal mask="YYYY-MM-DD"
            @input="() => $refs.m.hide()" :options="minDate")
    template(v-slot:hint v-if="hint")
      .text-weight-medium.text-pink Before: {{hint }}
</template>

<script>
export default {
  props: ['value', 'disable', 'minDate', 'hint'],
  computed: {
    model: {
      get() { return this.value },
      set(val) { this.$emit('input', val) }
    }
  }
}
</script>
