<template lang="pug">
  q-select.col(
    v-model="model"
    :label="label"
    :options="options"
    filled square readonly bottom-slots
    :bg-color="diffKey === null ? 'teal-1' : diffVal ? 'pink-1' : ''"
  )
    template(#hint v-if="diffVal")
      .q-gutter-sm.flex
        .text-grey BEFORE:
        .text-weight-medium.text-pink {{ diffVal }}
</template>

<script>
export default {
  props: ['value', 'label', 'options', 'diffVal', 'diffKey'],
  data() {
    return {
      model: this.value
    }
  }
}
</script>
