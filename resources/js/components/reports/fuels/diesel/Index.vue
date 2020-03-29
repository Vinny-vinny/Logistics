<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Diesel Analysis</h3>
                    <download-excel
                        :data = "diesels"
                        :title = "title"
                        v-if="diesels.length"
                        name="DIESEL_ANALYSIS.xlsx"
                        class="btn btn-primary pull-right">
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </download-excel>
                    <button class="btn btn-outline-danger pull-right mr" @click="back()">Back</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Date</th>
                            <th>Item Code</th>
                            <th>Item Description</th>
                            <th>Reference</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Amount</th>
                            <th>Project</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="fuel in fuels">
                            <td>{{fuel.date}}</td>
                            <td>{{fuel.item_code}}</td>
                            <td>{{fuel.decription}}</td>
                            <td>{{fuel.reference}}</td>
                            <td>{{fuel.quantity}}</td>
                            <td>{{fuel.unit_cost}}</td>
                            <td>{{fuel.amount}}</td>
                            <td>{{fuel.project}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import XLSX from 'xlsx'
    export default {
        data(){
            return {
                diesels:[],
                title:''
            }
        },
        mounted(){
            this.initDatable();
            this.getDiesels();
        },
        computed:{
            fuels(){
                return this.$store.state.diesels;
            },
            dates(){
              return this.$store.state.dates;
            }
        },
        methods:{
            getDiesels(){
             setTimeout(()=>{
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
             },1000)
            },
            back(){
                eventBus.$emit('back');
            },
            initDatable(){
                setTimeout(()=>{
                    $('.dt').DataTable({
                        "pagingType": "full_numbers",
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        order: [[ 0, 'asc' ], [ 3, 'desc' ]],
                        responsive: true,
                        destroy: true,
                        retrieve:true,
                        autoFill: true,
                        colReorder: true,

                    });
                },1000)
            },
        }
    }
</script>

<style scoped>

</style>
