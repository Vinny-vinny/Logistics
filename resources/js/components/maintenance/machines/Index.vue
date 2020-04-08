<template>
    <div>
        <machines v-if="add_machine" :edit="editing"></machines>
        <!-- Main content -->
        <section class="content" v-if="!add_machine">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Machines
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="importMachines()" :disabled="importing" style="display: none">
                                    {{importing ? 'Importing...' : 'Import from Sage'}}
                                </v-btn>
                                <v-btn small color="indigo" dark @click="add_machine=true" class="mr" style="display: none">Add Machine
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
                                        <v-icon dark small @click="editMachine(item)">mdi-pencil</v-icon>
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
    import Machines from "./Machines";
    import FieldDefs from "./FieldDefs";
    import datatable from "../../../mixins/datatable";
    import {mapGetters} from "vuex";

    export default {
        mixins: [datatable],
        data() {
            return {
                add_machine: false,
                editing: false,
                importing: false,
                headers: FieldDefs,
            }
        },
        created() {
            this.getDetails();
            this.listen();
        },
        computed: {
            ...mapGetters({
                tableData: 'all_vehicles'
            })
        },
        methods: {
            getDetails() {
                this.$store.dispatch('my_vehicles').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 1000);
                    }
                })
            },
            editMachine(machine) {
                this.$store.dispatch('updateMachine', machine)
                    .then(() => {
                        this.editing = true;
                        this.add_machine = true;
                    })
            },
            deleteMachine(id) {
                axios.delete(`machines/${id}`)
                    .then(res => {
                        for (let i = 0; i < this.tableData.length; i++) {
                            if (this.tableData[i].id == res.data) {
                                this.tableData.splice(i, 1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            importMachines() {
                this.importing = true;
                axios.get('import-machines')
                    .then(machines => {
                        if (machines.data.length) {
                            this.$toastr.s('Machines imported successfully.');

                        }
                        this.importing = false;
                        this.$router.go();

                    })
            },
            listen() {
                eventBus.$on('listMachines', (machine) => {
                    this.getItems();
                    this.add_machine = false;
                });
                eventBus.$on('cancel', () => {
                    this.add_machine = false;
                    this.editing = false;
                });
                eventBus.$on('updateMachine', (machine) => {
                    this.add_machine = false;
                    this.editing = false;
                    for (let i = 0; i < this.tableData.length; i++) {
                        if (this.tableData[i].id == machine.id) {
                            this.tableData.splice(i, 1);
                        }
                    }
                    this.tableData.unshift(machine);
                });
            },
        },
        components: {
            Machines
        }
    }
</script>

<style>
    .mr {
        margin-left: 10px;
    }
</style>
