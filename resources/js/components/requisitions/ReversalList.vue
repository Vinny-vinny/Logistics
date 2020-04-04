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
                add_requisition: false,
                editing: false,
                show_form:false,
                show_reversal:false,
                check_customers:false,
                check_accounts:false,
                check_prices:false,
                check_parts:false,
                show_add_txt:false
            }
        },
        created(){
            this.getAllDetails();
            this.listen();
            },
        computed:{
            tableData(){
                if (this.$store.state.all_my_reqs.length > 1){
                 return  this.$store.state.all_my_reqs.filter(req => req.reversal_ref !=='' && req.reversal_ref !==null);
                }
            },
            pricelists(){
                return this.$store.state.all_my_pricelists;
            },
            parts(){
                return this.$store.state.all_my_parts;
            },
        },
        methods:{
            getAllDetails(){
                this.$store.dispatch('my_reqs');
                this.initDatable();
                this.$store.dispatch('my_parts');
                this.$store.dispatch('my_customers');
                this.$store.dispatch('my_accounts');
                this.$store.dispatch('my_pricelists');
                this.$store.dispatch('my_uoms');
                this.$store.dispatch('my_vehicles');
                this.$store.dispatch('my_stk_groups');
                this.$store.dispatch('my_users');
                this.$store.dispatch('my_projects');
                },

            editRequisition(rq){
                this.$store.dispatch('updateRequisition',rq)
                    .then(() =>{
                        if (this.pricelists.length > 1 && this.parts.length > 1){
                            this.editing=true;
                            this.add_requisition=true;
                        }

                    })
            },
            listen(){
                eventBus.$on('listReqs',(rq) =>{
                    this.tableData.unshift(rq);
                    this.add_requisition =false;
                });
                eventBus.$on('cancel',()=>{
                    this.add_requisition = false;
                    this.editing = false;
                    this.reversing = false;
                    this.show_reversal = false;
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
