<template>
    <div>
        <requisition v-if="add_requisition && !show_form && !show_reversal && !show_req_form" :edit="editing"></requisition>
        <reversal v-if="!add_requisition && !show_form && show_reversal && !show_req_form" :reverse="reversing"></reversal>
        <requisition-form v-if="show_form && !add_requisition && !show_reversal && !show_req_form"></requisition-form>
        <req-form v-if="show_req_form && !show_form && !add_requisition && !show_reversal"></req-form>
        <!-- Main content -->
        <section class="content" v-if="!add_requisition && !show_form && !show_reversal && !show_req_form">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Requisitions</h3>
                      <button class="btn btn-success pull-right" @click="show_req_form =true">Print Requisition Form</button>
                    <button class="btn btn-primary pull-right mr" @click="add_requisition=true">Add Requisition</button>
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
                               <button class="btn btn-success btn-sm fz" @click="editRequisition(rq)"><i class="fa fa-edit"></i></button>
                               <router-link :to="{path:'/requisition/'+rq.id}" class="btn btn-success btn-info btn-sm fz"><i class="fa fa-eye"></i></router-link>
                                <button class="btn btn-danger btn-sm fz" @click="reverseRequisition(rq)" v-if="!rq.reversal_ref && rq.type=='Internal' && rq.used==1"><i class="fa fa-undo" aria-hidden="true"></i></button>
<!--                            <button class="btn btn-danger btn-sm" @click="deleteRequisition(rq.id)"><i class="fa fa-trash"></i></button>-->                            </td>
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
    import RequisitionForm from "./RequisitionForm";
    import Reversal from "./Reversal";
    import ReqForm from "./ReqForm";
    export default {
        data(){
            return {
                tableData: [],
                add_requisition: false,
                editing: false,
                show_form:false,
                show_reversal:false,
                show_req_form:false,
                projects:{},
                users:{}
            }
        },
        created(){
            this.$store.dispatch('loadUsers');
            this.listen();
            this.getRequisitions();
            this.getAllAccounts();
            this.getAllCustomers();


        },
        computed:{
            get_users(){
            return this.$store.state.get_users;
            }
        },
        methods:{
            getAllAccounts(){
                axios.get('accounts')
                    .then(res => {
                      console.log(res.data)
                        this.$store.dispatch('my_accounts',res.data);
                    })
            },
            getAllCustomers(){
                axios.get('customers')
                    .then(res => {
                        //console.log(res.data)
                        this.$store.dispatch('my_customers',res.data.customers);
                    })
            },
            reverseRequisition(rq){
            this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                        this.reversing=true;
                        this.show_reversal = true;
                        this.add_requisition=false;
                    })
            },
            getRequisitions(){
                axios.get('requisitions')
                    .then(res => {
                        this.tableData = res.data.requisitions;
                        this.projects = res.data.projects;
                        this.$store.dispatch('my_vehicles',res.data.machines);
                        this.$store.dispatch('my_parts',res.data.parts);
                        this.$store.dispatch('my_uoms',res.data.uoms);
                        this.$store.dispatch('my_pricelists',res.data.pricelists);
                        this.$store.dispatch('my_reqs',res.data.requisitions);
                        this.$store.dispatch('my_charges',res.data.charges);
                        this.$store.dispatch('my_projects',res.data.projects);
                       //this.$store.dispatch('my_accounts',res.data.accounts);

                    })
                    .catch(error => Exception.handle(error))
                this.initDatable();
            },
            editRequisition(rq){
                //return alert('walla');
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
                eventBus.$on('listReqs',(rq) =>{
                    this.tableData.unshift(rq);
                    this.add_requisition =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_requisition = false;
                    this.editing = false;
                    this.reversing = false;
                    this.show_reversal = false;
                    this.getRequisitions();
                    this.initDatable();
                });
                eventBus.$on('close_req_form',(req) => {
                  this.show_req_form = false
                  this.show_form = true;
                });
                eventBus.$on('updateRequisition',(rq)=>{
                    this.add_requisition = false;
                    this.editing = false;
                    this.show_reversal = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == rq.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(rq);
                    this.initDatable();
                });
                eventBus.$on('hide_form',() =>{
                this.show_form = false;
                this.getRequisitions();

                })
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
                },2000)
            },
        },
        components:{
            Requisition,
            RequisitionForm,
            Reversal,
            ReqForm
        }
    }
</script>

<style scoped>
    .fz{
    font-size: 10px;
}
</style>
