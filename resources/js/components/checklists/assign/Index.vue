<template>
    <div>
        <assign-checklist v-if="add_checklist" :edit="editing"></assign-checklist>
        <!-- Main content -->
        <section class="content" v-if="!add_checklist">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Assign Checklists
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_checklist=true" v-if="jobs.length > 0">Add New
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
                                    <v-btn class="mx-1 my-1" fab dark color="indigo" small v-if="jobs.length > 0">
                                        <v-icon dark small @click="editChecklist(item)">mdi-pencil</v-icon>
                                    </v-btn>
                                    <span>
                                     <v-btn class="mx-1 my-1" fab dark color="pink" small style="display: none">
                                        <v-icon dark small @click="deleteChecklist(item.id)">mdi-delete</v-icon>
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
    import AssignChecklist from "./AssignChecklist";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import datatable from "../../../mixins/datatable";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_checklist: false,
                editing: false,
                headers:FieldDefs
            }
        },
        created(){
            this.listen();
            this.ssignChecklists();
            this.getDetails();
        },
       computed:{
         ...mapGetters({
             tableData:'all_assign_checklists',
             jobs:'all_jobs'
         })
       },
        methods:{
            getDetails(){
              this.$store.dispatch('my_vehicles');
              this.$store.dispatch('my_expiry_types');
              this.$store.dispatch('my_checklists');
              this.$store.dispatch('my_checklists');
              this.$store.dispatch('my_jobcards');
            },
            ssignChecklists(){
                this.$store.dispatch('assign_checklists').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }
                })
            },
            editChecklist(checklist){
                this.$store.dispatch('updateAssignChecklist',checklist)
                    .then(() =>{
                        this.editing=true;
                        this.add_checklist=true;
                    })
            },
            deleteChecklist(id){
                axios.delete(`assign-checklist/${id}`)
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
                eventBus.$on('listAssignChecklists',(checklist) =>{
                    this.getItems();
                    this.add_checklist =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_checklist = false;
                    this.editing = false;
                });
                eventBus.$on('updateAssignChecklist',(checklist)=>{
                    this.add_checklist = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == checklist.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(checklist);
                });
            }
        },
        components:{
            AssignChecklist
        }
    }
</script>

<style scoped>

</style>
