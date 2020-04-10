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
                                Department Consumption% Reports
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
                                    :data = "results"
                                    :title = "title"
                                    v-if="results.length"
                                    name="DEPARTMENT_CONSUMPTION%.xlsx"
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
    import FieldDefs from "./FieldDefs";
    import datatable from "../../../../mixins/datatable";
    export default {
        mixins:[datatable],
        data(){
         return {
            results:[],
             title:'',
             tableData:[],
             headers: FieldDefs
         }
        },
        mounted(){
            this.tableData = this.fuels;
            this.consumptions();
            this.getDetails;
        },
        computed:{
            getDetails(){
                if (this.tableData.length == undefined) {
                    setTimeout(() => {
                        this.getItems();
                    }, 2000);
                }else {
                    this.getItems();
                }
            },
            fuels(){
                return this.$store.state.department_consumptions;
            },
             dates(){
              return this.$store.state.dates;
            }
        },
        methods:{
            consumptions(){
            //setTimeout(()=>{
                let total_qty = 0;
                let total_percentage = 0;
            for(let i=0;i<this.fuels.length;i++){
                total_qty += parseFloat(this.fuels[i]['qty'])
                total_percentage += parseFloat(this.fuels[i]['percentage']);
                this.results.push({
                 'Department': this.fuels[i]['department'],
                 'QtyInLtrs': this.fuels[i]['qty'],
                 'Percentage': this.fuels[i]['percentage']
                });
            }
                this.results.push({Department:'Total',QtyInLtrs:total_qty,Percentage:total_percentage})
                this.title = "DEPARTMENT CONSUMPTION% AS FROM "+this.dates.from+" TO "+ this.dates.to
          //  },1000)

            },
            back(){
                eventBus.$emit('back');
            },
        }
    }
</script>

<style scoped>

</style>
