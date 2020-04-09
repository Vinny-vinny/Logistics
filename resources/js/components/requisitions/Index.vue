<template>
    <div>
        <requisition v-if="add_requisition && !show_form && !show_reversal && !show_req_form" :edit="editing"></requisition>
        <reversal v-if="!add_requisition && !show_form && show_reversal && !show_req_form" :reverse="reversing"></reversal>
        <requisition-form v-if="show_form && !add_requisition && !show_reversal && !show_req_form"></requisition-form>
        <req-form v-if="show_req_form && !show_form && !add_requisition && !show_reversal"></req-form>
        <!-- Main content -->
        <section class="content" v-if="!add_requisition && !show_form && !show_reversal && !show_req_form">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                       <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Requisitions
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="show_req_form=true" v-if="machines.length > 1">Print Requisition Card Form
                                </v-btn>
                                <v-btn small color="indigo" dark @click="add_requisition=true" class="mr" v-if="pricelists.length > 1">Add Requisition
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
                                        <v-icon dark small @click="editRequisition(item)">mdi-pencil</v-icon>
                                    </v-btn>
                                    <router-link :to="{path:'/requisition/'+item.id}">
                                        <v-btn class="mx-1 my-1" fab dark color="cyan" small>
                                            <v-icon dark small>mdi-eye</v-icon>
                                        </v-btn>
                                    </router-link>
                                    <v-btn class="mx-1 my-1" fab dark color="pink" small v-if="!item.reversal_ref && item.type=='Internal' && item.used==1">
                                        <v-icon dark small @click="reverseRequisition(item)">mdi-undo</v-icon>
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
    import Requisition from "./Requisition";
    import RequisitionForm from "./RequisitionForm";
    import Reversal from "./Reversal";
    import ReqForm from "./ReqForm";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import datatable from "../../mixins/datatable";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_requisition: false,
                editing: false,
                show_form:false,
                show_reversal:false,
                show_req_form:false,
                projects:{},
                users:{},
                customers:{},
                check_customers:false,
                check_accounts:false,
                check_prices:false,
                check_parts:false,
                show_add_txt:false,
                headers: FieldDefs
            }
        },
        created(){
            this.getReqs;
            this.getAllDetails();
            this.listen();

            },
        computed:{
            ...mapGetters({
                parts:'all_parts',
                pricelists:'all_pricelists',
                tableData:'all_reqs',
                machines:'all_vehicles'
            }),
            getReqs(){
                this.$store.dispatch('my_reqs').then(() => {
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
                this.$store.dispatch('my_reqs').then(() =>{
                    this.initDatable();
                });
            },
            reverseRequisition(rq){
            this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                        if (this.pricelists.length > 1 && this.parts.length > 1){
                            this.reversing=true;
                            this.show_reversal = true;
                            this.add_requisition=false;
                        }

                    })
            },
            editRequisition(rq){
                this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                        if (this.pricelists.length > 1 && this.parts.length > 1){
                            this.show_add_txt = false;
                            this.add_requisition=true
                            this.editing=true;
                        }
                    })
            },
            deleteRequisition(id){
                axios.delete(`requisitions/${id}`)
                    .then(res => {
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            listen(){
                eventBus.$on('listReqs',(rq) =>{
                    this.tableData.unshift(rq);
                    this.add_requisition =false;
                    this.show_add_txt = false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_requisition = false;
                    this.editing = false;
                    this.reversing = false;
                    this.show_reversal = false;
                    this.show_add_txt = false;
                    //this.getRequisitions();
                    this.initDatable();
                });
                eventBus.$on('close_req_form',(req) => {
                  this.show_req_form = false
                  this.show_form = true;
                });
                eventBus.$on('updateRequisition',(rq)=>{
                    this.add_requisition = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.show_add_txt = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == rq.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(rq);
                    this.initDatable();
                });
                eventBus.$on('hide_form',() =>{
                this.show_form = false;
              //  this.getRequisitions();
                })
            },
            initDatable(){
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
                },2000)
            },
        },
        components:{
            Requisition,
            RequisitionForm,
            Reversal,
            ReqForm
        }
    }
</script>

<style scoped>
    .fz{
    font-size: 10px;
}
</style>
