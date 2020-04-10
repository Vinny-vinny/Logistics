<template>
    <div>
        <checklist v-if="add_checklist" :edit="editing"></checklist>
        <!-- Main content -->
        <section class="content" v-if="!add_checklist">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Checklists
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_checklist=true"  v-if="tools.length > 0">Add Checklist
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
                                    <v-btn class="mx-1 my-1" fab dark color="indigo" small v-if="tools.length">
                                        <v-icon dark small @click="editChecklist(item)">mdi-pencil</v-icon>
                                    </v-btn>
                                     <v-btn class="mx-1 my-1" fab dark color="pink" small>
                                        <v-icon dark small @click="deleteChecklist(item.id)">mdi-delete</v-icon>
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
   import Checklist from "./Checklist";
   import FieldDefs from "./FieldDefs";
   import datatable from "../../mixins/datatable";
   import {mapGetters} from "vuex";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_checklist: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getChecklists();
            this.getDetails();
        },
        computed:{
          ...mapGetters({
              tableData:'all_checklists',
              tools:'all_checklist_tools'
          })
        },
        methods:{
            getDetails(){
              this.$store.dispatch('my_checklist_tools');
              this.$store.dispatch('my_expiry_types');
            },
            //download File function
                downloadFile(file) {
                    return console.log(file.split('.').pop());
                axios({
                    url: '/assets/images/sort_desc.png',
                    method: 'GET',
                    responseType: 'blob',
                }).then((response) => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');

                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'file.png');
                    document.body.appendChild(fileLink);
                    fileLink.click();
                });
            },

            getChecklists(){
                this.$store.dispatch('my_checklists').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }
                })
            },
            editChecklist(checklist){
                      this.$store.dispatch('updateChecklist',checklist)
                    .then(() =>{
                        this.editing=true;
                        this.add_checklist=true;
                    })
            },
            deleteChecklist(id){
                axios.delete(`checklists/${id}`)
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
                eventBus.$on('listChecklists',(checklist) =>{
                    this.getItems();
                    this.add_checklist =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_checklist = false;
                    this.editing = false;
                });
                eventBus.$on('updateChecklist',(checklist)=>{
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
            Checklist
        }
    }
</script>

<style scoped>

</style>
