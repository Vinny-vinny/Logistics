<template>
    <div>
        <show-reversal v-if="show_reversal" :reverse="show_reversal"></show-reversal>
        <!-- Main content -->
        <section class="content" v-if="!add_jobcard && !show_form && !show_reversal">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reversed Job Cards</h3>
                   </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Reverse Ref#</th>
                            <th>Card#</th>
                            <th>Machine</th>
                            <th>Driver</th>
                            <th>Project</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="job in tableData">
                            <td>{{job.reversal_ref}}</td>
                            <td>{{job.card_no}}</td>
                            <td>{{job.machine}}</td>
                            <td>{{job.driver}}</td>
                            <td>{{job.project}}</td>
                              <td>
                                <button class="btn btn-info btn-sm" @click="editJobcard(job)"><i class="fa fa-eye"></i></button>
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
    import ShowReversal from "./ShowReversal";
    import {mapGetters} from "vuex";
    export default {
        data(){
            return {
                add_jobcard: false,
                editing: false,
                show_form:false,
                show_reversal:false
            }
        },
        created(){
        this.getAllDetails();
        this.listen();
        },
        computed:{
          ...mapGetters({
              jobs:'all_jobs',
              parts:'all_parts'
          }),
            tableData(){
              if (this.jobs.length !==undefined){
               return this.jobs.filter(job => job.reversal_ref !=='' && job.reversal_ref !==null);
              }
            }
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

            editJobcard(job){
                this.$store.dispatch('updateJobcard',job)
                    .then(() =>{
                        if (this.jobs.length > 1) {
                            this.editing = true;
                            this.add_jobcard = false;
                            this.show_reversal = true;
                        }

                    })

            },

            listen(){
                eventBus.$on('listJobcards',(job) =>{
                    this.tableData.unshift(job);
                    this.add_jobcard =false;
                    this.initDatable();

                });
                eventBus.$on('cancel',()=>{
                    this.add_jobcard = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.initDatable();

                });
                eventBus.$on('updateJobcard',(job)=>{
                    this.add_jobcard = false;
                    this.editing = false;
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
            ShowReversal
        }
    }
</script>

<style scoped>

</style>
