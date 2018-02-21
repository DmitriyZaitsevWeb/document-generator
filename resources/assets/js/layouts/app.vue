<template>
  <div class="app-layout">
    <navbar></navbar>

    <div class="container my-4">
      <child/>
    </div>
  </div>
</template>

<script>
import Navbar from '~/components/Navbar'

export default {
  name: 'app-layout',

  components: {
    Navbar
  }
}
</script>
