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
                                Partner Reports
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
                                    :data = "fuels"
                                    :title = "title"
                                    v-if="fuels.length"
                                    name="PARTNER_REPORTS.xlsx"
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
            tableData:[],
            results:[],
            title:'',
            headers: FieldDefs
         }
        },
        mounted(){
           this.tableData = this.fuels;
           this.partners();
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
                return this.$store.state.partners;
            },
             dates(){
              return this.$store.state.dates;
            }

        },
        methods:{
              partners(){
                let results = [];
             //  setTimeout(()=>{
                   let total_distance=0;
                   let total_fuel_used = 0;
                   let total_km = 0;
              for(let k=0;k<this.fuels.length;k++){
                  total_distance+=parseInt(this.fuels[k]['distance']);
                  total_fuel_used += parseFloat(this.fuels[k]['fuel_used']);
                  total_km += parseFloat(this.fuels[k]['km_per_ltrs']);
                            this.results.push({
                            'Reg': this.fuels[k]['reg_no'] ,
                            'Start Odo':this.fuels[k]['start_odo'],
                            'End Odo': this.fuels[k]['end_odo'],
                            'Distance': this.fuels[k]['distance'],
                            'FuelUsed': this.fuels[k]['fuel_used'],
                            'KMPerLTR':this.fuels[k]['km_per_ltrs']

                          });
                         }
                   this.results.push({Reg:'Total',Distance:total_distance,FuelUsed:total_fuel_used,KMPerLTR:total_km})
                   this.title = "PARTNERS REPORTS AS FROM "+this.dates.from+" TO "+ this.dates.to
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
