<template>
    <div>
        <requisition v-if="add_requisition && !show_form" :edit="editing"></requisition>

        <!-- Main content -->
        <section class="content" v-if="!add_requisition && !show_form && !show_reversal">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reversed Requisitions</h3>

                </div>
                <div class="box-body">
                    <table class="table table-striped dt" style="width:100%">
                        <thead>
                        <tr>
                            <th>Reversal Ref</th>
                            <th>Description</th>
                            <th>Requested On</th>
                            <th>Requested By</th>
                            <th>Project</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="rq in tableData">
                            <td>{{rq.reversal_ref}}</td>
                            <td>{{rq.description}}</td>
                            <td>{{rq.date_requested}}</td>
                            <td>{{rq.person_requested}}</td>
                            <td>{{rq.project}}</td>
                            <td>
                               <button class="btn btn-info btn-sm" @click="editRequisition(rq)"><i class="fa fa-eye"></i></button>
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
    import Requisition from "./ShowReversal";
    export default {
        data(){
            return {
                tableData: [],
                add_requisition: false,
                editing: false,
                show_form:false,
                show_reversal:false
            }
        },
        created(){
            this.listen();
            this.getRequisitions();
            this.getAllAccounts();
            this.getAllCustomers();
            this.getPricelists();
            this.getUoms();
            this.getParts();
        },

        methods:{
            getAllAccounts(){
                axios.get('accounts')
                    .then(res => {
                        //console.log(res.data)
                        this.$store.dispatch('my_accounts',res.data);
                    })
            },
            getAllCustomers(){
                axios.get('customers')
                    .then(res => {
                        this.$store.dispatch('my_customers',res.data);
                    })
            },
            getPricelists(){
                axios.get('price-list')
                    .then(res => {
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
                axios.get('parts')
                    .then(res => {
                        this.$store.dispatch('my_parts',res.data);
                    })
            },
                getRequisitions(){
                axios.get('requisitions')
                    .then(res => {
                        this.tableData = res.data.requisitions.filter(req => req.reversal_ref !=='' && req.reversal_ref !==null)
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
                        this.editing=true;
                        this.add_requisition=true;
                    })
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

        }
    }
</script>

<style scoped>

</style>
