<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Department Consumption% Reports</h3>
                    <download-excel
                        :data = "results"
                        :title = "title"
                        v-if="results.length"
                        name="DEPARTMENT_CONSUMPTION%.xlsx"
                        class="btn btn-primary pull-right">
                        <i class="fa fa-file-excel-o"></i> Export Excel
                    </download-excel>
                     <button class="btn btn-outline-danger pull-right mr" @click="back()">Back</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Department</th>
                            <th>QTY In Ltrs</th>
                            <th style="display:none">QTY In Ltrs</th>
                            <th>Percentage</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="fuel in fuels">
                            <td>{{fuel.department}}</td>
                            <td>{{fuel.qty}}</td>
                            <td style="display:none">{{fuel.qty}}</td>
                            <td>{{fuel.percentage}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    export default {
        data(){
         return {
            results:[],
             title:''
         }
        },
        mounted(){
            this.initDatable();
            this.consumptions();
        },
        computed:{
            fuels(){
                return this.$store.state.department_consumptions;
            },
             dates(){
              return this.$store.state.dates;
            }
        },
        methods:{
            consumptions(){
            setTimeout(()=>{
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
