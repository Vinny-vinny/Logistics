<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Ledgers</h3>
                    <button class="btn btn-success pull-right" @click="importAccounts()" :disabled="importing">{{importing ? 'Importing...' : 'Import from Sage'}}</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Account</th>                          
                            <th>master Sub Account</th>
                            <th>Description</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="account in tableData">
                            <td>{{account.id}}</td>                         
                            <td>{{account.account}}</td>
                             <td>{{account.sub_account}}</td>
                            <td>{{account.description}}</td>
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
            this.getAccounts();
        },
       
        methods:{
            getAccounts(){
                axios.get('accounts')
                    .then(res => {
                      this.tableData = res.data
                      this.initDatable();
                    })
            },
            importAccounts(){
                this.importing = true;
                    axios.get(`import-accounts`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Ledgers successfully imported.');
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
