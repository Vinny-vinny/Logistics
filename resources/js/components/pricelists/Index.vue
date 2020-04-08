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
                                Ledgers
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="cyan"  @click="importPrices()" :disabled="importing">
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
                            >
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
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";

    export default {
        mixins:[datatable],
        data(){
            return {
               importing:false,
                headers: FieldDefs
            }
        },
        created(){
            this.getPriceLists();
        },
       computed:{
         ...mapGetters({
           tableData:'all_pricelists'
         })
       },
        methods:{
            getPriceLists(){
                this.$store.dispatch('my_pricelists').then(() => {
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            console.log(this.$store.getters.all_pricelists)
                            this.getItems();
                        }, 5000);
                    }else {
                      this.getItems();
                    }
                })
            },
            importPrices(){
                this.importing = true;
                    axios.get(`import-price-list`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Price Lists successfully imported.');
                        this.$router.go();
                    })
            },
        },

    }
</script>

<style scoped>

</style>
