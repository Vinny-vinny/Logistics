<template>
    <div>
        <app-index v-if="show_diesel"></app-index>
        <!-- Main content -->
        <section class="content" v-if="!show_diesel">
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
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
    import Index from '../../../reports/fuels/diesel/Index';
    export default {
        data(){
            return {
                form:{
                    from:'',
                    to:''
                },
                show_diesel: false,
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
             .then(res => this.parts = res.data)
            },
            getProjects(){
            axios.get('asset-category')
            .then(res => this.projects = res.data)
            },
            fuel(){
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
                        let result = [];
                        for(let i=0;i<res.data.length;i++){
                          result.push({
                            'date': res.data[i]['date_fueled'],
                            'item_code': this.parts.find(p => p.description ==res.data[i]['fuel_type']).code,
                            'decription':res.data[i]['fuel_type'],
                            'reference': res.data[i]['fuel_no'],
                            'quantity': res.data[i]['litres'],
                            'unit_cost': res.data[i]['rate'],
                            'amount': res.data[i]['rate'] * res.data[i]['litres'],
                            'project': this.projects.find(p => p.project_link == res.data[i]['asset_category_id']).name
                          })
                        }
                       // console.log(result)
                        this.show_diesel = true;
                        this.$store.dispatch('listDieselReports',result)
                         this.$store.dispatch('getPeriod',{from: moment(this.form.from).format("DD-MM-YYYY"),to:moment(this.form.to).format("DD-MM-YYYY")})
                    })
                    .catch(error => error.response)
            },
            listen(){
                eventBus.$on('back', ()=>{
                    this.show_diesel = false;
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
