<template>
    <div>
        <supplier v-if="add_supplier" :edit="editing"></supplier>
        <!-- Main content -->
        <section class="content" v-if="!add_supplier">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Suppliers</h3>
                    <button class="btn btn-primary pull-right" @click="add_supplier=true">Add Supplier</button>
                    <button class="btn btn-success pull-right mr" @click="importSuppliers()">{{importing ? 'Importing...' : 'Import External Suppliers'}}</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Phone</th>
                            <th>Fuel Type</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="supplier in tableData">
                            <td>{{supplier.id}}</td>
                            <td>{{supplier.code}}</td>
                            <td>{{supplier.name}}</td>
                            <td>{{supplier.phone_no}}</td>
                            <td>{{supplier.fuel_type}}</td>
                            <td>{{supplier.price}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editSupplier(supplier)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteSupplier(supplier.id)"><i class="fa fa-trash"></i></button>
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
    import Supplier from "./Supplier";
    export default {
        data(){
            return {
                tableData: [],
                add_supplier: false,
                editing: false,
                importing:false
            }
        },
        created(){
            this.listen();
            this.getSuppliers();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getSuppliers(){
                axios.get('fuel-supplier')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editSupplier(supplier){
                this.$store.dispatch('updateFuelSupplier',supplier)
                    .then(() =>{
                        this.editing=true;
                        this.add_supplier=true;
                    })
            },
            importSuppliers(){
                this.importing = true;
              axios.get('import-suppliers')
                  .then(() => {
                      this.importing = false;
                      this.$toastr.s('Suppliers imported successfully.')
                  })
            },
            deleteSupplier(id){
                axios.delete(`fuel-supplier/${id}`)
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
                eventBus.$on('listFuelSuppliers',(supplier) =>{
                    this.tableData.unshift(supplier);
                    this.add_supplier =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_supplier = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateFuelSupplier',(supplier)=>{
                    this.add_supplier = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == supplier.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(supplier);
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
            Supplier
        }
    }
</script>

<style scoped>

</style>
