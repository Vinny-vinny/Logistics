<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Customers</h3>
                    <button class="btn btn-success pull-right" @click="importCustomers()" :disabled="importing">{{importing ? 'Importing...' : 'Import from Sage'}}</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Account</th>
                            <th>Contact Person</th>
                            <th>Email</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="customer in tableData">
                            <td>{{customer.id}}</td>
                            <td>{{customer.name}}</td>
                            <td>{{customer.account}}</td>
                            <td>{{customer.contact_person}}</td>
                            <td>{{customer.email}}</td>
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
                tableData: [],
                importing:false,

            }
        },
        created(){
            this.getCustomers();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getCustomers(){
                axios.get('customers')
                    .then(res => {
                        this.tableData = res.data
                    })
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

    }
</script>

<style scoped>

</style>
