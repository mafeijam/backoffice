<template lang="pug">
  .q-pa-md
    q-table.shadow-1.sticky-header-table(
      title="Client List"
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
      template(#top)
        .full-width.flex.justify-between.q-py-xs
          .text-h6 Client List
      template(#body-cell-status="props")
        q-td(:props="props")
          q-chip(
            square
            :color="props.row.status === 'ACTIVE' ? 'green' : 'red'"
            text-color="white"
            size="sm"
          ) {{ props.row.status }}

      template(#body-cell-actions="props")
        q-td(:props="props")
          .q-gutter-xs
            q-btn(
              :label="props.row.pstatus ? 'pending edit' : 'edit'"
              flat dense size="sm"
              icon="o_edit"
              color="green"
              @click="$inertia.visit(`/client/edit/${props.row.client_uuid}`)"
              :disable="!!props.row.pstatus"
            )

            q-btn(
              label="view"
              flat dense size="sm"
              icon="o_launch"
              color="blue"
              @click="$inertia.visit(`/client/view/${props.row.client_uuid}`)"
            )
</template>

<script>
export default {
  metaInfo() {
    return {
      title: 'Client List'
    }
  },
  props: ['data'],
  data() {
    return {
      columns: [
        {
          name: 'client',
          align: 'left',
          label: 'Client',
          field: 'cname',
          sortable: true
        },
        {
          name: 'account',
          align: 'left',
          label: 'Account Number',
          field: 'number',
          sortable: true
        },
        {
          name: 'type',
          align: 'left',
          label: 'Account Type',
          field: 'type',
          sortable: true
        },
        {
          name: 'status',
          align: 'left',
          label: 'Status',
          field: 'status',
          sortable: true
        },
        { name: 'actions', align: 'right', label: 'Actions' }
      ],
      pagination: {
        sortBy: 'client',
        descending: false,
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
