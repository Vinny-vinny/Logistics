<template>
    <div>
        <charge v-if="add_charge" :edit="editing"></charge >
        <!-- Main content -->
        <section class="content" v-if="!add_charge">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Charges
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="indigo" dark @click="add_charge=true" v-if="tableData.length < 2 && parts.length > 0">Add New
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
                                    <v-btn class="mx-1 my-1" fab dark color="indigo" small v-if="parts.length > 0">
                                        <v-icon dark small @click="editCharge(item)">mdi-pencil</v-icon>
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

    import Charge from "./Charge";
    import datatable from "../../mixins/datatable";
    import FieldDefs from "./FieldDefs";
    import {mapGetters} from "vuex";
    export default {
        mixins:[datatable],
        data(){
            return {
                add_charge: false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.listen();
            this.getCharges();
            this.$store.dispatch('my_parts');
        },
        computed:{
          ...mapGetters({
              tableData:'all_charges',
              parts:'all_parts'
          })
        },
        methods:{
            getCharges(){
                this.$store.dispatch('my_charges').then(() => {
                    this.getItems();
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }
                })
            },
            editCharge(charge){
                this.$store.dispatch('updateCharges',charge)
                    .then(() =>{
                        this.editing=true;
                        this.add_charge=true;
                    })
            },
            deleteCharge(id){
                axios.delete(`charges/${id}`)
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
                eventBus.$on('listCharges',(charge) =>{
                    this.getItems();
                    this.add_charge =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_charge = false;
                    this.editing = false;
                });
                eventBus.$on('updateCharges',(charge)=>{
                    this.add_charge = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == charge.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(charge);
                });
            }
        },
        components:{
            Charge
        }
    }
</script>

<style scoped>

</style>
