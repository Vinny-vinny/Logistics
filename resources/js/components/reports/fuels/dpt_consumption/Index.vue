<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fuel Reports</h3>
                   <button class="btn btn-primary pull-right" v-on:click="onexport" v-if="results.length"><i class="fa fa-file-excel-o" aria-hidden="true"></i>  Download</button>
                    <button class="btn btn-outline-danger pull-right mr" @click="back()">Back</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Department</th>
                            <th>QTY In Ltrs</th>
                            <th style="display:none">QTY In Ltrs</th>
                            <th>Percentage</th>                         
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="fuel in fuels">
                            <td>{{fuel.department}}</td>
                            <td>{{fuel.qty}}</td>
                            <td style="display:none">{{fuel.qty}}</td>
                            <td>{{fuel.percentage}}</td>                          
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
            this.consumptions();
        },
        computed:{
            fuels(){
                return this.$store.state.department_consumptions;
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
      XLSX.utils.book_append_sheet(wb, animalWS, `Dept% ${this.dates.from} to ${this.dates.to}`) // sheetAName is name of Worksheet     

      // export Excel file
      XLSX.writeFile(wb, `DEPARTMENT CONSUMPTION % AS FROM ${this.dates.from} to ${this.dates.to}.xls`) // name of the file is 'book.xlsx'
    },
            consumptions(){
            setTimeout(()=>{
            for(let i=0;i<this.fuels.length;i++){
                this.results.push({
                 'Department': this.fuels[i]['department'],
                 'QTY In Ltrs': this.fuels[i]['qty'],
                 'Percentage': this.fuels[i]['percentage']
                });
            }
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
