<template>
    <div>
        <show-reversal v-if="show_reversal" :reverse="show_reversal"></show-reversal>
        <!-- Main content -->
        <section class="content" v-if="!show_reversal">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fuel Reversals</h3>

                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Reversal Ref#</th>
                            <th>Vehicle</th>
                            <th>Litres</th>
                            <th>Fuel Type</th>
                            <th>Pump Price</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="fuel in tableData">
                            <td>{{fuel.reversal_ref}}</td>
                            <td>{{fuel.vehicle}}</td>
                            <td>{{fuel.reversal_litres}}</td>
                            <td>{{fuel.fuel_type}}</td>
                            <td>{{fuel.reversal_rate}}</td>
                            <td>
                                <button class="btn btn-info btn-sm" @click="editFuel(fuel)"><i
                                    class="fa fa-eye"></i></button>
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
    import ShowReversal from "./ShowReversal";

    export default {
        data() {
            return {
                tableData: [],
                add_fuel: false,
                add_fuel_other: false,
                editing: false,
                show_reversal:false,
                check_customers:false,
                check_accounts:false,
                check_parts:false
            }
        },
        created() {
            this.getFuels();
            this.getCustomers();
            this.getMachines();
            this.getAccounts();
            this.getParts();
        },
        mounted() {
            this.listen();
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
                    .then(res =>{
                        this.tableData = res.data.fuels.filter(f => f.reversal_ref !=='' && f.reversal_ref !==null)
                        this.$store.dispatch('my_fuels',res.data.fuels);
                        this.$store.dispatch('my_customer_types',res.data.customer_types);
                        this.$store.dispatch('my_jobcards',res.data.jobcards);
                        this.$store.dispatch('my_charges',res.data.charges);
                        this.$store.dispatch('my_users',res.data.users);
                        this.$store.dispatch('my_projects',res.data.projects);
                        this.$store.dispatch('my_expenses',res.data.expenses);
                    })
                    .catch(error => Exception.handle(error))
            },
            editFuel(fuel) {
                this.$store.dispatch('updateFuel', fuel)
                    .then(() => {
                        if (this.cust && this.part && this.account){
                            console.log('walla')
                            this.show_reversal = true;
                        }
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
                    this.initDatable();
                });
                eventBus.$on('cancel', () => {
                    this.add_fuel = false;
                    this.add_fuel_other = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.getFuels();
                    this.initDatable();
                });
                eventBus.$on('updateFuel', (fuel) => {
                    this.add_fuel = false;
                    this.add_fuel_other = false;
                    this.editing = false;
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
            ShowReversal
        }
    }
</script>

<style scoped>

</style>
