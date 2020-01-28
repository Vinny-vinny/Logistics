<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">KM Per Hr Reports</h3>                   
                    <button class="btn btn-primary pull-right" v-on:click="onexport" v-if="results.length"><i class="fa fa-file-excel-o" aria-hidden="true"></i>  Download</button>
                   <button class="btn btn-outline-danger pull-right mr" @click="back()">Back</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Reg No.</th>
                            <th>Start Odometer</th>
                            <th>End Odometer</th>
                            <th>Distance</th>
                            <th>Fuel Used(Ltr)</th>
                            <th>LTRS/HR & KM/LTR</th>               
                            
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
                results:[],
                fuels:[],
                machines:{}
            }
        },
       mounted(){           
            this.kmPerHr; 
            this.getMachines();           

        },
        computed:{
         dates(){
              return this.$store.state.dates;
            } ,          
          kmPerHr(){
          setTimeout(()=>{          
            let km_object = {};
                     let other_km_obj = [];
                     let result = [];
                     for(let i=0;i<this.$store.state.km_hrs.length;i++){
                        if (!km_object[this.$store.state.km_hrs[i]['vehicle_id']]) {
                          km_object[this.$store.state.km_hrs[i]['vehicle_id']]= this.$store.state.km_hrs[i]; 
                        }
                        else if(km_object[this.$store.state.km_hrs[i]['vehicle_id']]){
                           other_km_obj.push(this.$store.state.km_hrs[i]);
                           km_object[this.$store.state.km_hrs[i]['vehicle_id']]['litres'] += this.$store.state.km_hrs[i]['litres']
                        }
                     }
                    
                    let myitems = other_km_obj.map(obj =>{
                      return obj.vehicle_id;
                    });                               

                       for(var i in km_object){
                        if(km_object.hasOwnProperty(i)){                       
                         if (myitems.includes(km_object[i]['vehicle_id'])) {
                         let itm = other_km_obj.filter(j => j.vehicle_id ==km_object[i]['vehicle_id']);                         
                          this.fuels.push({
                            'reg_no': this.machines.find(m => m.id ==km_object[i]['vehicle_id']).code,                            
                            'start_odo': km_object[i]['odometer_readings'],
                            'end_odo': itm[itm.length-1]['odometer_readings'],
                            'distance': itm[itm.length-1]['odometer_readings'] - km_object[i]['odometer_readings'],
                            'fuel_used': km_object[i]['litres'],
                            'km_per_ltrs':((itm[itm.length-1]['odometer_readings'] - km_object[i]['odometer_readings'])/km_object[i]['litres']).toFixed(2)
                          })
                         }
                         else if(!myitems.includes(km_object[i]['vehicle_id'])){                
                           this.fuels.push({
                            'reg_no': this.machines.find(m => m.id ==km_object[i]['vehicle_id']).code,                            
                            'start_odo': km_object[i]['previous_odometer'],
                            'end_odo': km_object[i]['odometer_readings'],
                            'distance': km_object[i]['odometer_readings']- km_object[i]['previous_odometer'],
                            'fuel_used': km_object[i]['litres'],
                            'km_per_ltrs':((km_object[i]['odometer_readings'] - km_object[i]['previous_odometer'])/km_object[i]['litres']).toFixed(2)                              
                          });
                         }                    
                    
                      }

                    } 
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
                  this.initDatable();           
          },1000) 
          }
        },
        methods:{
      onexport () {      
      var animalWS = XLSX.utils.json_to_sheet(this.results)    

      // A workbook is the name given to an Excel file
      var wb = XLSX.utils.book_new() // make Workbook of Excel
      // add Worksheet to Workbook
      // Workbook contains one or more worksheets
      XLSX.utils.book_append_sheet(wb, animalWS, `kmhr ${this.dates.from} to ${this.dates.to}`) // sheetAName is name of Worksheet     

      // export Excel file
      XLSX.writeFile(wb, `KM PER HOUR AS FROM ${this.dates.from} to ${this.dates.to}.xls`) // name of the file is 'book.xlsx'
    } , 
        getMachines(){
        axios('machines')
        .then(res => this.machines = res.data)
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
        },
        
    }
</script>

<style scoped>

</style>
