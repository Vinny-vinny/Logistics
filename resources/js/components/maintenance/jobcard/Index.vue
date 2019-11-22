<template>
    <div>
        <job-card v-if="add_jobcard" :edit="editing"></job-card>
        <!-- Main content -->
        <section class="content" v-if="!add_jobcard">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Card</h3>
                    <button class="btn btn-primary pull-right" @click="add_jobcard=true">Add Job Card</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Card #</th>
                            <th>Machine</th>
                            <th>Driver</th>
                            <th>Supplier</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="job in tableData">
                            <td>{{job.id}}</td>
                            <td>{{job.card_no}}</td>
                            <td>{{job.machine}}</td>
                            <td>{{job.driver}}</td>
                             <td>{{job.supplier}}</td>
                              <td>
                                <button class="btn btn-success btn-sm" @click="editJobcard(job)"><i class="fa fa-edit"></i></button>
                                <router-link :to="{path:'/job-card/'+job.id}" class="btn btn-info btn-sm"><i class=" fa fa-eye"></i></router-link>
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
    export default {
        data(){
            return {
                tableData: [],
                add_jobcard: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getJobs();
            console.log(moment('6.00pm','h:mm A').format('HH:mm') > moment('5:00pm','h:mm A').format('HH:mm'))
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getJobs(){
                axios.get('job-card')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editJobcard(job){
                this.$store.dispatch('updateJobcard',job)
                    .then(() =>{
                        this.editing=true;
                        this.add_jobcard=true;
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
                    console.log(job);
                    this.tableData.unshift(job);
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
            JobCard
        }
    }
</script>

<style scoped>

</style>
