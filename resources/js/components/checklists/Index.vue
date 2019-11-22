<template>
    <div>
        <checklist v-if="add_checklist" :edit="editing"></checklist>
        <!-- Main content -->
        <section class="content" v-if="!add_checklist">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Checklists</h3>
                    <button class="btn btn-primary pull-right" @click="add_checklist=true">Add Checklist</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Expiry Type</th>
                            <th>Duration #</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="checklist in tableData">
                            <td>{{checklist.id}}</td>
                            <td>{{checklist.name}}</td>
                            <td>{{checklist.expiry_type}}</td>
                            <td>{{checklist.duration_no}}</td>
                               <td>
                                <button class="btn btn-success btn-sm" @click="editChecklist(checklist)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteChecklist(checklist.id)"><i class="fa fa-trash"></i></button>
<!--                                <button class="btn btn-info btn-sm" @click="downloadFile(checklist.checklist_file)"><i class="fa fa-download"></i></button>-->
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
   import Checklist from "./Checklist";
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
            this.getChecklists();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            //download File function
                downloadFile(file) {
                    return console.log(file.split('.').pop());
                axios({
                    url: '/assets/images/sort_desc.png',
                    method: 'GET',
                    responseType: 'blob',
                }).then((response) => {
                    var fileURL = window.URL.createObjectURL(new Blob([response.data]));
                    var fileLink = document.createElement('a');

                    fileLink.href = fileURL;
                    fileLink.setAttribute('download', 'file.png');
                    document.body.appendChild(fileLink);
                    fileLink.click();
                });
            },

            getChecklists(){
                axios.get('checklists')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editChecklist(checklist){
                      this.$store.dispatch('updateChecklist',checklist)
                    .then(() =>{
                        this.editing=true;
                        this.add_checklist=true;
                    })
            },
            deleteChecklist(id){
                axios.delete(`checklists/${id}`)
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
                eventBus.$on('listChecklists',(checklist) =>{
                    this.tableData.unshift(checklist);
                    this.add_checklist =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_checklist = false;
                    this.editing = false;
                    this.initDatable();
                    this.getChecklists();
                });
                eventBus.$on('updateChecklist',(checklist)=>{
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
            Checklist
        }
    }
</script>

<style scoped>

</style>
