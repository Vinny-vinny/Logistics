<template>
    <div>
        <fuel v-if="add_fuel" :edit="editing" :other_fuel="add_fuel_other"
              :add_fuel="add_fuel"></fuel>
             <reversal v-if="show_reversal" :reverse="show_reversal"></reversal> 
        <!-- Main content -->
        <section class="content" v-if="!add_fuel && !add_fuel_other && !show_reversal">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fueling</h3>
                    <button class="btn btn-primary pull-right" @click="add_fuel_other=true" style="display:none">Add Other Fuel</button>
                    <button class="btn btn-primary pull-right mr" @click="add_fuel=true">Add Fuel</button>

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
                                <router-link :to="{path: '/fuel/'+fuel.id}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></router-link>
                                <button v-if="!fuel.reversal_ref && fuel.fuel_category_id=='stock_issue'" class="btn btn-danger btn-sm" @click="reverseFuel(fuel)"><i class="fa fa-undo" aria-hidden="true"></i></button>
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

    export default {
        data() {
            return {
                tableData: [],
                add_fuel: false,
                add_fuel_other: false,
                editing: false,
                show_reversal:false
            }
        },
        created() {
             this.getFuels();
        },
        mounted() {
            this.listen();
            this.initDatable();
        },
        methods: {
            reverseFuel(fuel){
             this.$store.dispatch('updateFuel',fuel)
                    .then(() =>{                       
                        this.show_reversal = true;
                        this.add_fuel=false;
                        })
            },
            getFuels() {
                axios.get('fuel')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
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
                    this.show_reversal = false;
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
            Reversal
        }
    }
</script>

<style scoped>

</style>
