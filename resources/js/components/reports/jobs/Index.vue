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
                        :data="requistions">
                        <i class="fa fa-file-excel-o" aria-hidden="true"></i> Download
                    </download-excel>
                   <button class="btn btn-outline-danger pull-right mr" @click="back()">Back</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>Reference #</th>
                            <th>Machine</th>
                            <th>Created At</th>
                            <th>Actual Service Date</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="job in jobs">
                            <td>{{job.Reference}}</td>
                            <td>{{job.Machine}}</td>
                            <td>{{job.Created}}</td>
                            <td>{{job.service_date}}</td>                            
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
        data(){
            return {
                requistions:[],
                parts:{},
                projects:{}
            }
        },
       mounted(){
            this.initDatable();
            this.itemsIssued;
            this.getParts();
            this.getProjects();

        },
        computed:{
          jobs(){            
              return this.$store.state.jobs;
          },
          itemsIssued(){
          setTimeout(()=>{
       let jobs =this.$store.state.jobs.filter(j => j.requistions !==null);
            let requistions = [];
            let internal_reqs = [];
            let external_reqs = [];
            for(let i=0;i<jobs.length;i++){
                if(jobs[i]['requistions']['type'] =='Internal'){
                    let reqs_internal = JSON.parse(jobs[i]['requistions']['inventory_items_internal']); 
                       for(let k=0;k<reqs_internal.length;k++){
                        this.requistions.push({
                            'Item Code': this.parts.find(p => p.id ==reqs_internal[k]['part']).code,               
                            'Item Description': this.parts.find(p => p.id ==reqs_internal[k]['part']).description,
                            'Reference': jobs[i]['Reference'],
                            'Quantity': reqs_internal[k]['quantity'],
                            'Unit Cost': reqs_internal[k]['unit_cost'],
                            'Amount': reqs_internal[k]['total_cost'],
                            'Project': this.projects.find(p => jobs[i]['requistions']['project_id'] == p.id).name,
                        })
                       }         
                      
                } 
                  else if(jobs[i]['requistions']['type'] =='External'){
                    let reqs_external = JSON.parse(jobs[i]['requistions']['inventory_items_external']); 
                       for(let k=0;k<reqs_external.length;k++){
                        this.requistions.push({
                            'Item Code': this.parts.find(p => p.id ==reqs_external[k]['part']).code,             
                            'Item Description': this.parts.find(p => p.id ==reqs_external[k]['part']).description,
                            'Reference': jobs[i]['Reference'],
                            'Quantity': reqs_external[k]['quantity'],
                            'Unit Cost': reqs_external[k]['unit_price'],
                            'Amount': reqs_external[k]['total_price'],
                            'Project': this.projects.find(p => jobs[i]['requistions']['project_id'] == p.id).name,
                        })
                       }         
                      
                }         

               }
                  
          },1000) 
          }
        },
        methods:{
            getParts(){
             axios.get('parts')
             .then(res => {
                this.parts = res.data;
             })
            },
            getProjects(){
             axios.get('asset-category')
             .then(res => {
                this.projects = res.data;
             })
            },
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
