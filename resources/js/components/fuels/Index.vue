<template>
    <div>
        <fuel v-if="add_fuel || add_fuel_other" :edit="editing" :other_fuel="add_fuel_other"
              :add_fuel="add_fuel"></fuel>
        <!-- Main content -->
        <section class="content" v-if="!add_fuel && !add_fuel_other">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fueling</h3>
                    <button class="btn btn-primary pull-right" @click="add_fuel_other=true">Add Other Fuel</button>
                    <button class="btn btn-primary pull-right mr" @click="add_fuel=true">Add Company Asset Fuel</button>

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
                            <td>{{fuel.id}}</td>
                            <td>{{fuel.vehicle}}</td>
                            <td>{{fuel.litres}}</td>
                            <td>{{fuel.fuel_type}}</td>
                            <td>{{fuel.price}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editFuel(fuel)"><i
                                    class="fa fa-edit"></i></button>
                                <router-link :to="{path: '/fuel/'+fuel.id}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i></router-link>
<!--                                <button class="btn btn-danger btn-sm" @click="deleteFuel(fuel.id)"><i-->
<!--                                    class="fa fa-trash"></i></button>-->
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

    export default {
        data() {
            return {
                tableData: [],
                add_fuel: false,
                add_fuel_other: false,
                editing: false
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
            Fuel
        }
    }
</script>

<style scoped>

</style>
