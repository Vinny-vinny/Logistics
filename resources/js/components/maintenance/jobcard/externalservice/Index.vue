<template>
    <div>
        <external-service v-if="add_external" :edit="editing"></external-service>
        <!-- Main content -->
        <section class="content" v-if="!add_external">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Track By</h3>
                    <button class="btn btn-primary pull-right" @click="add_external=true">Add External Service</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Job card#</th>
                            <th>Supplier</th>
                            <th>External Service Type</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="external in tableData">
                            <td>{{external.id}}</td>
                            <td>{{external.job_card}}</td>
                            <td>{{external.supplier}}</td>
                            <td>{{external.service_type}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editExternal(external)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteExternal(external.id)"><i class="fa fa-trash"></i></button>
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
    import ExternalService from "./ExternalService";
    export default {
        data(){
            return {
                tableData: [],
                add_external: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getServices();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getServices(){
                axios.get('external-services')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editExternal(track){
                this.$store.dispatch('updateExternalServices',track)
                    .then(() =>{
                        this.editing=true;
                        this.add_external=true;
                    })
            },
            deleteExternal(id){
                axios.delete(`external-services/${id}`)
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
                eventBus.$on('listExternalServices',(service) =>{
                    this.tableData.unshift(service);
                    this.add_external =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_external = false;
                    this.editing = false;
                    this.initDatable();
                    this.getServices();
                });
                eventBus.$on('updateExternalService',(service)=>{
                    this.add_external = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == service.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(service);
                    this.initDatable();
                    this.getServices();
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
            ExternalService
        }
    }
</script>

<style scoped>

</style>
