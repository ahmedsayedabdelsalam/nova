import mediaLibrary from './store'

Nova.booting((Vue, router, store) => {
  Vue.config.devtools = true
  router.addRoutes([
    {
      name: 'media-library',
      path: '/media-library',
      component: require('./components/Tool'),
    },
  ]),
  store.registerModule(
    'mediaLibrary', mediaLibrary
  )
})
