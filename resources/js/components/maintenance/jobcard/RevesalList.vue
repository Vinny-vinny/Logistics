<template>
    <div>
        <show-reversal v-if="show_reversal" :reverse="show_reversal"></show-reversal>
        <!-- Main content -->
        <section class="content" v-if="!add_jobcard && !show_form && !show_reversal">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Reversed Job Cards
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
                                    <v-btn class="mx-1 my-1" fab dark color="cyan" small>
                                        <v-icon dark small @click="editJobcard(item)" v-if="parts.length > 1">mdi-eye</v-icon>
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
    import datatable from "../../../mixins/datatable";
    import RevFieldDefs from "./RevFieldDefs";

    export default {
        mixins:[datatable],
        data(){
            return {
                add_jobcard: false,
                editing: false,
                show_form:false,
                show_reversal:false,
                headers: RevFieldDefs
            }
        },
        created(){
        this.getAllDetails();
        this.listen();
        this.getJobs;
        },
        computed:{
          ...mapGetters({
              jobs:'all_jobs',
              parts:'all_parts'
          }),
            getJobs(){
                this.$store.dispatch('my_vehicles').then(() => {
                    if (this.jobs.length == undefined) {
                          setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }else {
                        this.getItems();
                    }
                })
            },
            tableData(){
              if (this.jobs.length > 0){
                return this.jobs.filter(job => job.reversal_ref !=='' && job.reversal_ref !==null);
              }else {
                  return [];
              }
            },

        },
        methods:{
            getAllDetails(){
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
                this.$store.dispatch('my_jobcards');
            } ,

            editJobcard(job){
                this.$store.dispatch('updateJobcard',job)
                    .then(() =>{
                            this.editing = true;
                            this.add_jobcard = false;
                            this.show_reversal = true;

                    })

            },

            listen(){
                eventBus.$on('listJobcards',(job) =>{
                    this.tableData.unshift(job);
                    this.add_jobcard =false;

                });
                eventBus.$on('cancel',()=>{
                    this.add_jobcard = false;
                    this.editing = false;
                    this.show_reversal = false;

                });
                eventBus.$on('updateJobcard',(job)=>{
                    this.add_jobcard = false;
                    this.editing = false;
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
            ShowReversal
        }
    }
</script>

<style scoped>

</style>
