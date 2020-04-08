<template>
    <div>
        <parts v-if="add_part" :edit="editing"></parts>
        <!-- Main content -->
        <section class="content" v-if="!add_part">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Parts
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="cyan"  @click="importParts()" :disabled="importing">
                                    {{importing ? 'Importing...' : 'Import from Sage'}}
                                </v-btn>
                                <v-btn small color="indigo" dark @click="add_part=true" class="mr">Add Part
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
                                        <v-icon dark small @click="editPart(item)">mdi-pencil</v-icon>
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
    import Parts from "./Parts";
    import FieldDefs from "./FieldDefs";
    import datatable from "../../mixins/datatable";
    import {mapGetters} from "vuex";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_part: false,
                editing: false,
                importing:false,
                warehouse:'',
                show_warehouse:false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getParts();
        },
       computed:{
         ...mapGetters({
             tableData:'all_parts'
         })
       },
        methods:{
            importParts(){
                this.importing = true;
                axios.get(`import-parts`)
                    .then(res =>{
                       this.importing = false;
                       this.$toastr.s('Parts successfully imported.');
                        this.$router.go();
                    })
            },
            getParts(){
                this.$store.dispatch('my_parts').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            console.log(this.$store.all_my_parts)
                            this.getItems();
                        }, 5000);
                    }
                })
            },
            editPart(part){
                this.$store.dispatch('updatePart',part)
                    .then(() =>{
                        this.editing=true;
                        this.add_part=true;
                    })
            },
            deletePart(id){
                axios.delete(`parts/${id}`)
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
                eventBus.$on('listParts',(part) =>{
                    this.getItems();
                    this.add_part =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_part = false;
                    this.editing = false;
                });
                eventBus.$on('updateParts',(part)=>{
                    this.add_part = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == part.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(part);
                });
            },

        },
        components:{
            Parts
        }
    }
</script>

<style scoped>

</style>
