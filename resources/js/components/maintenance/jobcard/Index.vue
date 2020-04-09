<template>
    <div>
        <job-card v-if="add_jobcard" :edit="editing"></job-card>
        <job-form v-if="show_form" :printJob="show_form"></job-form>
        <reversal v-if="show_reversal" :reverse="show_reversal"></reversal>
        <!-- Main content -->
        <section class="content" v-if="!add_jobcard && !show_form && !show_reversal">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Job Card
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="show_form=true" v-if="parts.length > 1">Print Job Card Form
                                </v-btn>
                                <v-btn small color="indigo" dark @click="add_jobcard=true" class="mr" v-if="parts.length > 1">Add Job Card
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
                                        <v-icon dark small @click="editJobcard(item)">mdi-pencil</v-icon>
                                    </v-btn>
                                    <router-link :to="{path:'/job-card/'+item.id}">
                                        <v-btn class="mx-1 my-1" fab dark color="cyan" small>
                                            <v-icon dark small>mdi-eye</v-icon>
                                        </v-btn>
                                    </router-link>
                                    <v-btn class="mx-1 my-1" fab dark color="pink" small v-if="!item.reversal_ref && item.invoiced==1">
                                        <v-icon dark small @click="reverseJob(item)">mdi-undo</v-icon>
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
    import JobCard from "./JobCard";
    import JobForm from "./JobForm";
    import Reversal from "./Reversal";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import datatable from "../../../mixins/datatable";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_jobcard: false,
                editing: false,
                show_form:false,
                show_reversal:false,
                check_customers:false,
                check_parts:false,
                show_add_text:false,
                headers: FieldDefs
            }
        },
        created(){
            this.getJobs;
            this.getAllDetails();
            this.listen();
        },
        computed:{
           ...mapGetters({
               tableData:'all_jobs',
               parts:'all_parts',
               machines:'all_vehicles'
           }),
            getJobs(){
                this.$store.dispatch('my_vehicles').then(() => {
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
               this.$store.dispatch('my_jobcards');
               this.$store.dispatch('my_parts');
               this.$store.dispatch('my_customers');
               this.$store.dispatch('my_vehicles');
               this.$store.dispatch('my_users');
               this.$store.dispatch('my_job_types');
               this.$store.dispatch('my_job_categories');
               this.$store.dispatch('my_customer_types');
               this.$store.dispatch('my_service_types');
               this.$store.dispatch('my_tracks');
               this.$store.dispatch('my_projects');
               this.$store.dispatch('my_categories');
               this.$store.dispatch('my_mechanics');
               this.$store.dispatch('my_reqs');
           } ,
            reverseJob(rq){
            this.$store.dispatch('updateJobcard',rq)
                    .then(() =>{
                        if (this.parts !==undefined) {
                            this.show_reversal = true;
                            this.add_jobcard = false;
                        }
                    })
            },
            editJobcard(job){
                this.$store.dispatch('updateJobcard',job)
                    .then(() =>{
                        if (this.parts !==undefined){
                                this.editing=true;
                                this.add_jobcard=true;
                            }
                    })

            },
            deleteJobcard(id){
                axios.delete(`job-card/${id}`)
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
                eventBus.$on('listJobcards',(job) =>{
                    this.tableData.unshift(job);
                    this.add_jobcard =false;
                    this.show_add_text=false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_jobcard = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.show_add_text=false;
                });
                eventBus.$on('updateJobcard',(job)=>{
                    this.add_jobcard = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.show_add_text=false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == job.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(job);
                });
                eventBus.$on('close_form',() => {
                    this.show_form = false;
                });
                eventBus.$on('cancel_job',() =>{
                    this.show_form = false;
                });
            },
        },
        components:{
            JobCard,
            JobForm,
            Reversal
        }
    }
</script>

<style scoped>

</style>
