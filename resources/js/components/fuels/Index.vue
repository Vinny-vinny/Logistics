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
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Fueling
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_fuel=true" v-if="parts.length > 1">Add Fuel
                                </v-btn>
                            </v-card-title>
                            <v-data-table
                                v-model="selected"
                                :headers="headers"
                                :items="items"
                                :single-select="singleSelect"
                                :sort-by.sync="sortBy"
                                :sort-desc.sync="sortDesc"
                                :search="search"
                                item-key="name"
                                class="elevation-1"
                                :footer-props="{
                              showFirstLastPage: true,
                              firstIcon: 'mdi-arrow-collapse-left',
                              lastIcon: 'mdi-arrow-collapse-right',
                              prevIcon: 'mdi-minus',
                              nextIcon: 'mdi-plus'
                              }"
                            >
                                <template v-slot:item.actions="{ item }">
                                    <v-btn class="mx-1 my-1" fab dark color="indigo" small>
                                        <v-icon dark small @click="editFuel(item)">mdi-pencil</v-icon>
                                    </v-btn>
                                        <v-btn class="mx-1 my-1" fab dark color="cyan" small>
                                            <v-icon dark small @click="showPrint(item)">mdi-eye</v-icon>
                                        </v-btn>
                                    <v-btn class="mx-1 my-1" fab dark color="pink" small  v-if="!item.reversal_ref && item.fuel_category_id=='stock_issue' && item.status !=0">
                                        <v-icon dark small @click="reverseFuel(item)">mdi-undo</v-icon>
                                    </v-btn>

                                </template>
                            </v-data-table>
                        </v-card>
                    </v-app>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import Fuel from "./Fuel";
    import Reversal from "./Reversal";
    import ShowFuel from "./ShowFuel";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import datatable from "../../mixins/datatable";
    export default {
        mixins:[datatable],
        data() {
            return {
                add_fuel: false,
                add_fuel_other: false,
                editing: false,
                show_reversal:false,
                show_print:false,
                show_add_text:false,
                check_customers:false,
                check_accounts:false,
                check_parts:false,
                headers: FieldDefs
            }
        },
        created() {
         this.getFuels;
         this.getAllDetails();
         this.listen();
            },
        computed:{
        ...mapGetters({
            tableData:'all_fuels',
            parts:'all_parts'
        }),
            getFuels(){
                this.$store.dispatch('my_fuels').then(() => {
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }else {
                        this.getItems();
                    }
                })
            },
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
            } ,
            reverseFuel(fuel){
             this.$store.dispatch('updateFuel',fuel)
                    .then(() =>{
                    this.show_reversal = true;
                    this.add_fuel = false;
                        })
            },
            showPrint(fuel){
             //this.$store.dispatch('updateCustomer',this.$store.state.all_my_customers.find(c => c.id === fuel.customer_id));
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
                });
                eventBus.$on('cancel', () => {
                    this.add_fuel = false;
                    this.add_fuel_other = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.show_print = false;
                    this.show_add_text = false;
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
                });
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
