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
                    <button class="btn btn-success pull-right" @click="show_form=true">Print Job Card Form</button>
                    <button class="btn btn-primary pull-right mr" @click="addJob()">{{show_add_text ? 'Please wait...' :'Add Job Card'}}</button>
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
                                <button class="btn btn-success btn-sm" @click="editJobcard(job)"><i class="fa fa-edit"></i></button>
                                <router-link :to="{path:'/job-card/'+job.id}" class="btn btn-info btn-sm"><i class=" fa fa-eye"></i></router-link>
                                 <button v-if="!job.reversal_ref && job.invoiced==1" class="btn btn-danger btn-sm" @click="reverseJob(job)"><i class="fa fa-undo" aria-hidden="true"></i></button>
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
    export default {
        data(){
            return {
                tableData: [],
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
            this.listen();
            this.getJobs();
            this.getReqs();
            this.getCustomers();
            this.getParts();
            this.getMachines();
        },
        methods:{
            cust(){
              return this.check_customers;
            },
            part(){
              return this.check_parts;
            },
                 reverseJob(rq){
            this.$store.dispatch('updateJobcard',rq)
                    .then(() =>{
                        this.show_reversal = true;
                        this.add_jobcard=false;
                    })
            },
            addJob(){
                this.show_add_text=false;
                if (this.cust && this.part){
                    this.add_jobcard=true;
                    this.show_add_text=true;
                }
            },
            getCustomers(){
              axios.get('customers')
              .then(res => {
                  this.check_customers = true;
                  this.$store.dispatch('my_customers',res.data);
              })
            },
            getParts(){
                axios.get('parts')
                    .then(res => {
                        this.check_parts = true;
                        this.$store.dispatch('my_parts',res.data);
                    })
            },
            getMachines(){
                axios.get('machines')
                    .then(res => {
                        this.$store.dispatch('my_vehicles',res.data);
                    })
            },
            getJobs(){
                axios.get('job-card')
                    .then(res =>{
                        this.tableData = res.data.jobcards;
                        this.$store.dispatch('my_job_types',res.data.job_types);
                        this.$store.dispatch('my_job_categories',res.data.jobcard_categories);
                        this.$store.dispatch('my_customer_types',res.data.customer_types);
                        this.$store.dispatch('my_service_types',res.data.service_types);
                        this.$store.dispatch('my_tracks',res.data.tracks);
                        this.$store.dispatch('my_users',res.data.users);
                        this.$store.dispatch('my_categories',res.data.categories);
                        this.$store.dispatch('my_mechanics',res.data.mechanics);
                        this.$store.dispatch('my_projects',res.data.asset_categories);
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
                this.initDatable();
            },
            getReqs(){
                axios.get('requisitions')
                    .then(res =>{
                     this.$store.dispatch('my_reqs',res.data.requisitions)
            }).catch(error => Exception.handle(error))
            },
            editJobcard(job){
                this.$store.dispatch('updateJobcard',job)
                    .then(() =>{
                            if (this.cust && this.part){
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
                    this.getJobs();
                });
                eventBus.$on('cancel',()=>{
                    this.add_jobcard = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.show_add_text=false;
                    this.initDatable();
                    this.getJobs();
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
                    this.getJobs();
                    this.initDatable();
                });
                eventBus.$on('cancel_job',() =>{
                    this.show_form = false;
                    this.getJobs();
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
