<template lang="pug">
  q-select.col(
    v-model="model"
    :label="label"
    :options="options"
    filled square readonly bottom-slots
    :bg-color="diff === null ? 'teal-1' : diff ? 'pink-1' : ''"
  )
    template(#hint v-if="diff")
      .q-gutter-sm.flex
        .text-grey BEFORE:
        .text-weight-medium.text-pink {{ diff }}
</template>

<script>
export default {
  props: ['value', 'label', 'options', 'diff'],
  data() {
    return {
      model: this.value
    }
  }
}
</script>
