<template lang="pug">
  .q-pa-md
    q-card.shadow-1
      q-card-section
        .flex.items-center.justify-between
          .text-h6 {{ isView ? 'View Client' : 'Approve Client' }}
          q-chip(
            :label="client.status"
            :color="client.status === 'ACTIVE' ? 'green' : 'red'"
            text-color="white"
            square size="md"
            v-if="isView"
          )
          q-chip(
            :label="client.status"
            :color="client.status === 'NEW' ? 'purple' : 'orange'"
            text-color="white"
            square size="md"
            v-else
          )

      q-separator(inset)

      q-card-section
        .row.q-col-gutter-md
          .col-12.text-subtitle1.text-indigo Information

          diff-select.col-4(
            v-model="client.data.clientType"
            label="Client Type"
            :options="['INDIVIDUAL', 'JOINT', 'CORPORATE']"
            filled square bottom-slots
            :diff="$e(() => meta.clientType)"
          )

          diff-radio.col-4(
            v-model="client.data.nonFace"
            label="Non Face To Face"
            :options="options"
            :diff="$e(() => meta.nonFace)"
          )

          diff-radio.col-4(
            v-model="client.data.usTax"
            label="US Tax Citizen"
            :options="options"
            :diff="$e(() => meta.usTax)"
          )

          diff-input.col-4(v-model="client.data.name" label="Name" :diff="$e(() => meta.name)")

          diff-input.col-4(v-model="client.data.email" label="Email Address" :diff="$e(() => meta.email)")

          diff-input.col-4(v-model="client.data.phone" label="Phone Number" :diff="$e(() => meta.phone)")

          .col-12
            q-separator
          .col-12
            .text-subtitle1.text-indigo Accounts
          .col-12
            .row.q-col-gutter-x-md.items-center.q-mb-md(v-for="a, i in client.data.accounts")
              diff-input-array.col(
                v-model="a.accountNo"
                label="Account Numbber"
                :diffKey="$e(() => meta.accounts[$k(a)])"
                :diffVal="$e(() => meta.accounts[$k(a)].accountNo)"
              )

              diff-select-array.col(
                v-model="a.type"
                label="Account Type"
                :options="['CASH', 'CUSTODIAN', 'MARGIN']"
                filled square readonly bottom-slots
                :diffKey="$e(() => meta.accounts[$k(a)])"
                :diffVal="$e(() => meta.accounts[$k(a)].type)"
              )

              date-pick.col(
                v-model="a.openAt"
                label="Open Date (YYYY-MM-DD)"
                filled square readonly bottom-slots
                :diffKey="$e(() => meta.accounts[$k(a)])"
                :diffVal="$e(() => meta.accounts[$k(a)].openAt)"
                disable
              )

              diff-select-array.col(
                v-model="a.status"
                label="Status"
                :options="['ACTIVE', 'INACTIVE']"
                filled square readonly bottom-slots
                :diffKey="$e(() => meta.accounts[$k(a)])"
                :diffVal="$e(() => meta.accounts[$k(a)].status)"
              )

          .col-12.text-right(v-if="type === 'approve'")
            .q-gutter-md
              q-btn(label="reject" icon="o_cancel" color="pink" @click="reject" :loading="loading.reject" :disable="loading.approve")
              q-btn(label="approve" icon="o_check_circle" color="teal" @click="confirm" :loading="loading.approve" :disable="loading.reject")
</template>

<script>
export default {
  metaInfo() {
    return {
      title: 'Client'
    }
  },
  props: ['client', 'type'],
  data() {
    return {
      loading: {
        approve: false,
        reject: false
      },
      options: [{
        label: 'YES',
        value: 'YES',
      }, {
        label: 'NO',
        value: 'NO',
      }]
    }
  },
  computed: {
    isView() {
      return this.type === 'view'
    },
    meta() {
      return this.client.meta_data.before
    }
  },
  methods: {
    confirm() {
      this.$q.dialog({
        title: 'Confirm',
        message: 'Are you sure?',
        cancel: true,
        transitionShow: 'jump-down',
        transitionHide: 'jump-up'
      })
      .onOk(() => {
        this.loading.approve = true
        const self = this
        this.$inertia.patch(`/client/${this.client.uuid}`, { }, {
          onError() {
            self.loading.approve = false
          }
        })
      })
    },
    reject() {
      this.$q.dialog({
        title: 'Reason',
        cancel: true,
        transitionShow: 'jump-down',
        transitionHide: 'jump-up',
        prompt: {
          model: '',
          type: 'textarea',
          filled: true,
          square: true,
          isValid: val => val.length > 2
        }
      })
      .onOk(reason => {
        this.loading.reject = true
        const self = this
        this.$inertia.patch(`/client/${this.client.uuid}`, { reason }, {
          onError() {
            self.loading.reject = false
          }
        })
      })
    },
    $e(fn) {
      try { return fn() }
      catch (e) {}
    },
    $k(a) {
      return `${a.accountNo}@${a.type}`
    }
  },
}
</script>
