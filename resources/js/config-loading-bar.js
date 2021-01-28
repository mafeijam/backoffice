export default (Inertia, vm) => {
  let timeout = null

  Inertia.on('start', () => {
    timeout = setTimeout(() => vm.$q.loadingBar.start(), 300)
  })

  Inertia.on('finish', () => {
    clearTimeout(timeout)
    vm.$q.loadingBar.stop()
  })

  Inertia.on('progress', event => {
    if (event.detail.progress.percentage) {
      vm.$q.loadingBar.increment((event.detail.progress.percentage / 100) * 0.9)
    }
  })
}
