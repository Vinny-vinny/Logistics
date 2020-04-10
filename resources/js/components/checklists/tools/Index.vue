<template>
    <div>
        <tool v-if="add_tool" :edit="editing"></tool>
        <!-- Main content -->
        <section class="content" v-if="!add_tool">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Checklist Tools
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_tool=true">Add Checklist Tool
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
                                        <v-icon dark small @click="editTool(item)">mdi-pencil</v-icon>
                                    </v-btn>
                                    <span style="display: none">
                                     <v-btn class="mx-1 my-1" fab dark color="pink" small>
                                        <v-icon dark small @click="deleteTool(item.id)">mdi-delete</v-icon>
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
    import Tool from "./Tool";
    import {mapGetters} from "vuex";
    import FieldDefs from "./FieldDefs";
    import datatable from "../../../mixins/datatable";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_tool: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getTools();
        },
       computed:{
         ...mapGetters({
            tableData:'all_checklist_tools'
         })
       },
        methods:{
            getTools(){
                this.$store.dispatch('my_checklist_tools').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }
                })
            },
            editTool(tool){
                this.$store.dispatch('updateTool',tool)
                    .then(() =>{
                        this.editing=true;
                        this.add_tool=true;
                    })
            },
            deleteTool(id){
                axios.delete(`checklist-tool/${id}`)
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
                eventBus.$on('listTools',(tool) =>{
                    this.getItems();
                    this.add_tool =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_tool = false;
                    this.editing = false;
                });
                eventBus.$on('updateTool',(tool)=>{
                    this.add_tool = false;
                    this.editing = false;
                    const index = this.tableData.map(t => t.id).indexOf(tool.id);
                    this.tableData.splice(index,1);
                    this.tableData.unshift(tool);
                });
            }
        },
        components:{
            Tool
        }
    }
</script>

<style scoped>

</style>
