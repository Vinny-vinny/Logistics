<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Partner Reports</h3>
                    <download-excel
                        :data = "results"
                        :title = "title"
                        v-if="results.length"
                        name="PARTNER_REPORTS.xlsx"
                        class="btn btn-primary pull-right">
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </download-excel>
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

    export default {
        data(){
         return {
            results:[],
             title:''
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
              partners(){
                let results = [];
               setTimeout(()=>{
                   let total_distance=0;
                   let total_fuel_used = 0;
                   let total_km = 0;
              for(let k=0;k<this.fuels.length;k++){
                  total_distance+=parseInt(this.fuels[k]['distance']);
                  total_fuel_used += parseFloat(this.fuels[k]['fuel_used']);
                  total_km += parseFloat(this.fuels[k]['km_per_ltrs']);
                            this.results.push({
                            'Reg': this.fuels[k]['reg_no'] ,
                            'Start Odo':this.fuels[k]['start_odo'],
                            'End Odo': this.fuels[k]['end_odo'],
                            'Distance': this.fuels[k]['distance'],
                            'FuelUsed': this.fuels[k]['fuel_used'],
                            'KMPerLTR':this.fuels[k]['km_per_ltrs']

                          });
                         }
                   this.results.push({Reg:'Total',Distance:total_distance,FuelUsed:total_fuel_used,KMPerLTR:total_km})
                   this.title = "PARTNERS REPORTS AS FROM "+this.dates.from+" TO "+ this.dates.to
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
