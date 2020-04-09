<template>
    <div>
        <show-reversal v-if="show_reversal" :reverse="show_reversal"></show-reversal>
        <!-- Main content -->
        <section class="content" v-if="!show_reversal">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Fuel Reversals
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>

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
                                        <v-icon dark small @click="editFuel(item)" v-if="parts.length > 1">mdi-eye</v-icon>
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
    import ShowReversal from "./ShowReversal";
    import {mapGetters} from "vuex";
    import RevDefFields from "./RevDefFields";
    import datatable from "../../mixins/datatable";

    export default {
        mixins:[datatable],
        data() {
            return {
                add_fuel: false,
                add_fuel_other: false,
                editing: false,
                show_reversal:false,
                check_customers:false,
                check_accounts:false,
                check_parts:false,
                headers: RevDefFields
            }
        },
        created() {
            this.getFuels
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
            },
            getFuels(){
            this.$store.dispatch('my_fuels').then(() => {
                if (this.all_fuels.length == undefined) {
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
