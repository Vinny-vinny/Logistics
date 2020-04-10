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
                                Stock Issue
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
                                    :data   = "requistions"
                                    :title = "title"
                                    v-if="requistions.length"
                                    name="STOCK_ISSUE.xlsx"
                                    class="btn btn-primary pull-right"
                                >
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
    import JobcardReport from "./JobcardReport";
    import {mapGetters} from "vuex";
    import datatable from "../../../mixins/datatable";
    import FieldDefs from "./FieldDefs";
    export default {
        mixins:[datatable],
        data(){
            return {
                requistions:[],
                title: '',
                headers:FieldDefs,
                tableData:[]
            }
        },
       mounted(){
           this.tableData = this.jobs;
            this.itemsIssued();
            this.getDetails;
        },
        computed:{
            ...mapGetters({
                parts:'all_parts',
                projects:'all_projects'
            }),
            getDetails(){
                    if (this.tableData.length == undefined) {
                        setTimeout(() => {
                            this.getItems();
                        }, 2000);
                    }else {
                        this.getItems();
                    }

            },
            jobs(){
          return this.$store.state.jobs;
            },
            dates(){
              return this.$store.state.dates;
            }

        },
        methods:{
     itemsIssued(){
           let total_qty=0;
           let total_unit_cost = 0;
           let total_amount = 0;
            for(let i=0;i<this.jobs.length;i++){
                total_qty+=parseInt(this.jobs[i]['quantity']);
                total_unit_cost += parseFloat(this.jobs[i]['unit_cost']);
                total_amount += parseFloat(this.jobs[i]['amount']);
             this.requistions.push({
                'Date':this.jobs[i]['date'],
                'Item Code': this.jobs[i]['code'],
                'Item Description': this.jobs[i]['description'],
                'Reference': this.jobs[i]['reference'],
                'Quantity': this.jobs[i]['quantity'],
                'UnitCost': this.jobs[i]['unit_cost'],
                'Amount': this.jobs[i]['amount'],
                'Project': this.jobs[i]['project']
             })

            }
              this.requistions.push({Date:'Total',Quantity:total_qty,UnitCost:total_unit_cost,Amount:total_amount})
              this.title = "STOCK ISSUE AS FROM "+this.dates.from+" TO "+ this.dates.to
          },
            back(){
              eventBus.$emit('back');
            }
        },
        components:{
            JobcardReport
        }
    }
</script>

<style scoped>

</style>
