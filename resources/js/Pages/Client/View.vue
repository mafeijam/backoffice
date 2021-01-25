<template lang="pug">
  .q-pa-md
    q-card.shadow-1
      q-card-section
        .row.q-col-gutter-md
          .col-12.text-right
            q-chip(
              :label="client.status"
              :color="client.status === 'NEW' ? 'purple' : 'orange'"
              text-color="white" square
              v-if="!isView"
            )

          .col-12
            .text-subtitle1.text-indigo Information
          q-input.col-4(
            v-model="client.data.name"
            label="Name"
            filled square readonly bottom-slots
            :bg-color="$e(() => meta.name) ? 'pink-1' : ''"
          )
            template(v-slot:hint v-if="$e(() => meta.name)")
              .text-weight-medium.text-pink Before: {{ $e(() => meta.name) }}
          q-input.col-4(
            v-model="client.data.email"
            label="Email Address"
            filled square readonly bottom-slots
            :bg-color="$e(() => meta.email) ? 'pink-1' : ''"
          )
            template(v-slot:hint v-if="$e(() => meta.email)")
              .text-weight-medium.text-pink Before: {{ $e(() => meta.email) }}
          q-input.col-4(
            v-model="client.data.phone"
            label="Phone Number"
            filled square readonly bottom-slots
            :bg-color="$e(() => meta.phone) ? 'pink-1' : ''"
          )
            template(v-slot:hint v-if="$e(() => meta.phone)")
              .text-weight-medium.text-pink Before: {{ $e(() => meta.phone) }}

          .col-12
            q-separator
          .col-12
            .text-subtitle1.text-indigo Accounts
          .col-12
            .row.q-col-gutter-x-md.items-center.q-mb-md(v-for="a, i in client.data.accounts")
              q-input.col(
                v-model="a.accountNo"
                label="Account Numbber"
                filled square readonly bottom-slots
                :bg-color="$e(() => meta.accounts[$a(a)].accountNo) ? 'pink-1' : ''"
              )
                template(v-slot:hint v-if="$e(() => meta.accounts[$a(a)].accountNo)")
                 .text-weight-medium.text-pink Before: {{ $e(() => meta.accounts[$a(a)].accountNo) }}
              q-select.col(v-model="a.type" label="Account Type" :options="['CASH', 'CUSTODIAN', 'MARGIN']" filled square readonly bottom-slots)
              date-pick.col(
                v-model="a.openAt"
                label="Open Date (YYYY-MM-DD)"
                filled square readonly bottom-slots
                :bg-color="$e(() => meta.accounts[$a(a)].openAt) ? 'pink-1' : ''"
                :hint="$e(() => meta.accounts[$a(a)].openAt)"
              )
              q-select.col(
                v-model="a.status"
                label="Status"
                :options="['ACTIVE', 'INACTIVE']"
                filled square readonly bottom-slots
                :bg-color="$e(() => meta.accounts[$a(a)].status) ? 'pink-1' : ''"
              )
                template(v-slot:hint v-if="$e(() => meta.accounts[$a(a)].status)")
                 .text-weight-medium.text-pink Before: {{ $e(() => meta.accounts[$a(a)].status) }}

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
      }
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
    $a(a) {
      return `${a.accountNo}@${a.type}`
    }
  },
}
</script>
