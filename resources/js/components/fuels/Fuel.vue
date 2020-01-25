<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_fuel ? 'Update Fuel' : 'New Fuel'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveFuel()">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Extenal Reference(FID No)</label>
                                    <input type="text" class="form-control" v-model="form.external_reference" required>
                                </div>
                                <div class="form-group" v-if="company">
                                    <label>Project</label>                                   
                                      <model-select :options="projects"
                                        v-model="form.asset_category_id"  
                                        @input="subProject()"                    
                                        >
                                        </model-select>
                                </div>
                                <div class="form-group" v-if="company">
                                    <label>Vehicle</label>                                   
                                      <model-select :options="subprojects"
                                        v-model="form.vehicle_id"  
                                        @input="getFuelType()"                    
                                        >
                                        </model-select>
                                </div>
                                <div class="form-group">
                                    <label>Fuel On</label>
                                    <datepicker v-model="form.fuel_on" required></datepicker>
                                </div>
                                <div class="form-group">
                                    <label>Current Odometer Readings</label>
                                    <input type="number" v-model="form.odometer_readings" step="0.001"
                                           class="form-control">
                                </div>
                                <div class="form-group">
                                    <label>Litres</label>
                                    <input type="number" step="0.001" class="form-control" v-model="form.litres"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label>Fuel Type</label>
                                    <select name="fuel_type_id" v-model="form.fuel_type_id" class="form-control" @change="fuelRate()">
                                        <option :value="fuel.id" v-for="fuel in stks" :key="fuel.id">
                                            {{fuel.code}} - {{fuel.description}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="form.customer_id && form.fuel_type_id">
                                    <label>Fuel Rate</label>
                                    <input type="text" class="form-control" v-model="form.rate">
                                </div>
                                <div class="form-group" style="display:none">
                                    <label>Other Charges</label>
                                    <select name="expense_id" class="form-control" v-model="form.expense_id" @change="genExpenses()">
                                        <option :value="expense.id" v-for="expense in expenses" :key="expense.id">
                                            {{expense.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="jobs.length">
                                    <label>Jobcard</label>
                                    <select v-model="form.job_card_id" class="form-control">
                                        <option :value="job.id" v-for="job in jobs" :key="job.id">{{job.card_no}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Fueling</label></legend>
                                <div class="form-group" style="margin-left:100px;">
                                    <table width="100%">
                                        <tr>
                                            <td style="font-size:16px"><b>Fuel Type: </b>{{fuel_type}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px"><b>Previous Odometer Readings: </b>{{form.previous_odometer | number}}
                                            </td>
                                          </tr>
                                        <tr>
                                            <td style="font-size:16px"><b>Fuel Rate: </b>{{form.rate | number}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px"><b>Total Fuel Cost: </b>{{totalAmount | number}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px"><b>Total Expense: </b>{{(genExpenses()) | number}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px"><b>Grand Total: </b>{{grandTotal}}</td>
                                        </tr>
                                    </table>
                                </div>
                             </fieldset>
                                   <br>
                                <div class="form-group">
                                    <span class="reset_btn pull-right" @click="resetCustomerType">reset</span>
                                    <label>Customer Type</label>
                                    <select class="form-control" v-model="form.customer_type_id" required @change="customerTypes()">
                                        <option :value="type.id" v-for="type in customer_types" :key="type.id">{{type.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="show_customer">
                                    <span class="reset_btn pull-right" @click="resetCustomer">reset</span>
                                    <label>Customer</label>                                   
                                      <model-select :options="filtered_customers"
                                        v-model="form.customer_id"                         
                                        required>
                                        </model-select>
                                </div>
                                <div class="form-group">
                                    <label>Authorized By</label>
                                    <input type="text" class="form-control" v-model="username" disabled>
                                </div>                              
                                <div class="form-group">
                                    <label>Requested By</label>
                                    <input type="text" class="form-control" v-model="form.requested_by" required>
                                </div>
                                <div class="form-group">
                                    <label>Store Man</label>
                                    <input type="text" class="form-control" v-model="form.store_man" required>
                                </div>
                            </div>
                            </div>
                        <button type="submit" class="btn btn-primary">{{edit_fuel ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import datepicker from 'vuejs-datepicker';
    import { ModelSelect } from 'vue-search-select'; 
    export default {
        props: ['edit', 'other_fuel', 'add_fuel'],
        data() {
            return {
                form: {
                    litres: '',
                    vehicle_id: '',
                    fuel_on: '',
                    customer_id: '',                  
                    expense_id: '',
                    fuel_type_id: '',
                    authorized_by: '',
                    requested_by:'',
                    odometer_readings: '',
                    customer_type_id:'',
                    job_card_id:'',
                    asset_type:'',
                    store_man:'',
                    asset_category_id:'',
                    previous_odometer: 0,
                    external_reference:'',
                    rate:0,
                    id: ''
                },
                edit_fuel: this.edit,
                other_fuel_asset: this.other_fuel,
                customers: {},
                vehicles: {},
                drivers: {},
                expenses: {},
                company: false,
                other: false,
                fuel_types: {},
                fuel_type: '',
                total: 0,
                show_fuel_type: false,
                show_rate: false,             
                parts: {},
                total_expenses:0,
                pa:{},
                filtered_customers:[],
                customer_types:{},
                jobcards:{},
                show_customer:false,
                fuels:{},
                jobs:{},
                username:User.name(),
                projects:[],
                subprojects:[],
                stks:{}
            }
        },
        created() {

            this.listen();
            this.getCustomers();
            this.getVehicles();
            this.getDrivers();
            this.getExpenses();
            this.getFuelTypes();
            this.assetType();
            this.getParts();
            this.getCustomerTypes();
            this.getJobcards();
            this.getFuels();
            this.getProjects();
            this.getStk();
             

        },
       watch:{
        numConversion(){
         if(this.form.odometer_readings < 0 || (isNaN(parseFloat(this.form.odometer_readings)) && !isFinite(this.form.odometer_readings))){
             this.form.odometer_readings = 1; 
            }
             if(this.form.litres < 0 || (isNaN(parseFloat(this.form.litres)) && !isFinite(this.form.litres))){
             this.form.litres = 1; 
            }
        },
         'form.vehicle_id'(){
             this.jobs = {};
             axios.get('job-card')
                 .then(res => {
                     this.jobs = res.data.filter(f => f.machine_id ==this.form.vehicle_id);
                 })
         }
       },
        mounted: function () {
            var self = this;
            $('#datepicker').datepicker({
                onSelect: function (selectedDate, datePicker) {
                    self.form.fuel_on = selectedDate;
                }
            });
        },
        computed: {
            numConversion(){
            return [this.form.odometer_readings,this.form.litres].join();
            },
            totalAmount() {
                let total = this.form.rate * this.form.litres;
                return this.total = total;
            },

            grandTotal(){
                let total = 0;
                if (this.totalAmount > 0 || this.genExpenses() > 0){
                    total += parseFloat(this.totalAmount) + parseFloat(this.genExpenses());
                }
                return total;
            }
        },
        methods: {
            resetCustomerType(){
            this.form.customer_type_id = '';
            },
            resetCustomer(){
            this.form.customer_id = '';
            },
            getStk(){
                setTimeout(()=>{
             this.stks = this.parts.filter(p => p.code == 'LA0012' || p.code == 'LA0018');
            console.log(this.stks);
                },3000)
               
            
            },
            subProject(){
                this.subprojects =[];
             let subp = this.vehicles.filter(vehicle => vehicle.asset_category_id == this.form.asset_category_id);
             subp.forEach(p => {
                this.subprojects.push({
                    'value': p.id,
                    'text': p.code
                })
             })
            },
            getProjects(){
              axios.get('asset-category')
              .then(res => {
                res.data.forEach(p => {
                    this.projects.push({
                        'value': p.id,
                        'text': p.name
                    })
                })
                 
              })
            },
            getFuels(){
              axios.get('fuel')
              .then(res => {
                  this.fuels = res.data
              })
            },
            getJobcards(){
              axios.get('job-card')
              .then(res => {
                  this.jobcards = res.data
              })
            },
            getCustomerTypes(){
              axios.get('customer-types')
                .then(res => {
                    this.customer_types = res.data;
                })
            },            
            fuelRate(){
            setTimeout(()=>{
                let item =this.stks.find(s => s.id == this.form.fuel_type_id);
                this.form.rate = item.cost;
                this.fuel_type = item.description;              
            },100)

            },
            customerTypes(){
                this.filtered_customers = [];
                this.show_customer = true;
                let customers = this.customers.filter(c => c.customer_type_id == this.form.customer_type_id);              
                 customers.forEach(c => {
                    this.filtered_customers.push({
                        'value': c.id,
                        'text': c.name
                    })
                 })                 

            },
            genExpenses(){
                let total = 0;
                if (this.form.expense_id !=='') {
                    for (let i =0; i< this.expenses.length; i++){
                        if (this.expenses[i]['id'] === this.form.expense_id){
                            total += parseFloat(this.expenses[i]['cost']);
                        }
                    }
                }
                return total;
            },
            getParts() {
                axios.get('parts')
                    .then(res => {
                        this.parts = res.data

                    })

            },
            getFuelType() {
                this.vehicles.forEach(vehicle => {
                    if (vehicle.id === this.form.vehicle_id) {
                         this.form.previous_odometer = vehicle.odometer_readings;                                  
                    }
                })
            },

            assetType() {
                this.other_fuel_asset ? this.other = true : this.company = true;
            },
            getFuelTypes() {
                axios.get('fuel-types')
                    .then(types => {
                        this.fuel_types = types.data;
                    })
            },
            getCustomers() {
                axios.get('customers')
                    .then(customer => {
                        this.customers = customer.data;
                    })
            },
            getVehicles() {
                axios.get('machines')
                    .then(vehicle => {
                        this.vehicles = vehicle.data;
                    })
            },
            getDrivers() {
                axios.get('users')
                    .then(driver => {
                        this.drivers = driver.data;
                    })
            },
            getExpenses() {
                axios.get('expense')
                    .then(expense => {
                        this.expenses = expense.data;
                    })
            },
            convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            saveFuel() {
                 this.form.authorized_by = User.id();

                if (this.other && this.form.fuel_type_id ===''){
                    return this.$toastr.e('Select Fuel Type.');
                }
                if (this.form.odometer_readings < this.form.previous_odometer){
                    return this.$toastr.e('Current Odometer Readings cannot be less than Previous Readings.');
                }
                this.other_fuel_asset ? this.form.asset_type = 'other' : this.form.asset_type = 'company';

              this.edit_fuel ? this.update() : this.save();
            },
            save() {
                delete this.form.id;
                this.form.fuel_on = this.convertDate(this.form.fuel_on);
                this.form.authorized_by = User.id();
                axios.post('fuel', this.form)
                    .then(res => eventBus.$emit('listFuels', res.data))
                    .catch(error => error.response)
            },
            update() {
                axios.patch(`fuel/${this.form.id}`, this.form)
                    .then(res => {
                        this.edit_fuel = false;
                        eventBus.$emit('updateFuel', res.data);
                    })
                    .catch(error => error.response)
            },
            cancel() {
                eventBus.$emit('cancel');
            },
            listen() {

                if (this.edit) {
                    this.form = this.$store.state.fuels;
                    this.show_fuel_type = true;
                    this.fuel_type = this.$store.state.fuels.fuel_type;
                    this.form.rate = this.$store.state.fuels.rate;
                    this.form.previous_odometer = this.$store.state.fuels.previous_odometer;
                    this.show_rate = true;
                    this.show_customer = true;
                    axios.get(`users/${this.form.authorized_by}`)
                    .then(res => {
                        this.username = res.data.name;
                    })
                    
                    this.form.asset_type ==='other' ? this.other =true : this.company = true;
                     
                   setTimeout(()=>{                    
                    this.subProject();
                    this.customerTypes();
                   },1000)
                }
            },
           
        },
        components: {
            datepicker,
            ModelSelect
        }
    }
</script>

<style scoped>
.pt{
   margin-bottom: 10px;
}
    .qt{
        margin-bottom: 10px;
        margin-left: 10px;
    }
.toolbar ul {
    display:table-row;
}
.toolbar ul li
{
    display: table-cell;
    height: 100px;
    list-style-type: none;
    margin: 10px;
    vertical-align: middle;
}
.toolbar ul li a {
    display:table-cell;
    vertical-align: middle;
    height:100px;
    border: solid 1px black;
}
.toolbar ul li.button a {
    height:50px;
    border: solid 1px black;
}
</style>
