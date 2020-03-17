<template>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Datatable test</h3>
                </div>
                <div class="box-body">
                    <download-excel
                        :data   = "dataForExcel"
                         :title = "title"
                         >
                         Download Data
                        <img src="download_icon.png">
                    </download-excel>
                </div>
            </div>
        </section>
</template>
    <script>
        import JsonExcel from 'vue-json-excel'
    export default {
        data: function() {
            return {
                all_data:{},
                dataForExcel: [],
                title:'AMAZING'

            }
        },
        created() {
         this.getFuel();

         setTimeout(()=>{
           this.waalla();
         },1000)

        },

        methods:{
            waalla(){
                console.log('------------')
                console.log(this.all_data)
                console.log('=====================')
                this.all_data.forEach(() => {
                    this.dataForExcel.push({
                     colA: "Hello", colB: "World",colC:'cool'
                    });
                })
                this.dataForExcel.push({
                    colA: "Total", colC: 330
                })
            },
          getFuel(){
              axios.get('fuel')
              .then(res => {
                  this.all_data = res.data
                  console.log(res.data)
              })
          }
        },
        components: {
            'downloadExcel':JsonExcel
        }
    }
</script>

<style scoped>

</style>
