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
                                <v-btn small color="cyan"  @click="importAccounts()" :disabled="importing">
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
            this.getAccounts();
        },
        computed:{
          ...mapGetters({
             tableData:'all_accounts'
          })
        },
        methods:{
            getAccounts(){
                this.$store.dispatch('my_accounts').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 1000);
                    }
                })
            },
            importAccounts(){
                this.importing = true;
                    axios.get(`import-accounts`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Ledgers successfully imported.');
                        this.$router.go();
                    })
            },

        },

    }
</script>

<style scoped>

</style>
