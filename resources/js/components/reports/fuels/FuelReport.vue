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
                        <button type="submit" class="btn btn-primary">Generate</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
    import Index from '../../reports/fuels/Index';
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
        },
        methods:{
            fuel(){
                this.form.from = moment(this.form.from).format('YYYY-MM-DD');
                this.form.to = moment(this.form.to).format('YYYY-MM-DD');
                if (this.form.from > this.form.to){
                    return this.$toastr.e('Date from cannot be greater than Date to.')
                }
                axios.post('fuel-report',this.form)
                    .then(res =>{
                        this.show_fuel = true;
                        this.$store.dispatch('listFuelReports',res.data)
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
