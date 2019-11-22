<template>
    <div>
        <users v-if="add_user" :edit="editing"></users>
        <!-- Main content -->
        <section class="content" v-if="!add_user">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Users</h3>
                    <button class="btn btn-primary pull-right" @click="add_user=true">Add User</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Code</th>
                            <th>Join Date</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="user in tableData">
                            <td>{{user.id}}</td>
                            <td>{{user.name}}</td>
                            <td>{{user.email}}</td>
                            <td>{{user.code_no}}</td>
                            <td>{{user.join_date}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editUser(user)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteUser(user.id)"><i class="fa fa-trash"></i></button>
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
    import Users from "./Users";
    export default {
        data(){
            return {
                tableData: [],
                add_user: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getUsers();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getUsers(){
                axios.get('users')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editUser(user){
                this.$store.dispatch('updateUser',user)
                    .then(() =>{
                        this.editing=true;
                        this.add_user=true;
                    })
            },
            deleteUser(id){
                axios.delete(`users/${id}`)
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
                eventBus.$on('listUsers',(user) =>{
                    this.tableData.unshift(user);
                    this.add_user =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_user = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateUser',(user)=>{
                    this.add_user = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == user.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(user);
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
            Users
        }
    }
</script>

<style scoped>

</style>
