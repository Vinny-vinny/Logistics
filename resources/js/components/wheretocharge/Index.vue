<template>
    <div>
        <where-to-charge v-if="add_charge" :edit="editing"></where-to-charge >
        <!-- Main content -->
        <section class="content" v-if="!add_charge">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Where To Charge</h3>
                    <button class="btn btn-primary pull-right" @click="add_charge=true" v-if="tableData.length < 2">Add New</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Account</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="account in tableData">
                            <td>{{account.id}}</td>
                            <td>{{account.account}}</td>
                            <td>{{account.type}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editCharge(account)"><i class="fa fa-edit"></i></button>
<!--                                <button class="btn btn-danger btn-sm" @click="deleteCategory(category.id)"><i class="fa fa-trash"></i></button>-->
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

    import WhereToCharge from "./WhereToCharge";
    export default {
        data(){
            return {
                tableData: [],
                add_charge: false,
                editing: false,
                accounts:{}
            }
        },
        created(){
            this.listen();
            this.getCharges();
            this.getAccounts();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getAccounts(){
             axios.get('accounts')
             .then(res => {
                this.accounts = res.data;
             })
            },
            getCharges(){
                axios.get('where-to-charge')
                    .then(res => { 
                   this.tableData = res.data

                     })
                    .catch(error => Exception.handle(error))
            },
            editCharge(charge){
                this.$store.dispatch('updateCharge',charge)
                    .then(() =>{
                        this.editing=true;
                        this.add_charge=true;
                    })
            },
            deleteCharge(id){
                axios.delete(`where-to-charge/${id}`)
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
                eventBus.$on('listCharge',(charge) =>{
                    this.tableData.unshift(charge);
                    this.add_charge =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_charge = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateCharge',(charge)=>{
                    this.add_charge = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == charge.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(charge);
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
            WhereToCharge
        }
    }
</script>

<style scoped>

</style>
