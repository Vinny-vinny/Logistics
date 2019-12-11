<template>
    <div>
        <tool v-if="add_tool" :edit="editing"></tool>
        <!-- Main content -->
        <section class="content" v-if="!add_tool">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Checklist Tools</h3>
                    <button class="btn btn-primary pull-right" @click="add_tool=true">Add Checklist Tool</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th style="display: none">Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="tool in tableData">
                            <td>{{tool.id}}</td>
                            <td>{{tool.name}}</td>
                            <td style="display: none">{{tool.name}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editTool(tool)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteTool(tool.id)"><i class="fa fa-trash"></i></button>
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
    import Tool from "./Tool";
    export default {
        data(){
            return {
                tableData: [],
                add_tool: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getTools();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getTools(){
                axios.get('checklist-tool')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editTool(tool){
                this.$store.dispatch('updateTool',tool)
                    .then(() =>{
                        this.editing=true;
                        this.add_tool=true;
                    })
            },
            deleteTool(id){
                axios.delete(`checklist-tool/${id}`)
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
                eventBus.$on('listTools',(tool) =>{
                    this.tableData.unshift(tool);
                    this.add_tool =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_tool = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateTool',(tool)=>{
                    this.add_tool = false;
                    this.editing = false;
                    const index = this.tableData.map(t => t.id).indexOf(tool.id);
                    this.tableData.splice(index,1);
                    this.tableData.unshift(tool);
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
            Tool
        }
    }
</script>

<style scoped>

</style>
