<template>
    <div>
        <expense v-if="add_expense" :edit="editing"></expense>
        <!-- Main content -->
        <section class="content" v-if="!add_expense">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Expenses</h3>
                    <button class="btn btn-primary pull-right" @click="add_expense=true">Add Expense</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="expense in tableData">
                            <td>{{expense.id}}</td>
                            <td>{{expense.name}}</td>
                            <td>{{expense.description}}</td>
                            <td>{{expense.cost}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editExpense(expense)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteExpense(expense.id)"><i class="fa fa-trash"></i></button>
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
    import Expense from "./Expense";
    export default {
        data(){
            return {
                tableData: [],
                add_expense: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getExpenses();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getExpenses(){
                axios.get('expense')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editExpense(expense){

                this.$store.dispatch('updateExpense',expense)
                    .then(() =>{
                        this.editing=true;
                        this.add_expense=true;
                    })
            },
            deleteExpense(id){
                axios.delete(`expense/${id}`)
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
                eventBus.$on('listExpenses',(expense) =>{
                    this.tableData.unshift(expense);
                    this.add_expense =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_expense = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateExpense',(expense)=>{
                    this.add_expense = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == expense.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(expense);
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
            Expense
        }
    }
</script>

<style scoped>

</style>
