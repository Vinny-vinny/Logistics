<template>
    <div>
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                All Items
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
                                        <v-icon dark small @click="editItem(item)">mdi-plus</v-icon>
                                    </v-btn>
                                    <v-btn class="mx-1 my-1" fab dark color="pink" small>
                                        <v-icon dark small>mdi-delete</v-icon>
                                    </v-btn>
                                    <v-btn class="mx-1 my-1" fab dark color="cyan" small>
                                        <v-icon dark small>mdi-eye</v-icon>
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

    import FieldDefs from "./FieldDefs";
    import datatable from "../../../mixins/datatable";

    export default {
        mixins: [datatable],
        data() {
            return {
                items: [],
                loading: true,
                singleSelect: false,
                selected: [],
                headers: FieldDefs
            }
        },

        computed: {
            tableData() {
                return this.$store.state.all_my_vehicles;
            }
        },

        methods:{
            editItem(item){
               console.log(item)
            },
            deleteItem(item){
                console.log(item)
            }
        }

    }
</script>

<style>
    .v-btn--fab.v-size--small{
        height:22px !important;
        width:22px !important;
    }
    .pagination {
        margin-top: 1rem;
    }

    .vuetable-head-wrapper table.vuetable th.sortable {
        cursor: pointer
    }
</style>
