<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_customer ? 'Update Customer' : 'New Customer'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveCustomer()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Account</label>
                            <input type="text" class="form-control" v-model="form.account" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" v-model="form.email" required>
                        </div>
                        <div class="form-group">
                            <label>Contact Person</label>
                            <input type="text" class="form-control" v-model="form.contact_person" required>
                        </div>

                        <button type="submit" class="btn btn-primary">{{edit_customer ? 'Update' : 'Save'}}</button>
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
                    email:'',
                    account:'',
                    contact_person:'',
                    id:''
                },
                edit_customer: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveCustomer(){
                this.edit_customer ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('customers',this.form)
                    .then(res => {
                        this.$store.state.all_my_customers.unshift(res.data);
                        eventBus.$emit('listCustomers',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`customers/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_customer = false;
                        eventBus.$emit('updateCustomer',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.customers
                }
            },
        }
    }
</script>

<style scoped>

</style>
