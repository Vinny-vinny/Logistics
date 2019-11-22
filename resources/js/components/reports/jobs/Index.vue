<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Job Card Reports</h3>
                    <download-excel
                        v-if="jobs.length"
                        class="btn btn-primary pull-right"
                        :data="jobs">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Download
                    </download-excel>
                   <button class="btn btn-outline-danger pull-right mr" @click="back()">Back</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Card #</th>
                            <th>Machine</th>
                            <th>Driver</th>
                            <th>Created At</th>
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="job in jobs">
                            <td>{{job.Card}}</td>
                            <td>{{job.Machine}}</td>
                            <td>{{job.Driver}}</td>
                            <td>{{job.Created}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import JobcardReport from "./JobcardReport";
    export default {
       mounted(){
            this.initDatable();
        },
        computed:{
          jobs(){
              return this.$store.state.jobs;
          }
        },
        methods:{
            back(){
              eventBus.$emit('back');
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
            JobcardReport
        }
    }
</script>

<style scoped>

</style>
