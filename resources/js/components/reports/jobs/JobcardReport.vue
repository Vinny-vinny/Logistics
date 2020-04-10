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
                        <button type="submit" class="btn btn-primary" v-if="parts.length > 0">Generate</button>
                        </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
   import Index from '../../reports/jobs/Index';
    import {mapGetters} from "vuex";
    export default {
           data(){
            return {
                form:{
                    from:'',
                    to:''
                    },
                show_job: false,
                requisitions:[],
            }
        },
        created(){
          this.listen();
          this.getDetails();
        },
        computed:{
          ...mapGetters({
             parts:'all_parts',
             projects:'all_projects'
          })
        },
           methods:{
           getDetails(){
           this.$store.dispatch('my_parts');
           this.$store.dispatch('my_projects');
            },
            jobCards(){
               if(this.form.from ==='' || this.form.to ===''){
                   return this.$toastr.e('All fields are required.')
               }
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
            for(let p=0;p<jobs.length;p++){
              if (jobs[p]['requisitions'] !==null) {
               // console.log(jobs[n]['requisitions'])
                if (jobs[p]['requisitions']['type'] =='Internal') {
                  let int_reqs = JSON.parse(jobs[p]['requisitions']['inventory_items_internal']);

                  for(let k=0;k<int_reqs.length;k++){
                       requistions.push({
                            'date': jobs[p]['start_date'],
                            'code': this.parts.find(pa => pa.id ==int_reqs[k]['part']).code,
                            'description': this.parts.find(pa => pa.id ==int_reqs[k]['part']).description,
                            'reference': jobs[p]['card_no'],
                            'quantity': int_reqs[k]['quantity'],
                            'unit_cost': int_reqs[k]['unit_cost'],
                            'amount': int_reqs[k]['total_cost'],
                            'project': this.projects.find(pro => jobs[p]['requisitions']['project_id'] == pro.id).name,
                        })
                  }
                }
                if (jobs[p]['requisitions']['type'] =='External') {
                  let ex_reqs = JSON.parse(jobs[p]['requisitions']['inventory_items_external']);
                  for(let k=0;k<ex_reqs.length;k++){
                       requistions.push({
                            'date': jobs[p]['start_date'],
                            'code': this.parts.find(pa => pa.id ==ex_reqs[k]['part']).code,
                            'description': this.parts.find(pa => pa.id ==ex_reqs[k]['part']).description,
                            'reference': jobs[p]['card_no'],
                            'quantity': ex_reqs[k]['quantity'],
                            'unit_cost': ex_reqs[k]['unit_price'],
                            'amount': ex_reqs[k]['total_price'],
                            'project': this.projects.find(pro => jobs[p]['requisitions']['project_id'] == pro.project_link).name,
                        })

                  }

                }
              }
            }

                 this.$store.dispatch('listJobReports',requistions)
                 this.$store.dispatch('getPeriod',{from: moment(this.form.from).format("DD-MM-YYYY"),to:moment(this.form.to).format("DD-MM-YYYY")})
                 this.show_job = true;

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
