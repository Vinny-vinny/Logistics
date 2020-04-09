<template>
    <div>
        <customers v-if="add_customer" :edit="editing"></customers>
        <!-- Main content -->
        <section class="content" v-if="!add_customer">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Customers
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <v-btn small color="cyan"  @click="importCustomers()" :disabled="importing">
                                    {{importing ? 'Importing...' : 'Import from Sage'}}
                                </v-btn>
                                <v-btn small color="indigo" dark @click="add_customer=true" class="mr">Add Customer
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
                                        <v-icon dark small @click="editCustomer(item)">mdi-pencil</v-icon>
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
    import Customers from "./Customers";
    import datatable from "../../mixins/datatable";
    import FieldDefs from "./FieldDefs";
    import {mapGetters} from 'vuex';
    export default {
        mixins:[datatable],
        data(){
            return {
                importing:false,
                add_customer:false,
                editing: false,
                headers: FieldDefs
            }
        },
        created(){
            this.getCustomers;
            this.listen();
        },
        computed:{
            ...mapGetters({
               tableData:'all_customers'
            }),
            getCustomers(){
                this.$store.dispatch('my_customers').then(() => {
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }else {
                        this.getItems();
                    }
                })
            },
        },
        methods:{
            editCustomer(customer){
                this.$store.dispatch('updateCustomer',customer)
                    .then(() =>{
                        this.editing=true;
                        this.add_customer=true;
                    })
            },
            deleteCustomer(id){
                axios.delete(`customers/${id}`)
                    .then(res => {
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            importCustomers(){
                this.importing = true;
                axios.get(`import-customers`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Customers successfully imported.');
                        this.$router.go();
                    })
            },
            listen(){
                eventBus.$on('listCustomers',(customer) =>{
                    this.getItems();
                    this.add_customer =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_customer = false;
                    this.editing = false;
                });
                eventBus.$on('updateCustomer',(customer)=>{
                    this.add_customer = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == customer.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(customer);
                });
            },
        },
    components:{
            Customers
    }
    }
</script>

<style scoped>

</style>
