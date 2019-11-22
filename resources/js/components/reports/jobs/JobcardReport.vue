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
                    <form @submit.prevent="jobCard()">
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
                show_job: false
            }
        },
        created(){
          this.listen();
        },
           methods:{
            jobCard(){
                this.form.from = moment(this.form.from).format('YYYY-MM-DD');
                this.form.to = moment(this.form.to).format('YYYY-MM-DD');
                if (this.form.from > this.form.to){
                    return this.$toastr.e('Date from cannot be greater than Date to.')
                }
                      axios.post('job-report',this.form)
                    .then(res =>{
                        console.log(res.data)
                      this.show_job = true;
                       this.$store.dispatch('listJobReports',res.data)
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
