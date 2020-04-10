<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-body">
                    <v-app id="inspire">
                        <v-card>
                            <v-card-title>
                                Diesel Analysis
                                <v-spacer></v-spacer>
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Search"
                                    single-line
                                    hide-details
                                ></v-text-field>
                                <v-spacer></v-spacer>
                                <download-excel
                                    :data = "diesels"
                                    :title = "title"
                                    v-if="diesels.length"
                                    name="DIESEL_ANALYSIS.xlsx"
                                    class="btn btn-primary pull-right">
                                    <i class="fa fa-file-excel-o"></i> Export Excel
                                </download-excel>
                                <v-btn normal color="warning" outlined  @click="back()" class="mr">Back
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
                            </v-data-table>
                        </v-card>
                    </v-app>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import datatable from "../../../../mixins/datatable";
    import FieldDefs from "./FieldDefs";
    export default {
        mixins:[datatable],
        data(){
            return {
                diesels:[],
                title:'',
                headers:FieldDefs,
                tableData:[]
            }
        },
        mounted(){
            this.tableData = this.fuels;
            this.getDiesels();
            this.getData;
        },
        computed:{
            getData(){
                if (this.tableData.length == undefined) {
                    setTimeout(() => {
                        this.getItems();
                    }, 2000);
                }else {
                    this.getItems();
                }

            },
            fuels(){
                return this.$store.state.diesels;
            },
            dates(){
              return this.$store.state.dates;
            }
        },
        methods:{
            getDiesels(){
            // setTimeout(()=>{
                 let total_qty=0;
                 let total_unit_cost = 0;
                 let total_amount = 0;
              for(let i=0;i<this.fuels.length;i++){
                  total_qty+=parseInt(this.fuels[i]['quantity']);
                  total_unit_cost += parseFloat(this.fuels[i]['unit_cost']);
                  total_amount += parseFloat(this.fuels[i]['amount']);
              this.diesels.push({
                'ItemCode': this.fuels[i]['item_code'],
                'Item Description': this.fuels[i]['decription'],
                'Reference': this.fuels[i]['reference'],
                'Quantity': this.fuels[i]['quantity'],
                'UnitCost': this.fuels[i]['unit_cost'],
                'Amount': this.fuels[i]['amount'],
                'Project': this.fuels[i]['project']
              })
              }
                 this.diesels.push({ItemCode:'Total',Quantity:total_qty,UnitCost:total_unit_cost,Amount:total_amount})
                 this.title = "DIESEL ANALYSIS AS FROM "+this.dates.from+" TO "+ this.dates.to
           //  },1000)
            },
            back(){
                eventBus.$emit('back');
            }
        }
    }
</script>

<style scoped>

</style>
