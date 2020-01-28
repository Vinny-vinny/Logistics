<template>
    <div>
        <app-index v-if="show_fuel"></app-index>
        <!-- Main content -->
        <section class="content" v-if="!show_fuel">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Choose Period</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="fuel()">
                        <div class="form-group">
                            <label>From</label>
                            <datepicker v-model="form.from" required></datepicker>
                        </div>
                        <div class="form-group">
                            <label>To</label>
                            <datepicker v-model="form.to" required></datepicker>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
    import Index from '../../../reports/fuels/partners/Index';
    export default {
        data(){
            return {
                form:{
                    from:'',
                    to:''
                },
                show_fuel: false,
                customers:{},
                customer_types:{},
                machines:{},

            }
        },
        created(){
            this.listen();
            this.getCustomers();
            this.getCustomerTypes();
            this.getMachines();
        },
        methods:{
            getCustomers(){
             axios.get('customers')
             .then(res => this.customers = res.data);
            },
            getCustomerTypes(){
            axios.get('customer-types')
            .then(res => this.customer_types = res.data);
            },
            getMachines(){
             axios.get('machines')
             .then(res => this.machines = res.data);
            },
            fuel(){
                this.form.from = moment(this.form.from).format('YYYY-MM-DD');
                this.form.to = moment(this.form.to).format('YYYY-MM-DD');
                if (this.form.from > this.form.to){
                    return this.$toastr.e('Date from cannot be greater than Date to.')
                }
                if (this.form.from =='' || this.form.to ==''){
                    return this.$toastr.e('Date from and Date to cannot be empty.')
                }
                axios.post('diesel-analysis',this.form)
                    .then(res =>{
                        //console.log(res.data)
                        let customer_id = this.customer_types.find(c => c.name =='Internal').id;
                        let partners_fuels =[];
                        for(let i=0;i<res.data.length;i++){
                         if(res.data[i]['customer_type_id'] ==customer_id){
                            partners_fuels.push(res.data[i]);
                         }
                     }

                     let km_object = {};
                     let other_km_obj = [];
                     let result = [];
                     for(let i=0;i<partners_fuels.length;i++){
                        if (!km_object[partners_fuels[i]['vehicle_id']]) {
                          km_object[partners_fuels[i]['vehicle_id']]= partners_fuels[i]; 
                        }
                        else if(km_object[partners_fuels[i]['vehicle_id']]){
                           other_km_obj.push(partners_fuels[i]);
                           km_object[partners_fuels[i]['vehicle_id']]['litres'] += partners_fuels[i]['litres']
                        }
                     }
                    
                    let myitems = other_km_obj.map(obj =>{
                   return obj.vehicle_id;
                    });                               

                       for(var k in km_object){
                        if(km_object.hasOwnProperty(k)){                       
                         if (myitems.includes(km_object[k]['vehicle_id'])) {
                         let itm = other_km_obj.filter(j => j.vehicle_id ==km_object[k]['vehicle_id']);                         
                          result.push({
                            'reg_no': this.machines.find(m => m.id ==km_object[k]['vehicle_id']).code,                            
                            'start_odo': km_object[k]['odometer_readings'],
                            'end_odo': itm[itm.length-1]['odometer_readings'],
                            'distance': itm[itm.length-1]['odometer_readings'] - km_object[k]['odometer_readings'],
                            'fuel_used': km_object[k]['litres'],
                            'km_per_ltrs':((itm[itm.length-1]['odometer_readings'] - km_object[k]['odometer_readings'])/km_object[k]['litres']).toFixed(2)
                          })
                         }
                         else if(!myitems.includes(km_object[k]['vehicle_id'])){                
                           result.push({
                            'reg_no': this.machines.find(m => m.id ==km_object[k]['vehicle_id']).code,                            
                            'start_odo': km_object[k]['previous_odometer'],
                            'end_odo': km_object[k]['odometer_readings'],
                            'distance': km_object[k]['odometer_readings']- km_object[k]['previous_odometer'],
                            'fuel_used': km_object[k]['litres'],
                            'km_per_ltrs':((km_object[k]['odometer_readings'] - km_object[k]['previous_odometer'])/km_object[k]['litres']).toFixed(2)                              
                          });
                         }                    
                    
                      }

                    }            
                     this.show_fuel = true;
                     this.$store.dispatch('listPartnersReports',result);
                     this.$store.dispatch('getPeriod',{from: moment(this.form.from).format("DD-MM-YYYY"),to:moment(this.form.to).format("DD-MM-YYYY")})                   

                    })
                    .catch(error => error.response)
            },
            listen(){
                eventBus.$on('back', ()=>{
                    this.show_fuel = false;
                })
            }
        },
        components:{
            datepicker,
            'app-index':Index
        }
    }
</script>

<style scoped>

</style>
