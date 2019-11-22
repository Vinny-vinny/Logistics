<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_user ? 'Update User' : 'New User'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveUser()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" class="form-control" v-model="form.title" required>
                        </div>
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" v-model="form.code_no" required>
                        </div>
                        <div class="form-group">
                            <label>Join Date</label>
                            <datepicker v-model="form.join_date" required></datepicker>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" v-model="form.email" required>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control" v-model="form.password">
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_user ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    name:'',
                    title:'',
                    code_no:'',
                    email:'',
                    join_date:'',
                    password:'',
                    id:''
                },
                edit_user: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveUser(){
                this.form.join_date = moment(this.form.join_date).format('YYYY-MM-DD')
                this.edit_user ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                if (this.form.password ===''){
                    return this.$toastr.e('Password field cannot be empty.');
                }
                axios.post('users',this.form)
                    .then(res => eventBus.$emit('listUsers',res.data))
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`users/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_user = false;
                        eventBus.$emit('updateUser',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.users
                }
            },
        },
        components:{
            datepicker
        }
    }
</script>

<style scoped>

</style>
