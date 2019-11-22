<template>
    <div>
        <mechanics v-if="add_mechanic" :edit="editing"></mechanics>
        <!-- Main content -->
        <section class="content" v-if="!add_mechanic">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Mechanics</h3>
                    <button class="btn btn-primary pull-right" @click="add_mechanic=true">Add Mechanic</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="mechanic in tableData">
                            <td>{{mechanic.id}}</td>
                            <td>{{mechanic.name}}</td>
                            <td>{{mechanic.email}}</td>
                            <td>{{mechanic.phone}}</td>
                            <td>{{mechanic.address}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editMechanic(mechanic)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteMechanic(mechanic.id)"><i class="fa fa-trash"></i></button>
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
    import Mechanics from "./Mechanics";
    export default {
        data(){
            return {
                tableData: [],
                add_mechanic: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getMechanics();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getMechanics(){
                axios.get('mechanics')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editMechanic(mechanic){
                this.$store.dispatch('updateMechanic',mechanic)
                    .then(() =>{
                        this.editing=true;
                        this.add_mechanic=true;
                    })
            },
            deleteMechanic(id){
                axios.delete(`mechanics/${id}`)
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
                eventBus.$on('listMechanics',(mechanic) =>{
                    this.tableData.unshift(mechanic);
                    this.add_mechanic =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_mechanic = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateMechanic',(mechanic)=>{
                    this.add_mechanic = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == mechanic.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(mechanic);
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
            Mechanics
        }
    }
</script>

<style scoped>

</style>
