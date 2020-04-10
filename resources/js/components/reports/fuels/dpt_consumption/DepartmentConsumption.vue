<template>
    <div>
        <app-index v-if="show_fuel"></app-index>
        <!-- Main content -->
        <section class="content" v-if="!show_fuel">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Choose Period</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="fuel()">
                        <div class="form-group">
                            <label>From</label>
                            <datepicker v-model="form.from" required></datepicker>
                        </div>
                        <div class="form-group">
                            <label>To</label>
                            <datepicker v-model="form.to" required></datepicker>
                        </div>
                        <button type="submit" class="btn btn-primary" v-if="departments.length > 0">Generate</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
    import Index from '../../../reports/fuels/dpt_consumption/Index';
    import {mapGetters} from "vuex";
    export default {
        data(){
            return {
                form:{
                    from:'',
                    to:''
                },
                show_fuel: false
            }
        },
        created(){
            this.listen();
            this.$store.dispatch('my_projects');
        },
        computed:{
          ...mapGetters({
              departments:'all_projects'
          })
        },
        methods:{
            fuel(){
                if(this.form.from ==='' || this.form.to ===''){
                    return this.$toastr.e('All fields are required.')
                }
                this.form.from = moment(this.form.from).format('YYYY-MM-DD');
                this.form.to = moment(this.form.to).format('YYYY-MM-DD');
                if (this.form.from > this.form.to){
                    return this.$toastr.e('Date from cannot be greater than Date to.')
                }
                   if (this.form.from =='' || this.form.to ==''){
                    return this.$toastr.e('Date from and Date to cannot be empty.')
                }
                axios.post('diesel-analysis',this.form)
                    .then(res =>{
                        let dept_obj = {};
                        let result = [];
                        for(let i=0;i<res.data.length;i++){
                            if(!dept_obj[res.data[i]['asset_category_id']]){
                                dept_obj[res.data[i]['asset_category_id']] = res.data[i];
                                 dept_obj[res.data[i]['asset_category_id']]['total'] = res.data[i]['rate'] * res.data[i]['litres'];
                            }
                            else if(dept_obj[res.data[i]['asset_category_id']]){
                              dept_obj[res.data[i]['asset_category_id']]['litres'] += res.data[i]['litres'];
                               dept_obj[res.data[i]['asset_category_id']]['total'] += (res.data[i]['rate'] * res.data[i]['litres']);
                            }
                        }
                        for(var i in dept_obj){
                            if(dept_obj.hasOwnProperty(i)){
                               result.push({
                                'department': this.departments.find(d => d.project_link == dept_obj[i]['asset_category_id']).name,
                                'qty': dept_obj[i]['litres'],
                                'percentage': ((dept_obj[i]['litres']/dept_obj[i]['total'])*100).toFixed(2)
                               })
                            }
                        }
                        this.show_fuel = true;
                        this.$store.dispatch('listconsumptionReports',result)
                         this.$store.dispatch('getPeriod',{from: moment(this.form.from).format("DD-MM-YYYY"),to:moment(this.form.to).format("DD-MM-YYYY")})
                    })
                    .catch(error => error.response)
            },
            listen(){
                eventBus.$on('back', ()=>{
                    this.show_fuel = false;
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
