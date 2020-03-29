 <template>
    <div>
        <app-index v-if="show_km"></app-index>
        <!-- Main content -->
        <section class="content" v-if="!show_km">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Choose Period</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="KmHr()">
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
   import Index from '../../reports/kmperhr/Index';
    export default {
           data(){
            return {
                form:{
                    from:'',
                    to:''
                    },
                show_km: false,
                machines:{}
            }
        },
        created(){
          this.listen();
          this.getMachines();
        },
           methods:{
            getMachines(){
            axios.get('machines')
            .then(res => {
                this.machines = res.data;
            })
            },
            KmHr(){
                this.form.from = moment(this.form.from).format('YYYY-MM-DD');
                this.form.to = moment(this.form.to).format('YYYY-MM-DD');
                if (this.form.from > this.form.to){
                    return this.$toastr.e('Date from cannot be greater than Date to.')
                }
                if (this.form.from =='' ||  this.form.to=='') {
                    return this.$toastr.e('Sorry,Date to and Date from cannot be empty.');
                }
                 axios.post('km-per-hr',this.form)
                    .then(res =>{  
                       this.show_km = true;
                       this.$store.dispatch('listKMHRReports',res.data)
                       this.$store.dispatch('getPeriod',{from: moment(this.form.from).format("DD-MM-YYYY"),to:moment(this.form.to).format("DD-MM-YYYY")})               
                   
                    })
                    .catch(error => error.response)
            },
               listen(){
                eventBus.$on('back', ()=>{
                    this.show_km = false;
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
