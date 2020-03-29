<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">KM Per Hr Reports</h3>
                    <download-excel
                        :data   = "results"
                        :title = "title"
                        v-if="results.length"
                        name="KM_PER_HR.xlsx"
                        class="btn btn-primary pull-right"
                    >
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </download-excel>
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
   export default {
        data(){
            return {
                results:[],
                fuels:[],
                machines:{},
                title:''
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
                            'reg_no': this.machines.find(m => m.project_link ==km_object[i]['vehicle_id']).description,
                            'start_odo': km_object[i]['odometer_readings'],
                            'end_odo': itm[itm.length-1]['odometer_readings'],
                            'distance': itm[itm.length-1]['odometer_readings'] - km_object[i]['odometer_readings'],
                            'fuel_used': km_object[i]['litres'],
                            'km_per_ltrs':((itm[itm.length-1]['odometer_readings'] - km_object[i]['odometer_readings'])/km_object[i]['litres']).toFixed(2)
                          })
                         }
                         else if(!myitems.includes(km_object[i]['vehicle_id'])){
                           this.fuels.push({
                            'reg_no': this.machines.find(m => m.project_link ==km_object[i]['vehicle_id']).description,
                            'start_odo': km_object[i]['previous_odometer'],
                            'end_odo': km_object[i]['odometer_readings'],
                            'distance': km_object[i]['odometer_readings']- km_object[i]['previous_odometer'],
                            'fuel_used': km_object[i]['litres'],
                            'km_per_ltrs':((km_object[i]['odometer_readings'] - km_object[i]['previous_odometer'])/km_object[i]['litres']).toFixed(2)
                          });
                         }

                      }

                    }
                       let total_km_hr = 0;
                       let total_fuel_used = 0;
                       let total_distance = 0;
                    for(let k=0;k<this.fuels.length;k++){
                        total_km_hr += parseFloat(this.fuels[k]['km_per_ltrs']);
                        total_fuel_used +=  parseFloat(this.fuels[k]['fuel_used']);
                        total_distance += parseFloat(this.fuels[k]['distance']);
                            this.results.push({
                            'Reg': this.fuels[k]['reg_no'] ,
                            'Start Odo':this.fuels[k]['start_odo'],
                            'End Odo': this.fuels[k]['end_odo'],
                            'Distance': this.fuels[k]['distance'],
                            'FuelUsed': this.fuels[k]['fuel_used'],
                            'KmPerLtr':this.fuels[k]['km_per_ltrs']
                          });
                         }
              this.results.push({Reg:'Total',Distance:total_distance,FuelUsed:total_fuel_used,KmPerLtr:total_km_hr});
              this.title = "KM Per Hr AS FROM "+this.dates.from+" TO "+ this.dates.to;
                  this.initDatable();
          },1000)
          }
        },
        methods:{
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
