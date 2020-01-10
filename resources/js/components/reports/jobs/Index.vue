<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Stock Issue</h3>
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
                            <th>Date</th>
                            <th>Item Code</th>
                            <th>Item Description</th>
                            <th>Reference</th>
                            <th>Quantity</th>
                            <th>Unit Cost</th>
                            <th>Amount</th>
                            <th>Project</th>
                            
                            </tr>
                        </thead>
                        <tbody>
                        <tr v-for="job in jobs">
                            <td>{{job.date}}</td>
                            <td>{{job.code}}</td>
                            <td>{{job.description}}</td>
                            <td>{{job.reference}}</td>  
                            <td>{{job.quantity}}</td>
                            <td>{{job.unit_cost}}</td>
                            <td>{{job.amount}}</td>    
                            <td>{{job.project}}</td>                         
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
            this.itemsIssued();
            this.getParts();
            this.getProjects();

        },
        computed:{
          jobs(){            
              return this.$store.state.jobs;
          },
        
        },
        methods:{
         itemsIssued(){
          setTimeout(()=>{  
            for(let i=0;i<this.jobs.length;i++){
             this.requistions.push({
                'Date':this.jobs[i]['date'],
                'Item Code': this.jobs[i]['code'],
                'Item Description': this.jobs[i]['description'],
                'Reference': this.jobs[i]['reference'],
                'Quantity': this.jobs[i]['quantity'],
                'Unit Cost': this.jobs[i]['unit_cost'],
                'Amount': this.jobs[i]['amount'],
                'Project': this.jobs[i]['project']
             })
            }          
                  
          },1000) 
          },
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
