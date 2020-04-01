<template>
    <div>
        <fuel v-if="add_fuel" :edit="editing" :other_fuel="add_fuel_other"
              :add_fuel="add_fuel"></fuel>
             <reversal v-if="show_reversal" :reverse="show_reversal"></reversal>
             <show-fuel v-if="show_print && !add_fuel && !add_fuel_other && !show_reversal"></show-fuel>

        <!-- Main content -->
        <section class="content" v-if="!add_fuel && !add_fuel_other && !show_reversal && !show_print">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fueling</h3>
                    <button class="btn btn-primary pull-right" @click="add_fuel_other=true" style="display:none">Add Other Fuel</button>
                    <button class="btn btn-primary pull-right mr" @click="addFuel()">{{show_add_text ? 'Please wait...':'Add Fuel'}}</button>

                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Vehicle</th>
                            <th>Litres</th>
                            <th>Fuel Type</th>
                            <th>Pump Price</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="fuel in tableData">
                            <td>{{fuel.fuel_no}}</td>
                            <td>{{fuel.vehicle}}</td>
                            <td>{{fuel.litres}}</td>
                            <td>{{fuel.fuel_type}}</td>
                            <td>{{fuel.rate}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editFuel(fuel)"><i
                                    class="fa fa-edit"></i></button>
                                <button class="btn btn-info btn-sm" @click="showPrint(fuel)"><i class="fa fa-eye"></i></button>
<!--                                <router-link :to="{path: '/fuel/'+fuel.id}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></router-link>-->
                                <button v-if="!fuel.reversal_ref && fuel.fuel_category_id=='stock_issue' && fuel.status !=0" class="btn btn-danger btn-sm" @click="reverseFuel(fuel)"><i class="fa fa-undo" aria-hidden="true"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import Fuel from "./Fuel";
    import Reversal from "./Reversal";
    import ShowFuel from "./ShowFuel";
    export default {
        data() {
            return {
                tableData: [],
                add_fuel: false,
                add_fuel_other: false,
                editing: false,
                show_reversal:false,
                show_print:false,
                show_add_text:false,
                check_customers:false,
                check_accounts:false,
                check_parts:false
            }
        },
        created() {
             this.getFuels();
             this.listen();
             this.getCustomers();
             this.getMachines();
             this.getAccounts();
             this.getParts();
        },
        computed:{
          cust(){
             return this.check_customers;
          }  ,
            account(){
             return this.check_accounts;
            },
            part(){
              return this.check_parts;
            },
        },
        methods: {
            addFuel(){
             this.show_add_text = true;
             if (this.cust && this.account && this.part){
                 this.add_fuel =  true;
             }
            },
            reverseFuel(fuel){
             this.$store.dispatch('updateFuel',fuel)
                    .then(() =>{
                        this.show_reversal = true;
                        this.add_fuel=false;
                        })
            },
            getCustomers(){
              axios.get('customers')
              .then(res => {
                  this.check_customers = true;
                  this.$store.dispatch('my_customers',res.data);
              })
            },
            getMachines(){
                axios.get('machines')
                    .then(res => {
                        this.$store.dispatch('my_vehicles',res.data);
                    })
            },
            getAccounts(){
                axios.get('accounts')
                    .then(res => {
                        this.check_accounts = true;
                        this.$store.dispatch('my_accounts',res.data);
                    })
            },
            getParts(){
                axios.get('parts')
                    .then(res => {
                        this.check_parts = true;
                       this.$store.dispatch('my_parts',res.data);
                    })
            },
            getFuels() {
                axios.get('fuel')
                    .then(res => {
                        this.tableData = res.data.fuels;
                        this.initDatable();
                        this.$store.dispatch('my_fuels',res.data.fuels);
                        this.$store.dispatch('my_customer_types',res.data.customer_types);
                        this.$store.dispatch('my_jobcards',res.data.jobcards);
                        this.$store.dispatch('my_charges',res.data.charges);
                        this.$store.dispatch('my_users',res.data.users);
                        this.$store.dispatch('my_projects',res.data.projects);

                    })
                    .catch(error => Exception.handle(error))
            },
            showPrint(fuel){
             this.$store.dispatch('updateCustomer',this.$store.state.all_my_customers.find(c => c.id === fuel.customer_id));
             this.$store.dispatch('updateFuel',fuel)
                .then(() =>{
                 this.show_print = true;
                })
            },
            editFuel(fuel) {
                this.$store.dispatch('updateFuel', fuel)
                    .then(() => {
                        this.editing = true;
                        fuel.vehicle_id !== null ? this.add_fuel = true : this.add_fuel_other = true;
                    })
            },
            deleteFuel(id) {
                axios.delete(`fuel/${id}`)
                    .then(res => {
                        for (let i = 0; i < this.tableData.length; i++) {
                            if (this.tableData[i].id == res.data) {
                                this.tableData.splice(i, 1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            listen() {
                eventBus.$on('listFuels', (fuel) => {
                    this.tableData.unshift(fuel);
                    this.add_fuel = false;
                    this.add_fuel_other = false;
                    this.show_add_text = false;
                    this.initDatable();
                });
                eventBus.$on('cancel', () => {
                    this.add_fuel = false;
                    this.add_fuel_other = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.show_print = false;
                    this.show_add_text = false;
                    this.getFuels();
                    this.initDatable();
                });
                eventBus.$on('updateFuel', (fuel) => {
                    this.add_fuel = false;
                    this.add_fuel_other = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.show_add_text = false;
                    for (let i = 0; i < this.tableData.length; i++) {
                        if (this.tableData[i].id == fuel.id) {
                            this.tableData.splice(i, 1);
                        }
                    }
                    this.tableData.unshift(fuel);
                    this.initDatable();
                });
            },
            initDatable() {
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
        components: {
            Fuel,
            Reversal,
            ShowFuel
        }
    }
</script>

<style scoped>

</style>
