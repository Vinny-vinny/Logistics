<template>
    <div>
        <service-providers v-if="add_supplier" :edit="editing"></service-providers>
        <!-- Main content -->
        <section class="content" v-if="!add_supplier">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Service Providers</h3>
                    <button class="btn btn-info pull-right" @click="importSuppliers()" :disabled="importing">{{importing ? 'Importing...' : 'Import from Sage'}}</button>
                    <button class="btn btn-primary pull-right mr" @click="add_supplier=true">Add Service Provider</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Account</th>
                            <th>Phone</th>
                            <th>Tax #</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="supplier in tableData">
                            <td>{{supplier.id}}</td>
                            <td>{{supplier.name}}</td>
                            <td>{{supplier.account}}</td>
                            <td>{{supplier.phone}}</td>
                            <td>{{supplier.tax_no}}</td>
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
    import ServiceProviders from "./ServiceProviders";
    export default {
        data(){
            return {
                tableData: [],
                add_supplier: false,
                editing: false,
                importing: false
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
                axios.get('service-providers')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editSupplier(supplier){
                this.$store.dispatch('updateSupplier',supplier)
                    .then(() =>{
                        this.editing=true;
                        this.add_supplier=true;
                    })
            },
            deleteSupplier(id){
                axios.delete(`service-providers/${id}`)
                    .then(res => {
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            importSuppliers(){
                this.importing = true;
                   axios.get('import-providers')
                  .then(suppliers => {
                      console.log(suppliers.data.length)
                        if(suppliers.data.length){
                         this.getSuppliers();
                         this.initDatable();
                         }
                    this.$toastr.s('Suppliers successfully imported.')
                    this.importing=false;
                  })
            },
            listen(){
                eventBus.$on('listSuppliers',(supplier) =>{
                    this.tableData.unshift(supplier);
                    this.add_supplier =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_supplier = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateSupplier',(supplier)=>{
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
            ServiceProviders
        }
    }
</script>

<style>
    .mr{
        margin-right: 10px;
    }
</style>
