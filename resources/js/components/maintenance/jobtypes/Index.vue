<template>
    <div>
        <job-type v-if="add_job_type" :edit="editing"></job-type>
        <!-- Main content -->
        <section class="content" v-if="!add_job_type">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Job Types
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_job_type=true">Add Job Type
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
                                        <v-icon dark small @click="editJobType(item)">mdi-pencil</v-icon>
                                    </v-btn>
                                    <span style="display:none">
                                     <v-btn class="mx-1 my-1" fab dark color="pink" small>
                                        <v-icon dark small @click="deleteJobType(item.id)">mdi-delete</v-icon>
                                    </v-btn>
                                    </span>
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
    import JobType from "./JobType";
    import {mapGetters} from "vuex";
    import datatable from "../../../mixins/datatable";
    import FieldDefs from "./FieldDefs";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_job_type: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getJobTypes();
        },
       computed:{
         ...mapGetters({
             tableData:'all_job_types'
         })
       },
        methods:{
            getJobTypes(){
                this.$store.dispatch('my_job_types').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }
                })
            },
            editJobType(job){
                this.$store.dispatch('updateJobType',job)
                    .then(() =>{
                        this.editing=true;
                        this.add_job_type=true;
                    })
            },
            deleteJobType(id){
                axios.delete(`job-types/${id}`)
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
                eventBus.$on('listJobTypes',(job) =>{
                    this.getItems();
                    this.add_job_type =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_job_type = false;
                    this.editing = false;
                });
                eventBus.$on('updateJobType',(job)=>{
                    this.add_job_type = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == job.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(job);
                });
            }
        },
        components:{
            JobType
        }
    }
</script>

<style scoped>

</style>
