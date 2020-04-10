<template>
    <div>
        <category v-if="add_category" :edit="editing"></category>
        <!-- Main content -->
        <section class="content" v-if="!add_category">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Jobcard Categories
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_category=true" v-if="transactions.length > 0">Add Category
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
                                    <v-btn class="mx-1 my-1" fab dark color="indigo" small v-if="transactions.length > 0">
                                        <v-icon dark small @click="editCategory(item)">mdi-pencil</v-icon>
                                    </v-btn>
                                    <span style="display: none">
                                     <v-btn class="mx-1 my-1" fab dark color="pink" small>
                                        <v-icon dark small @click="deleteCategory(item.id)">mdi-delete</v-icon>
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
   import Category from "./Category";
   import FieldDefs from "./FieldDefs";
   import datatable from "../../../../mixins/datatable";
   import {mapGetters} from "vuex";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_category: false,
                editing: false,
                headers:FieldDefs
            }
        },
        created(){
            this.listen();
            this.getCategories();
            this.$store.dispatch('my_transactions');
        },
       computed:{
         ...mapGetters({
           tableData:'all_job_categories',
           transactions:'all_transactions'
         })
       },
        methods:{
            getCategories(){
                this.$store.dispatch('my_job_categories').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }
                })
            },
            editCategory(category){
                this.$store.dispatch('updateJobcardCategory',category)
                    .then(() =>{
                        this.editing=true;
                        this.add_category=true;
                    })
            },
            deleteCategory(id){
                axios.delete(`jobcard-category/${id}`)
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
                eventBus.$on('listJobcardCategories',(category) =>{
                    this.getItems();
                    this.add_category =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_category = false;
                    this.editing = false;
                });
                eventBus.$on('updateJobcardCategory',(category)=>{
                    this.add_category = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == category.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(category);
                });
            }
        },
        components:{
            Category
        }
    }
</script>

<style scoped>

</style>
