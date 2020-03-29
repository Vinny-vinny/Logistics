<template>
    <div>

        <!-- Main content -->
        <section class="content">

            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Generate Requisition</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="generateReq()">
                        <div class="form-group">
                            <label>External Reference</label>
                            <input type="text" class="form-control" v-model="form.external_reference" required>
                        </div>
                        <div class="form-group">
                            <label>Project</label>
                            <model-select :options="projects"
                                          v-model="form.project_id"
                                          @input="subProject()"
                                          required>
                            </model-select>
                        </div>
                        <div class="form-group">
                            <label>Vehicle</label>
                            <model-select :options="subprojects"
                                          v-model="form.subproject_id"
                            >
                            </model-select>
                        </div>
                        <div class="form-group">
                            <label>Requisition Type</label>
                            <select class="form-control select2" v-model="form.type" required>
                                <option value="Internal">Stock Issue</option>
                                <option value="External">Invoice Customer</option>
                            </select>
                        </div>
                        <div class="form-group" v-show="show_customer">
                            <label>Customers</label>
                            <model-select :options="customers"
                                          v-model="form.customer_id"
                                             >
                            </model-select>
                        </div>
                        <button class="btn btn-success" type="submit">Generate</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import { ModelSelect } from 'vue-search-select';
    export default {
        data(){
            return{
                form:{
                    requested_by:User.id(),
                    type:'',
                    subproject_id:'',
                    project_id:'',
                    customer_id:'',
                    external_reference:'',
                    inventory_items_internal: [{part: '', uom:'',quantity: '',unit_cost:'',total_cost:'',total_cost_inclusive:'',qty_available:''}],
                    inventory_items_external: [{part: '', uom:'',quantity: '',unit_price:'',total_price:'',total_price_inclusive:'',qty_available:''}]
                },
                machines:{},
                projects:[],
                subprojects:[],
                show_customer:false,
                customers:[]
            }
        },
        created() {
            this.getMachines();
            this.getProjects();
            this.getCustomers();

        },
        watch:{
            'form.type'(){
                if (this.form.type ==='Internal'){
                    this.show_customer = false;
                    this.form.customer_id = '';
                }else{
                    this.show_customer = true;
                }
            }
        },
        methods:{
            getCustomers(){
                axios.get('customers')
                    .then(res => {
                        this.all_customers = res.data;
                        res.data.forEach(c => {
                            this.customers.push({
                                'value': c.id,
                                'text': c.name
                            })
                        })
                    })
            },
            subProject(){
                this.subprojects = [];
                let subp = this.machines.filter(vehicle => vehicle.asset_category_id == this.form.project_id);
                subp.forEach(p => {
                    this.subprojects.push({
                        'value': p.project_link,
                        'text': p.description
                    })
                })
            },

            getProjects() {
                axios.get('asset-category')
                    .then(res => {
                        res.data.forEach(p => {
                            this.projects.push({
                                'value': p.project_link,
                                'text': p.name
                            })
                        })
                    })
            },
            getMachines(){
                axios.get('machines')
                    .then(res => {
                        this.machines = res.data
                    })
            },
            generateReq(){
                if (this.form.type ==='External'){
                    if (this.form.customer_id ==''){
                        return this.$toastr.e('Customer field cannot be empty');
                    }
                }
                axios.post('generate-requisition',this.form)
                    .then(res => {
                        this.$store.dispatch('reqFormData',res.data);
                        eventBus.$emit('close_req_form');
                        // console.log(res.data)
                       // this.$router.push({path:`/jobcard-form/${res.data.id}`});
                        //eventBus.$emit('close_form');
                    })
                    .catch(err => console.log(err))
            },
            cancel(){
                eventBus.$emit('cancel_job');
            }
        },
        components:{
            ModelSelect
        }
    }
</script>

<style scoped>

</style>
