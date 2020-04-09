<template>
    <div>
        <requisition v-if="add_requisition && !show_form" :edit="editing"></requisition>
        <!-- Main content -->
        <section class="content" v-if="!add_requisition && !show_form && !show_reversal">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Reversed Requisitions
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
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
                                    <v-btn class="mx-1 my-1" fab dark color="cyan" small>
                                     <v-icon dark small @click="editRequisition(item)" v-if="pricelists.length > 1">mdi-eye</v-icon>
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
    import Requisition from "./ShowReversal";
    import {mapGetters} from "vuex";
    import RevFieldDefs from "./RevFieldDefs";
    import datatable from "../../mixins/datatable";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_requisition: false,
                editing: false,
                show_form:false,
                show_reversal:false,
                check_customers:false,
                check_accounts:false,
                check_prices:false,
                check_parts:false,
                show_add_txt:false,
                headers: RevFieldDefs
            }
        },
        created(){
            this.getReqs;
            this.getAllDetails();
            this.listen();
            },
        computed:{
            ...mapGetters({
                pricelists:'all_pricelists',
                parts:'all_parts',
                reqs:'all_reqs'
            }),
            getReqs(){
                this.$store.dispatch('my_reqs').then(() => {
                    if (this.reqs.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }else {
                        this.getItems();
                    }
                })
            },
            tableData(){
                if (this.reqs.length > 0){
                 return  this.reqs.filter(req => req.reversal_ref !=='' && req.reversal_ref !==null);
                }
            }
        },
        methods:{
            getAllDetails(){
                this.$store.dispatch('my_parts');
                this.$store.dispatch('my_customers');
                this.$store.dispatch('my_accounts');
                this.$store.dispatch('my_pricelists');
                this.$store.dispatch('my_uoms');
                this.$store.dispatch('my_vehicles');
                this.$store.dispatch('my_stk_groups');
                this.$store.dispatch('my_users');
                this.$store.dispatch('my_projects');

                },

            editRequisition(rq){
                this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                            this.editing=true;
                            this.add_requisition=true;
                      })
            },
            listen(){
                eventBus.$on('listReqs',(rq) =>{
                    this.tableData.unshift(rq);
                    this.add_requisition =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_requisition = false;
                    this.editing = false;
                    this.reversing = false;
                    this.show_reversal = false;
                });
                eventBus.$on('updateRequisition',(rq)=>{
                    this.add_requisition = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == rq.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(rq);
                });
                eventBus.$on('hide_form',() =>{
                this.show_form = false;

                })
            }
        },
        components:{
            Requisition,

        }
    }
</script>

<style scoped>

</style>
