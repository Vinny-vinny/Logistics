<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Unit of Measures
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="cyan"  @click="importUoms()" :disabled="importing">
                                    {{importing ? 'Importing...' : 'Import from Sage'}}
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
                            ></v-data-table>
                        </v-card>
                    </v-app>
                </div>
            </div>
        </section>
    </div>
</template>
<script>

    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import datatable from "../../mixins/datatable";

    export default {
        mixins:[datatable],
        data(){
            return {
               importing:false,
               headers: FieldDefs
            }
        },
        created(){
            this.getUoms;
        },
        computed:{
            ...mapGetters({
               tableData:'all_units'
            }),
            getUoms(){
                this.$store.dispatch('my_uoms').then(() => {
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

        methods:{
            importUoms(){
                this.importing = true;
                    axios.get(`import-uom`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Uoms successfully imported.');
                        this.$router.go();
                    })
            },
        },

    }
</script>

<style scoped>

</style>
