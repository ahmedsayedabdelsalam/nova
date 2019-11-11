import mediaLibrary from './store'

Nova.booting((Vue, router, store) => {
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
