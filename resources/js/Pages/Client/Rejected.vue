<template lang="pug">
  .q-pa-md
    q-table.shadow-1.sticky-header-table(
      title="Rejected Client"
      row-key="id"
      binary-state-sort
      :dense="$q.screen.lt.lg"
      :grid="$q.screen.xs"
      :columns="columns"
      :data="data.data"
      :pagination.sync="pagination"
      :rows-per-page-options="[30, 50, 100, 0]"
      @request="onRequest"
    )
      template(v-slot:top)
        .full-width.flex.justify-between.q-py-xs
          .text-h6 Rejected Client
      template(v-slot:body-cell-status="props")
        q-td(:props="props")
          q-chip(
            square
            :color="props.row.status === 'NEW REJECTED' ? 'purple' : 'orange'"
            text-color="white"
            size="sm"
          ) {{ props.row.status }}

      template(v-slot:body-cell-actions="props")
        q-td(:props="props")
          q-btn(
            label="edit"
            flat dense size="sm"
            icon="o_edit"
            color="green"
            @click="$inertia.visit(`/client/correct/${props.row.uuid}`)"
          )
</template>

<script>
export default {
  metaInfo() {
    return {
      title: 'Rejected Client'
    }
  },
  props: ['data'],
  data() {
    return {
      columns: [
        { name: 'client', align: 'left', label: 'Client', field: row => row.data.name, sortable: true },
        {
          name: 'accounts',
          align: 'left',
          label: 'Accounts',
          field: row => row.data.accounts.map(a => `${a.accountNo} (${a.type})`).join(', '),
          sortable: true
        },
        { name: 'status', align: 'left', label: 'Status', field: 'status', sortable: true },
        { name: 'created_by', align: 'left', label: 'Created By', field: 'cname', sortable: true },
        { name: 'rejected_by', align: 'left', label: 'Rejecdted By', field: 'rname', sortable: true },
        { name: 'submitted_at', align: 'right', label: 'Submitted At', field: 'updated_at', sortable: true },
        { name: 'actions', align: 'right', label: 'Actions' }
      ],
      pagination: {
        sortBy: 'submitted_at',
        descending: true,
        page: this.data.current_page,
        rowsPerPage: parseInt(this.data.per_page),
        rowsNumber: this.data.total
      }
    }
  },
  methods: {
    onRequest(props) {
      this.$onRequest(props, this)
    }
  },
}
</script>
