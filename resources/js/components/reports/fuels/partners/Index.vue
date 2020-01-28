<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Partner Reports</h3>                 
                     <button class="btn btn-primary pull-right" v-on:click="onexport" v-if="results.length"><i class="fa fa-file-excel-o" aria-hidden="true"></i>  Download</button>
                    <button class="btn btn-outline-danger pull-right mr" @click="back()">Back</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Reg. No</th>
                            <th>Start Odometer</th>
                            <th>End Odometer</th>
                            <th>Distance</th>
                            <th>Fuel Used(Ltr)</th>
                            <th>LTRS/Hr & KM/LTR</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="fuel in fuels">
                            <td>{{fuel.reg_no}}</td>
                            <td>{{fuel.start_odo}}</td>
                            <td>{{fuel.end_odo}}</td>
                            <td>{{fuel.distance}}</td>
                            <td>{{fuel.fuel_used}}</td>
                            <td>{{fuel.km_per_ltrs}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import XLSX from 'xlsx'
    export default {
        data(){
         return {
            results:[]
         }
        },
        mounted(){
            this.initDatable();
            this.partners();
        },
        computed:{
            fuels(){
                return this.$store.state.partners;
            },
             dates(){
              return this.$store.state.dates;
            } 

        },
        methods:{
        onexport () {      
      var animalWS = XLSX.utils.json_to_sheet(this.results)    

      // A workbook is the name given to an Excel file
      var wb = XLSX.utils.book_new() // make Workbook of Excel
      // add Worksheet to Workbook
      // Workbook contains one or more worksheets
      XLSX.utils.book_append_sheet(wb, animalWS, `Partn. ${this.dates.from} to ${this.dates.to}`) // sheetAName is name of Worksheet     

      // export Excel file
      XLSX.writeFile(wb, `PARTNER REPORTS AS FROM ${this.dates.from} to ${this.dates.to}.xls`) // name of the file is 'book.xlsx'
    },
             partners(){
                let results = [];
               setTimeout(()=>{
              for(let k=0;k<this.fuels.length;k++){
                            this.results.push({
                            'Reg': this.fuels[k]['reg_no'] ,                            
                            'Start Odo':this.fuels[k]['start_odo'],
                            'End Odo': this.fuels[k]['end_odo'],
                            'Distance': this.fuels[k]['distance'],
                            'Fuel Used(Ltr)': this.fuels[k]['fuel_used'],
                            'LTRS/HR & KM/LTR':this.fuels[k]['km_per_ltrs']
                              
                          });
                         }
                         return results;
               },1000)
            },
            back(){
                eventBus.$emit('back');
            },
            initDatable(){
                setTimeout(()=>{
                    $('.dt').DataTable({
                        "pagingType": "full_numbers",
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        order: [[ 0, 'asc' ], [ 3, 'desc' ]],
                        responsive: true,
                        destroy: true,
                        retrieve:true,
                        autoFill: true,
                        colReorder: true,

                    });
                },1000)
            },
        }
    }
</script>

<style scoped>

</style>
