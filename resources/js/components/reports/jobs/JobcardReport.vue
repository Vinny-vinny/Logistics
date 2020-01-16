 <template>
    <div>
        <app-index v-if="show_job"></app-index>
        <!-- Main content -->
        <section class="content" v-if="!show_job">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Choose Period</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="jobCards()">
                        <div class="form-group">
                            <label>From</label>
                            <datepicker v-model="form.from" required></datepicker>
                            </div>
                        <div class="form-group">
                            <label>To</label>
                            <datepicker v-model="form.to" required></datepicker>
                        </div>
                        <button type="submit" class="btn btn-primary">Generate</button>
                        </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
   import Index from '../../reports/jobs/Index';
    export default {
           data(){
            return {
                form:{
                    from:'',
                    to:''
                    },
                show_job: false,
                requisitions:[],
                parts:{},
                projects:{}
            }
        },
        created(){
          this.listen();
          this.getParts();
          this.getProjects();
        },
           methods:{
            getParts(){
           axios.get('parts')
           .then(res => {
            this.parts = res.data

        })
            },
            getProjects(){
             axios.get('asset-category')
             .then(res => this.projects = res.data)
            },
            jobCards(){
                this.form.from = moment(this.form.from).format('YYYY-MM-DD');
                this.form.to = moment(this.form.to).format('YYYY-MM-DD');
                if (this.form.from > this.form.to){
                    return this.$toastr.e('Date from cannot be greater than Date to.')
                }
                 if (this.form.from =='' || this.form.to ==''){
                    return this.$toastr.e('Date from and Date to cannot be empty')
                }
                 let requistions = [];
                 axios.post('job-report',this.form)
                    .then(res =>{   
                     let jobs =res.data.filter(j => j.requistion_id !==null);
            
            let internal_reqs = [];
            let external_reqs = [];          
            let requistions = []; 
            jobs.forEach(j => {
                if (j.requisitions !==null) {                 
                  if (j.requisitions.type =='Internal') {                   
                   internal_reqs.push(j.requisitions['inventory_items_internal']);
                  }
                  if (j.requisitions.type =='External') {                   
                   external_reqs.push(j.requisitions['inventory_items_external']);
                  }
                }

if (external_reqs.length > 0) {
  console.log('yuuuu')
              external_reqs.forEach(k => {

                  // requistions.push({
                  //           'date': jobs[j]['start_date'], 
                  //           'code': this.parts.find(pa => pa.id ==k.part).code,               
                  //           'description': this.parts.find(pa => pa.id ==k.part).description,
                  //           'reference': jobs[j]['card_no'],
                  //           'quantity': k.quantity,
                  //           'unit_cost': k.unit_cost,
                  //           'amount': k.total_cost,
                  //           'project': this.projects.find(pro => jobs[j]['requisitions']['project_id'] == pro.id).name,
                  //       })

                  requistions.push({
                    'part': k['part'] 
                  });
                  console.log('--test--')
                 console.log(k);
                   console.log('--end test')
              });

           }
           // if (internal_reqs.length > 0) {
           //    internal_reqs.forEach(k => {
           //       requistions.push({
           //                  'date': jobs[j]['start_date'], 
           //                  'code': this.parts.find(pa => pa.id ==k.part).code,               
           //                  'description': this.parts.find(pa => pa.id ==k.part).description,
           //                  'reference': jobs[j]['card_no'],
           //                  'quantity': k.quantity,
           //                  'unit_cost': k.unit_cost,
           //                  'amount': k.total_cost,
           //                  'project': this.projects.find(pro => jobs[j]['requisitions']['project_id'] == pro.id).name,
           //              })
           //    });
           //  }
            
            //  console.log(requistions)  
            })

            // if (internal_reqs.length > 0) {
            //   internal_reqs.forEach(k => {
            //            requistions.push({
            //                 'date': jobs[p]['start_date'], 
            //                 'code': this.parts.find(pa => pa.id ==k.part).code,               
            //                 'description': this.parts.find(pa => pa.id ==k.part).description,
            //                 'reference': jobs[p]['card_no'],
            //                 'quantity': k.quantity,
            //                 'unit_cost': k.unit_cost,
            //                 'amount': k.total_cost,
            //                 'project': this.projects.find(pro => jobs[p]['requisitions']['project_id'] == pro.id).name,
            //             })
            //   });
            // }
           console.log('--internal_reqs---')
            
            console.log('--end internal reqs--')

            console.log('--external reqs--')
              external_reqs.forEach(ex => console.log(ex));
            console.log('--end reqs--')

                 //  this.$store.dispatch('listJobReports',res.data) 
                 // this.show_job = true;
                 

                  })
                    .catch(error => error.response)
                   
            },
               listen(){
                eventBus.$on('back', ()=>{
                    this.show_job = false;
                })
               }
          },
        components:{
            datepicker,
            'app-index':Index
        }
    }
</script>

<style scoped>

</style>
