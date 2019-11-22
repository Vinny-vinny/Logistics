<template>
    <div>
        <assign-checklist v-if="add_checklist" :edit="editing"></assign-checklist>
        <!-- Main content -->
        <section class="content" v-if="!add_checklist">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Assign Checklists</h3>
                    <button class="btn btn-primary pull-right" @click="add_checklist=true">Add New</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Vehicle</th>
                            <th>Checklist</th>
                            <th>Start Date</th>
                            <th>Reminder Before</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="checklist in tableData">
                            <td>{{checklist.id}}</td>
                            <td>{{checklist.vehicle}}</td>
                            <td>{{checklist.checklist}}</td>
                            <td>{{checklist.start_date}}</td>
                            <td>{{checklist.reminder_before}} days</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editChecklist(checklist)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteChecklist(checklist.id)"><i class="fa fa-trash"></i></button>
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
    import AssignChecklist from "./AssignChecklist";
    export default {
        data(){
            return {
                tableData: [],
                add_checklist: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.ssignChecklists();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            ssignChecklists(){
                axios.get('assign-checklist')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editChecklist(checklist){
                this.$store.dispatch('updateAssignChecklist',checklist)
                    .then(() =>{
                        this.editing=true;
                        this.add_checklist=true;
                    })
            },
            deleteChecklist(id){
                axios.delete(`assign-checklist/${id}`)
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
                eventBus.$on('listAssignChecklists',(checklist) =>{
                    this.tableData.unshift(checklist);
                    this.add_checklist =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_checklist = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateAssignChecklist',(checklist)=>{
                    this.add_checklist = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == checklist.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(checklist);
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
            AssignChecklist
        }
    }
</script>

<style scoped>

</style>
