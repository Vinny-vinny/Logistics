<template>
   <div>
       <service-type v-if="add_service" :edit="editing"></service-type>
        <!-- Main content -->
       <section class="content" v-if="!add_service">
           <!-- Default box -->
           <div class="box">
               <div class="box-body">
                   <v-app id="inspire">
                       <v-card>
                           <v-card-title>
                               Service Types
                               <v-spacer></v-spacer>
                               <v-text-field
                                   v-model="search"
                                   append-icon="mdi-magnify"
                                   label="Search"
                                   single-line
                                   hide-details
                               ></v-text-field>
                               <v-spacer></v-spacer>
                               <v-btn small color="indigo" dark @click="add_service=true">Add Service Type
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
                                       <v-icon dark small @click="editService(item)">mdi-pencil</v-icon>
                                   </v-btn>
                                   <span style="display: none">
                                     <v-btn class="mx-1 my-1" fab dark color="pink" small>
                                        <v-icon dark small @click="deleteService(item.id)">mdi-delete</v-icon>
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
    import ServiceType from "./ServiceType";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import datatable from "../../../mixins/datatable";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_service: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getServices();
        },
       computed:{
         ...mapGetters({
             tableData:'all_service_types'
         })
       },
        methods:{
            getServices(){
                this.$store.dispatch('my_service_types').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }
                })
            },
            editService(service){
                this.$store.dispatch('updateServiceType',service)
                    .then(() =>{
                        this.editing=true;
                        this.add_service=true;
                    })
            },
            deleteService(id){
                axios.delete(`service-types/${id}`)
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
                eventBus.$on('listServiceTypes',(service) =>{
                    this.getItems();
                    this.add_service =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_service = false;
                    this.editing = false;
                });
                eventBus.$on('updateServiceType',(service)=>{
                    this.add_service = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == service.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(service);
                });
                },
        },
        components:{
            ServiceType
        }
    }
</script>

<style scoped>

</style>
