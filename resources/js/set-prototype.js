export default (Vue, vm) => {
  Vue.prototype.$can = permission => {
    if (vm.$page.auth.roles.indexOf('super amdin') !== -1) {
      return true
    }
    for (let p of permission.split('|')) {
      if (vm.$page.auth.permissions.indexOf(p) !== -1) {
        return true
      }
    }
    return false
  }

  Vue.prototype.$onRequest = (props, component) => {
    const { page, rowsPerPage, sortBy, descending } = props.pagination
    const order = descending ? 'desc' : 'asc'

    component.$inertia.visit(`${component.data.path}?page=${page}`, {
      headers: {
        'X-PerPage': rowsPerPage === 0 ? component.data.total : rowsPerPage,
        'X-Sort': sortBy,
        'X-Desc': order
      },
      preserveState: true,
      onSuccess() {
        component.pagination.page = page
        component.pagination.rowsPerPage = rowsPerPage
        component.pagination.sortBy = sortBy
        component.pagination.descending = descending
      }
    })
  }
}
