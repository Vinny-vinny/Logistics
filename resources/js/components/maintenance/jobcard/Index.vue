<template>
    <div>
        <job-card v-if="add_jobcard" :edit="editing"></job-card>
        <job-form v-if="show_form" :printJob="show_form"></job-form>
        <reversal v-if="show_reversal" :reverse="show_reversal"></reversal>
        <!-- Main content -->
        <section class="content" v-if="!add_jobcard && !show_form && !show_reversal">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Card</h3>
                    <button class="btn btn-success pull-right" @click="show_form=true" v-if="parts.length">Print Job Card Form</button>
                    <button class="btn btn-primary pull-right mr" @click="add_jobcard=true" v-if="parts.length > 1">Add Job Card</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Machine</th>
                            <th>Driver</th>
                            <th>Project</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="job in tableData">
                            <td>{{job.card_no}}</td>
                            <td>{{job.machine}}</td>
                            <td>{{job.driver}}</td>
                            <td>{{job.project}}</td>
                              <td>
                             <span v-if="parts.length">
                                <button class="btn btn-success btn-sm" @click="editJobcard(job)"><i class="fa fa-edit"></i></button>
                                <router-link :to="{path:'/job-card/'+job.id}" class="btn btn-info btn-sm"><i class=" fa fa-eye"></i></router-link>
                                 <button v-if="!job.reversal_ref && job.invoiced==1" class="btn btn-danger btn-sm" @click="reverseJob(job)"><i class="fa fa-undo" aria-hidden="true"></i></button>
                             </span>
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
    import JobCard from "./JobCard";
    import JobForm from "./JobForm";
    import Reversal from "./Reversal";
    import {mapGetters} from "vuex";
    export default {
        data(){
            return {
                add_jobcard: false,
                editing: false,
                show_form:false,
                show_reversal:false,
                check_customers:false,
                check_parts:false,
                show_add_text:false
            }
        },
        created(){
            this.getAllDetails();
            this.listen();
        },
        computed:{
           ...mapGetters({
               tableData:'all_jobs',
               parts:'all_parts',
               machines:'all_vehicles'

           })
        },
        methods:{
           getAllDetails(){
               this.$store.dispatch('my_parts');
               this.$store.dispatch('my_customers');
               this.$store.dispatch('my_vehicles');
               this.$store.dispatch('my_users');
               this.$store.dispatch('my_job_types');
               this.$store.dispatch('my_job_categories');
               this.$store.dispatch('my_customer_types');
               this.$store.dispatch('my_service_types');
               this.$store.dispatch('my_tracks');
               this.$store.dispatch('my_projects');
               this.$store.dispatch('my_categories');
               this.$store.dispatch('my_mechanics');
               this.$store.dispatch('my_reqs');
               this.$store.dispatch('my_jobcards').then(() =>{
                   this.initDatable();
               });
           } ,
            reverseJob(rq){
            this.$store.dispatch('updateJobcard',rq)
                    .then(() =>{
                        if (this.parts.length > 1) {
                            this.show_reversal = true;
                            this.add_jobcard = false;
                        }
                    })
            },
            editJobcard(job){
                this.$store.dispatch('updateJobcard',job)
                    .then(() =>{
                        if (this.parts.length > 1){
                                this.editing=true;
                                this.add_jobcard=true;
                            }
                    })

            },
            deleteJobcard(id){
                axios.delete(`job-card/${id}`)
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
                eventBus.$on('listJobcards',(job) =>{
                    this.tableData.unshift(job);
                    this.add_jobcard =false;
                    this.show_add_text=false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_jobcard = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.show_add_text=false;
                    this.initDatable();
                });
                eventBus.$on('updateJobcard',(job)=>{
                    this.add_jobcard = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.show_add_text=false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == job.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(job);
                    this.initDatable();
                });
                eventBus.$on('close_form',() => {
                    this.show_form = false;
                    this.initDatable();
                });
                eventBus.$on('cancel_job',() =>{
                    this.show_form = false;
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
            JobCard,
            JobForm,
            Reversal
        }
    }
</script>

<style scoped>

</style>
