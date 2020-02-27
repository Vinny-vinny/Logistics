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
    export default {
        data(){
            return {
                tableData: [],
                add_jobcard: false,
                editing: false,
                show_form:false,               
                show_reversal:false         
            }
        },
        created(){
            this.listen();
            this.getJobs();
        },    
        methods:{
                 reverseJob(rq){
            this.$store.dispatch('updateJobcard',rq)
                    .then(() =>{                       
                        this.show_reversal = true;
                        this.add_jobcard=false;
                    })
            },
            getJobs(){
                axios.get('job-card')
                    .then(res =>{
                        this.tableData = res.data.filter(job => job.reversal_ref !=='' && job.reversal_ref !==null);
                        this.initDatable()
                    })
                    .catch(error => Exception.handle(error))
                this.initDatable();
            },
            editJobcard(job){
                this.$store.dispatch('updateJobcard',job)
                    .then(() =>{
                            this.editing=true;
                            this.add_jobcard=false;
                            this.show_reversal = true;

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
                    this.initDatable();
                    this.getJobs();
                });
                eventBus.$on('cancel',()=>{
                    this.add_jobcard = false;
                    this.editing = false;
                    this.show_reversal = false;
                    this.initDatable();
                    this.getJobs();
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
            ShowReversal            
        }
    }
</script>

<style scoped>

</style>
