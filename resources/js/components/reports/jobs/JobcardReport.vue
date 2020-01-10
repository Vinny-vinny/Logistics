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
            let external_reqs = [];          ;
            
             for(let p=0;p<jobs.length;p++){              
               if(jobs[p]['requisitions']['type'] =='Internal'){               
                    let reqs_internal = JSON.parse(jobs[p]['requisitions']['inventory_items_internal']); 
                       for(let k=0;k<reqs_internal.length;k++){
                        requistions.push({
                            'date': jobs[p]['start_date'], 
                            'code': this.parts.find(pa => pa.id ==reqs_internal[k]['part']).code,               
                            'description': this.parts.find(pa => pa.id ==reqs_internal[k]['part']).description,
                            'reference': jobs[p]['card_no'],
                            'quantity': reqs_internal[k]['quantity'],
                            'unit_cost': reqs_internal[k]['unit_cost'],
                            'amount': reqs_internal[k]['total_cost'],
                            'project': this.projects.find(pro => jobs[p]['requisitions']['project_id'] == pro.id).name,
                        })
                       }                            
                      
                }

                 else if(jobs[p]['requisitions']['type'] =='External'){                
                    let external_reqs = JSON.parse(jobs[p]['requisitions']['inventory_items_internal']); 
                       for(let k=0;k<reqs_internal.length;k++){
                        requistions.push({
                            'date': jobs[p]['start_date'],
                            'code': this.parts.find(pa => pa.id ==external_reqs[k]['part']).code,               
                            'description': this.parts.find(pa => pa.id ==external_reqs[k]['part']).description,
                           'reference': jobs[p]['card_no'],
                            'quantity': external_reqs[k]['quantity'],
                            'unit_cost': external_reqs[k]['unit_price'],
                            'amount': external_reqs[k]['total_price'],
                            'project': this.projects.find(pro => jobs[p]['requisitions']['project_id'] == pro.id).name,
                        })
                       }                        
                      
                }         
               
                this.$store.dispatch('listJobReports',requistions) 
                 this.show_job = true;
                 
                  }

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
