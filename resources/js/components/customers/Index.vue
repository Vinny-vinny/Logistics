<template>
    <div>
        <customers v-if="add_customer" :edit="editing"></customers>
        <!-- Main content -->
        <section class="content" v-if="!add_customer">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Customers</h3>
                    <button class="btn btn-success pull-right" @click="importCustomers()" :disabled="importing">{{importing ? 'Importing...' : 'Import from Sage'}}</button>
                    <button class="btn btn-primary pull-right mr" @click="add_customer=true">Add Customer</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Account</th>
                            <th>Contact Person</th>
                            <th>Actions</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="customer in tableData">
                            <td>{{customer.id}}</td>
                            <td>{{customer.name}}</td>
                            <td>{{customer.account}}</td>
                            <td>{{customer.contact_person}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editCustomer(customer)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteCustomer(customer.id)" style="display:none"><i class="fa fa-trash"></i></button>
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
    import Customers from "./Customers";
    export default {
        data(){
            return {
                tableData: [],
                importing:false,
                add_customer:false,
                editing: false
            }
        },
        created(){
            this.getCustomers();
            this.listen();
        },
        
        methods:{
            getCustomers(){
                axios.get('customers')
                    .then(res => {
                        this.tableData = res.data
                        this.initDatable();
                    })
            },
            editCustomer(customer){
                this.$store.dispatch('updateCustomer',customer)
                    .then(() =>{
                        this.editing=true;
                        this.add_customer=true;
                    })
            },
            deleteCustomer(id){
                axios.delete(`customers/${id}`)
                    .then(res => {
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            importCustomers(){
                this.importing = true;
                axios.get(`import-customers`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Customers successfully imported.');
                        this.$router.go();
                    })
            },
            listen(){
                eventBus.$on('listCustomers',(customer) =>{
                    this.tableData.unshift(customer);
                    this.add_customer =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_customer = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateCustomer',(customer)=>{
                    this.add_customer = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == customer.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(customer);
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
                },500)
            },
        },
    components:{
            Customers
    }
    }
</script>

<style scoped>

</style>
