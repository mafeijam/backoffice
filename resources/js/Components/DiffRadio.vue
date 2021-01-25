<template lang="pug">
  q-field(
    :label="label"
    stack-label filled square bottom-slots readonly
    :bg-color="diff === null ? 'teal-1' : diff ? 'pink-1' : ''"
  )
    template(v-slot:control)
      q-option-group(
        v-model="model"
        inline dense disable size="xs"
        :options="options"
      )
    template(v-slot:hint v-if="diff")
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
