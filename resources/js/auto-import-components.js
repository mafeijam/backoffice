export default Vue => {
  const files = require.context('@/Components', true, /\.vue$/i)
  files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
}

