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
                                <button class="btn btn-info btn-sm" @click="editFuel(fuel)" v-if="parts.length"><i
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
    import {mapGetters} from "vuex";

    export default {
        data() {
            return {
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
            this.getAllDetails();
           },
        mounted() {
            this.listen();
        },
        computed:{
            ...mapGetters({
                all_fuels:'all_fuels',
                parts:'all_parts'
            }),
            tableData(){
             return this.all_fuels.filter(f => f.reversal_ref !=='' && f.reversal_ref !==null);
            }
        },
        methods: {
            getAllDetails(){
                this.$store.dispatch('my_parts');
                this.$store.dispatch('my_customers');
                this.$store.dispatch('my_vehicles');
                this.$store.dispatch('my_accounts');
                this.$store.dispatch('my_users');
                this.$store.dispatch('my_customer_types');
                this.$store.dispatch('my_jobcards');
                this.$store.dispatch('my_projects');
                this.$store.dispatch('my_stk_groups');
                this.$store.dispatch('my_fuels').then(() =>{
                    this.initDatable();
                });
            },
            editFuel(fuel) {
                this.$store.dispatch('updateFuel', fuel)
                    .then(() => {
                      this.show_reversal = true;
                    })
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
