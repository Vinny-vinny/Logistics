<template>
    <div>
        <types v-if="add_type" :edit="editing"></types>
        <!-- Main content -->
        <section class="content" v-if="!add_type">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fuel Types</h3>
                    <button class="btn btn-primary pull-right" @click="add_type=true">Add Fuel Type</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Pump Price</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="type in tableData">
                            <td>{{type.id}}</td>
                            <td>{{type.name}}</td>
                            <td>{{type.rate}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editFuelType(type)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteFuelType(type.id)"><i class="fa fa-trash"></i></button>
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
    import Types from "./Types";
    export default {
        data(){
            return {
                tableData: [],
                add_type: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getFuelTypes();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getFuelTypes(){
                axios.get('fuel-types')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editFuelType(type){
                this.$store.dispatch('updateFuelType',type)
                    .then(() =>{
                        this.editing=true;
                        this.add_type=true;
                    })
            },
            deleteFuelType(id){
                axios.delete(`fuel-types/${id}`)
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
                eventBus.$on('listFuelTypes',(type) =>{
                    this.tableData.unshift(type);
                    this.add_type =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_type = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateFuelTypes',(type)=>{
                    this.add_type = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == type.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(type);
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
            Types
        }
    }
</script>

<style scoped>

</style>
