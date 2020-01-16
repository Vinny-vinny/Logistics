<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Diesel Analysis</h3>
                    <download-excel
                        v-if="fuels.length"
                        class="btn btn-primary pull-right"
                        :data="diesels">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Download
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

    export default {
        data(){
            return {
                diesels:[]
            }
        },
        mounted(){
            this.initDatable();
            this.getDiesels();
        },
        computed:{
            fuels(){
                return this.$store.state.diesels;
            }
        },
        methods:{
            getDiesels(){
             setTimeout(()=>{
              for(let i=0;i<this.fuels.length;i++){
              this.diesels.push({
                'Item Code': this.fuels[i]['item_code'],
                'Item Description': this.fuels[i]['decription'],
                'Reference': this.fuels[i]['reference'],
                'Quantity': this.fuels[i]['quantity'],
                'Unit Cost': this.fuels[i]['unit_cost'],
                'Amount': this.fuels[i]['amount'],
                'Project': this.fuels[i]['project']
              })
              }
              console.log(this.diesels)
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
