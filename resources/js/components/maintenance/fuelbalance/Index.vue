<template>
    <div>
        <fuel-balance v-if="add_fuel" :edit="editing"></fuel-balance>
        <!-- Main content -->
        <section class="content" v-if="!add_fuel">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fuel Balance</h3>
                    <button class="btn btn-primary pull-right" @click="add_fuel=true">Add Fuel Balance</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th style="display:none">Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="fuel in tableData">
                            <td>{{fuel.id}}</td>
                            <td>{{fuel.name}}</td>
                            <td style="display:none">{{fuel.name}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editFuelBalance(fuel)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteFuelBalance(fuel.id)"><i class="fa fa-trash"></i></button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import FuelBalance from "./FuelBalance";
    export default {
        data(){
            return {
                tableData: [],
                add_fuel: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getFuels();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getFuels(){
                axios.get('fuel-balance')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editFuelBalance(fuel){
                this.$store.dispatch('updateFuelBalance',fuel)
                    .then(() =>{
                        this.editing=true;
                        this.add_fuel=true;
                    })
            },
            deleteFuelBalance(id){
                axios.delete(`fuel-balance/${id}`)
                    .then(res => {
                        console.log(res.data)
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            listen(){
                eventBus.$on('listFuelBalance',(fuel) =>{
                    this.tableData.unshift(fuel);
                    this.add_fuel =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_fuel = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateFuelBalance',(fuel)=>{
                    this.add_fuel = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == fuel.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(fuel);
                    this.initDatable();
                });
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
        },
        components:{
            FuelBalance
        }
    }
</script>

<style scoped>

</style>
