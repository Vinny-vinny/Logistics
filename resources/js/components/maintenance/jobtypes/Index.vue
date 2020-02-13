<template>
    <div>
        <job-type v-if="add_job_type" :edit="editing"></job-type>
        <!-- Main content -->
        <section class="content" v-if="!add_job_type">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Types</h3>
                    <button class="btn btn-primary pull-right" @click="add_job_type=true">Add Job Type</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Hourly Rate</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="rate in tableData">
                            <td>{{rate.id}}</td>
                            <td>{{rate.name}}</td>
                            <td>{{rate.description}}</td>
                            <td>{{rate.currency}} {{rate.hourly_rate}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editJobType(rate)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteJobType(rate.id)" style="display:none"><i class="fa fa-trash"></i></button>
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
    import JobType from "./JobType";
    export default {
        data(){
            return {
                tableData: [],
                add_job_type: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getJobTypes();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getJobTypes(){
                axios.get('job-types')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editJobType(job){
                this.$store.dispatch('updateJobType',job)
                    .then(() =>{
                        this.editing=true;
                        this.add_job_type=true;
                    })
            },
            deleteJobType(id){
                axios.delete(`job-types/${id}`)
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
                eventBus.$on('listJobTypes',(job) =>{
                    this.tableData.unshift(job);
                    this.add_job_type =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_job_type = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateJobType',(job)=>{
                    this.add_job_type = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == job.id){
                            this.tableData.splice(i,1);
                        }
                    }
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
            JobType
        }
    }
</script>

<style scoped>

</style>
