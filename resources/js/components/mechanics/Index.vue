<template>
    <div>
        <mechanics v-if="add_mechanic" :edit="editing"></mechanics>
        <!-- Main content -->
        <section class="content" v-if="!add_mechanic">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Mechanics
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_mechanic=true">Add Mechanic
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
                                        <v-icon dark small @click="editMechanic(item)">mdi-pencil</v-icon>
                                    </v-btn>
                                     <v-btn class="mx-1 my-1" fab dark color="pink" small>
                                        <v-icon dark small @click="deleteMechanic(item.id)">mdi-delete</v-icon>
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
    import Mechanics from "./Mechanics";
    import datatable from "../../mixins/datatable";
    import FieldDefs from "./FieldDefs";
    import {mapGetters} from "vuex";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_mechanic: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getMechanics();
        },
        computed:{
          ...mapGetters({
              tableData:'all_mechanics'
          })
        },
        methods:{
            getMechanics(){
                this.$store.dispatch('my_mechanics').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }
                })
            },
            editMechanic(mechanic){
                this.$store.dispatch('updateMechanic',mechanic)
                    .then(() =>{
                        this.editing=true;
                        this.add_mechanic=true;
                    })
            },
            deleteMechanic(id){
                axios.delete(`mechanics/${id}`)
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
                eventBus.$on('listMechanics',(mechanic) =>{
                    this.getItems();
                    this.add_mechanic =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_mechanic = false;
                    this.editing = false;
                });
                eventBus.$on('updateMechanic',(mechanic)=>{
                    this.add_mechanic = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == mechanic.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(mechanic);
                });
            },
        },
        components:{
            Mechanics
        }
    }
</script>

<style scoped>

</style>
