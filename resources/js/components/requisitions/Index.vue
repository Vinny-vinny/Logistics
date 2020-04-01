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
                    <template>
                        <button class="btn btn-primary pull-right mr" @click="addReq()">{{show_add_txt ? 'Please wait..' : 'Add Requisition'}}</button>
                    </template>

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
                users:{},
                customers:{},
                check_customers:false,
                check_accounts:false,
                check_prices:false,
                check_parts:false,
                show_add_txt:false
            }
        },
        watch:{
            mycustomers(){
                if (this.customers.length){
                 this.add_requisition = true;
                }
            }
        },
        created(){
           //this.$store.dispatch('loadUsers');
            this.listen();
            this.getRequisitions();
            this.getAllAccounts();
            this.getAllCustomers();
            this.getPricelists();
            this.getUoms();
            this.getParts();
            this.getGroups();
        },
        computed:{
            cust(){
              return this.check_customers;
            },
            account(){
                return this.check_accounts;
            },
            price(){
                return this.check_prices;
            },
            part(){
                return this.check_parts;
            }
        },
        methods:{
            addReq(){
                this.show_add_txt = true;
                if (this.cust && this.account && this.price && this.part){
                    this.show_add_txt = false;
                    this.add_requisition=true
                }
            },
            getGroups(){
                axios.get('stk-groups')
                    .then(res => {
                        this.$store.dispatch('my_stk_groups',res.data);
                    })
            },
            getAllAccounts(){
                this.check_accounts =false;
                axios.get('accounts')
                    .then(res => {
                        this.check_accounts =true;
                        this.$store.dispatch('my_accounts',res.data);
                    })
            },
            getAllCustomers(){
                this.check_customers =false;
                axios.get('customers')
                    .then(res => {
                        this.check_customers =true;
                        this.$store.dispatch('my_customers',res.data);
                    })
            },
            getPricelists(){
                this.check_prices =false;
                axios.get('price-list')
                    .then(res => {
                        this.check_prices =true;
                        this.$store.dispatch('my_pricelists',res.data);
                    })
            },
            getUoms(){
                axios.get('uom')
                    .then(res => {
                        this.$store.dispatch('my_uoms',res.data);
                    })
            },
            getParts(){
                this.check_parts = false;
                axios.get('parts')
                    .then(res => {
                     this.check_parts = true;
                     this.$store.dispatch('my_parts',res.data);
                    })
            },
            reverseRequisition(rq){
            this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                        if (this.cust && this.account && this.price && this.part){
                            this.reversing=true;
                            this.show_reversal = true;
                            this.add_requisition=false;
                        }

                    })
            },
            getRequisitions(){
                axios.get('requisitions')
                    .then(res => {
                        this.tableData = res.data.requisitions;
                        this.projects = res.data.projects;
                        this.$store.dispatch('my_vehicles',res.data.machines);
                        this.$store.dispatch('my_reqs',res.data.requisitions);
                        this.$store.dispatch('my_charges',res.data.charges);
                        this.$store.dispatch('my_projects',res.data.projects);
                    })
                    .catch(error => Exception.handle(error))
                this.initDatable();
            },
            editRequisition(rq){
                this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                        if (this.cust && this.account && this.price && this.part){
                            this.show_add_txt = false;
                            this.add_requisition=true
                            this.editing=true;
                        }
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
