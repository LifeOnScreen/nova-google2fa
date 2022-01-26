Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: '2fa-recovery-codes',
      path: '/recovery-codes',
      component: require('./components/Tool.vue').default,
    },
  ])
})
