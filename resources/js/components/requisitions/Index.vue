<template>
    <div>
        <requisition v-if="add_requisition" :edit="editing"></requisition>
        <!-- Main content -->
        <section class="content" v-if="!add_requisition">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Requisitions</h3>
                    <button class="btn btn-primary pull-right" @click="add_requisition=true">Add Requisition</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>

                            <th>Req #</th>
                            <th>Description</th>
                            <th>Requested On</th>
                            <th>Requested By</th>
                            <th>Project</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="rq in tableData">
                            <td>{{rq.req_no}}</td>
                            <td>{{rq.description}}</td>
                            <td>{{rq.date_requested}}</td>
                            <td>{{rq.person_requested}}</td>
                            <td>{{rq.project}}</td>
                            <td>
                               <button class="btn btn-success btn-sm" @click="editRequisition(rq)"><i class="fa fa-edit"></i></button>
                               <router-link :to="{path:'/requisition/'+rq.id}" class="btn btn-success btn-info btn-sm"><i class="fa fa-eye"></i></router-link>
<!--                               <button class="btn btn-danger btn-sm" @click="deleteRequisition(rq.id)"><i class="fa fa-trash"></i></button>-->
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
    import Requisition from "./Requisition";
    export default {
        data(){
            return {
                tableData: [],
                add_requisition: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getRequisitions();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getRequisitions(){
                axios.get('requisitions')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editRequisition(rq){
                this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                        this.editing=true;
                        this.add_requisition=true;
                    })
            },
            deleteRequisition(id){
                axios.delete(`requisitions/${id}`)
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
                eventBus.$on('listRequisitions',(rq) =>{
                    this.tableData.unshift(rq);
                    this.add_requisition =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_requisition = false;
                    this.editing = false;
                    this.getRequisitions();
                    this.initDatable();
                });
                eventBus.$on('updateRequisition',(rq)=>{
                    this.add_requisition = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == rq.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(rq);
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
            Requisition
        }
    }
</script>

<style scoped>

</style>
