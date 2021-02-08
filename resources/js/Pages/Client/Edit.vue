<template lang="pug">
  .q-pa-md
    q-card.shadow-1
      q-card-section
        .text-h6 Edit Client
      q-separator(inset)

      q-card-section(v-if="reason")
        q-banner.bg-red-1.q-pa-md
          template(#avatar)
            q-icon(name="o_info" color="red-8")
          .row.q-col-gutter-md
            .col-12.text-subtitle1.text-red-8 Rejected reason
            .col-12.text-body2 {{ reason }}

      q-card-section
        q-form(@submit.prevent="submit")
          .row.q-col-gutter-md
            .col-12
              .text-subtitle1.text-indigo Information
            q-select.col-4(
              v-model="form.clientType"
              label="Client Type"
              :options="['INDIVIDUAL', 'JOINT', 'CORPORATE']"
              filled square bottom-slots
            )

            q-field.col-4(label="Non Face To Face" stack-label filled square bottom-slots)
              template(#control)
                q-option-group(v-model="form.nonFace" inline dense :options="options" size="xs")

            q-field.col-4(label="US Tax Citizen" stack-label filled square bottom-slots)
              template(#control)
                q-option-group(v-model="form.usTax" inline dense :options="options" size="xs")

            q-input.col-4(
              v-model="form.name"
              label="Name"
              filled square bottom-slots
              :error-message="form.errors.name"
              :error="!!form.errors.name"
              @focus="form.clearErrors('name')"
            )
            q-input.col-4(v-model="form.email" label="Email Address" filled square bottom-slots)
            q-input.col-4(v-model="form.phone" label="Phone Number" filled square bottom-slots)

            .col-12
              q-separator
            .col-12.flex.justify-between.items-center
              .text-subtitle1.text-indigo Accounts
              q-btn(label="add accounts" flat dense size="sm" icon="o_add" color="blue" @click="addRowAccount")

            .col-12
              .row.q-col-gutter-x-md.items-center.q-mb-md(v-for="a, i in form.accounts")
                q-input.col(
                  v-model="a.accountNo"
                  label="Account Numbber"
                  filled square bottom-slots
                  :error-message="form.errors[`accounts.${i}.accountNo`]"
                  :error="!!form.errors[`accounts.${i}.accountNo`]"
                  @focus="form.clearErrors(`accounts.${i}.accountNo`)"
                  :readonly="a.readonly"
                )
                q-select.col(
                  v-model="a.type"
                  label="Account Type"
                  :options="['CASH', 'CUSTODIAN', 'MARGIN']"
                  filled square bottom-slots
                  :readonly="a.readonly"
                )
                date-pick.col(v-model="a.openAt" label="Open Date (YYYY-MM-DD)" filled square bottom-slots)
                q-select.col(v-model="a.status" label="Status" :options="['ACTIVE', 'INACTIVE']" filled square bottom-slots)
                .col-shrink
                  q-btn(size="md" icon="o_remove_circle_outline" flat round dense color="red" @click="removeRowAccount(i)" :disable="a.readonly")

          .row.q-col-gutter-md.q-mt-md
            .col-12.text-right
              q-btn.shadow-1(label="submit" type="submit" icon="o_send" color="indigo-10" :loading="form.processing")
</template>

<script>
export default {
  metaInfo() {
    return {
      title: 'Edit Client'
    }
  },
  remember: ['form'],
  props: ['client', 'formSchema', 'reason'],
  data() {
    return {
      form: this.$inertia.form(this.client.data),
      options: [{
        label: 'YES',
        value: 'YES',
      }, {
        label: 'NO',
        value: 'NO',
      }]
    }
  },
  methods: {
    submit() {
      const uri = this.reason ? 'correct' : 'edit'
      this.form.put(`/client/${uri}/${this.client.uuid}`)
    },
    addRowAccount() {
      this.form.accounts.push({
        ...this.formSchema.accounts[0]
      })
    },
    removeRowAccount(i) {
      if (this.form.accounts.length === 1) {
        this.form.accounts = [{
          ...this.formSchema.accounts[0]
        }]
        this.form.clearErrors('accounts.0.accountNo')
        return
      }

      this.form.accounts.splice(i, 1)
    }
  }
}
</script>
