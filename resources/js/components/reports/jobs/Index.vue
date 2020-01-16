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
            items(){
          return this.$store.state.jobs; 
            },
          jobs(){          
             
            setTimeout(()=>{
             let jobs =this.items.filter(j => j.requistion_id !==null);
              //console.log(jobs)
            let internal_reqs = [];
            let external_reqs = [];          
            let requistions = []; 
            jobs.forEach(j => {
                if (j.requisitions !==null) {                 
                  if (j.requisitions.type =='Internal') {                   
                   internal_reqs.push(j.requisitions['inventory_items_internal']);
                  }
                }
                
            })

            internal_reqs.forEach(h => console.log(h))
            //console.log(internal_reqs)
            // jobs.map(job => {
            //     console.log(job.requisitions);
            //     if (job.requisitions.type =='Internal') {
            //       //job.requisitions.inventory_items_internal.forEach(l => console.log('wee'));
            //     }
            // })
           // console.log(internal_reqs);
            //  for(let p=0;p<jobs.length;p++){  
            // console.log(jobs[p]['requisitions']['type']); 

            //    if(jobs[p]['requisitions']['type'] =='Internal'){ 

            //        let reqs_internal = jobs[p]['requisitions']['inventory_items_internal'];
            //        console.log(reqs_internal) 
            //           reqs_internal.forEach(s => console.log(s));
                   //  reqs_internal.forEach(k => {
                   //    console.log('oooo')
                   //    console.log(k)
                   //     requistions.push({
                   //          'date': jobs[p]['start_date'], 
                   //          'code': this.parts.find(pa => pa.id ==k.part).code,               
                   //          'description': this.parts.find(pa => pa.id ==k.part).description,
                   //          'reference': jobs[p]['card_no'],
                   //          'quantity': k.quantity,
                   //          'unit_cost': k.unit_cost,
                   //          'amount': k.total_cost,
                   //          'project': this.projects.find(pro => jobs[p]['requisitions']['project_id'] == pro.id).name,
                   //      })
                           
                   // })                      
                   
               // }
                

                //  else if(jobs[p]['requisitions']['type'] =='External'){                
                //     let external_reqs = jobs[p]['requisitions']['inventory_items_external']; 
                //        for(let k=0;k<external_reqs.length;k++){
                //         requistions.push({
                //             'date': jobs[p]['start_date'],
                //             'code': this.parts.find(pa => pa.id ==external_reqs[k]['part']).code,               
                //             'description': this.parts.find(pa => pa.id ==external_reqs[k]['part']).description,
                //            'reference': jobs[p]['card_no'],
                //             'quantity': external_reqs[k]['quantity'],
                //             'unit_cost': external_reqs[k]['unit_price'],
                //             'amount': external_reqs[k]['total_price'],
                //             'project': this.projects.find(pro => jobs[p]['requisitions']['project_id'] == pro.id).name,
                //         })
                //        }                        
                      
                // }         
               
               
                 // }
                  return requistions;
            },2000)
          }
        
        },
        methods:{
         itemsIssued(){
            return [];
          setTimeout(()=>{  
            // for(let i=0;i<this.jobs.length;i++){
            //  this.requistions.push({
            //     'Date':this.jobs[i]['date'],
            //     'Item Code': this.jobs[i]['code'],
            //     'Item Description': this.jobs[i]['description'],
            //     'Reference': this.jobs[i]['reference'],
            //     'Quantity': this.jobs[i]['quantity'],
            //     'Unit Cost': this.jobs[i]['unit_cost'],
            //     'Amount': this.jobs[i]['amount'],
            //     'Project': this.jobs[i]['project']
            //  })
            // }          
                  
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
