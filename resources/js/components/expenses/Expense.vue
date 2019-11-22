<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_expense ? 'Update Expense' : 'New Expense'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveExpense()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" required>
                        </div>
                        <div class="form-group">
                            <label>Cost</label>
                            <input type="number" step="0.001" class="form-control" v-model="form.cost" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_expense ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    name:'',
                    description:'',
                    cost:'',
                    id:''
                },
                edit_expense: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveExpense(){
                this.edit_expense ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('expense',this.form)
                    .then(res => eventBus.$emit('listExpenses',res.data))
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`expense/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_track = false;
                        eventBus.$emit('updateExpense',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.expense
                }
                console.log(this.form)
            },
        }
    }
</script>

<style scoped>

</style>
