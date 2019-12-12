<template>
    <div>
        <machines v-if="add_machine" :edit="editing"></machines>
        <!-- Main content -->
        <section class="content" v-if="!add_machine">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Machines</h3>
                    <button class="btn btn-success pull-right" @click="importMachines()" :disabled="importing">{{importing ? 'Importing...' : 'Import from Sage'}}</button>
                    <button class="btn btn-primary pull-right mr" @click="add_machine=true">Add Machine</button>
                    </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Make</th>
                            <th>Chasis #</th>
                            <th>Assigned To</th>
                            <th>Track By</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="machine in tableData">
                            <td>{{machine.code}}</td>
                            <td>{{machine.make}}</td>
                            <td>{{machine.chasis_no}}</td>
                            <td>{{machine.assign_to_id}}</td>
                            <td>{{machine.track_name}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editMachine(machine)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteMachine(machine.id)"><i class="fa fa-trash"></i></button>
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
    import Machines from "./Machines";
    export default {
        data(){
            return {
                tableData: [],
                add_machine: false,
                editing: false,
                importing: false
            }
        },
        created(){
            this.listen();
            this.getMachines();
        },
        methods:{
            getMachines(){
                axios.get('machines')
                    .then(res => {
                        this.tableData = res.data;
                        this.initDatable();
                    })
                    .catch(error => Exception.handle(error))
            },
            editMachine(machine){
                this.$store.dispatch('updateMachine',machine)
                    .then(() =>{
                        this.editing=true;
                        this.add_machine=true;
                    })
            },
            deleteMachine(id){
                axios.delete(`machines/${id}`)
                    .then(res => {
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            importMachines(){
                this.importing = true;
            axios.get('import-machines')
                .then(machines => {
                    if (machines.data.length){
                        this.$toastr.s('Machines imported successfully.');

                    }
                    this.importing = false;
                    this.$router.go();

                })
            },
            listen(){
                eventBus.$on('listMachines',(machine) =>{
                    this.tableData.unshift(machine);
                    this.add_machine =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_machine = false;
                    this.editing = false;
                    this.initDatable();
                    this.getMachines();
                });
                eventBus.$on('updateMachine',(machine)=>{
                    this.add_machine = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == machine.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(machine);
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
            Machines
        }
    }
</script>

<style>
.mr{
    margin-right: 10px;
}
</style>
