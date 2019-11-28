<template>
    <!-- Main Footer -->
    <footer class="main-footer" style="max-height: 100px;text-align: center">
        <strong>Copyright © {{date()}} <a href="#">Logistics</a>.</strong> All rights reserved.
    </footer>

</template>

<script>
    export default {
      methods:{
          date(){
              return moment(Date.now()).format('Y')
          }
      }
    }
</script>

<style scoped>

</style>
