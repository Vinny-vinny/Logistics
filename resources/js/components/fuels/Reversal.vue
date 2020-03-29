<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fuel Reversal</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveFuel()">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Extenal Reference(FID No)</label>
                                    <input type="text" class="form-control" v-model="form.external_reference" disabled>
                                </div>
                                <div class="form-group" v-if="company">
                                    <label>Project</label>
                                      <model-select :options="projects"
                                        v-model="form.asset_category_id"
                                        @input="subProject()"
                                        :is-disabled="true"
                                        >
                                        </model-select>
                                </div>
                                <div class="form-group" v-if="company">
                                    <label>Vehicle</label>
                                      <model-select :options="subprojects"
                                        v-model="form.vehicle_id"
                                        @input="getFuelType()"
                                        :is-disabled="true"
                                        >
                                        </model-select>
                                </div>
                                <div class="form-group">
                                    <label>Fuel On</label>
                                    <datepicker v-model="form.fuel_on" disabled></datepicker>
                                </div>
                                <div class="form-group">
                                    <label>Current Odometer Readings</label>
                                    <input type="number" v-model="form.odometer_readings" step="0.001"
                                           class="form-control" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Litres</label>
                                    <input type="number" step="0.001" class="form-control" v-model="form.litres"
                                           required>
                                </div>
                                <div class="form-group">
                                    <label>Fuel Type</label>
                                    <select name="fuel_type_id" v-model="form.fuel_type_id" class="form-control" @change="fuelRate()" disabled>
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
                                       <label>Category</label>
                                       <select v-model="form.fuel_category_id" class="form-control" disabled>
                                          <option value="stock_issue">Stock Issue</option>
                                          <option value="invoice">Invoice to Customer</option>
                                       </select>
                                   </div>

                                   <fieldset class="the-fieldset" v-if="show_issue">
                               <legend class="the-legend"><label class="fyr">Where To Charge</label></legend>
                                  <div>
                               <div class="form-group">
                                   <label>Credit Account</label>
                                      <model-select :options="accounts"
                            v-model="form.where_to_charge"
                            placeholder="Where to charge">
                            </model-select>
                               </div>

                         <div class="form-group">
                            <label>Debit Account</label>

                             <input type="text" class="form-control" v-model="form.account" disabled>
                        </div>
                       </div>
                   </fieldset>

                               <div v-if="show_inv">
                                    <div class="form-group">

                                    <label>Customer Type</label>
                                    <select class="form-control" v-model="form.customer_type_id" required @change="customerTypes()" disabled>
                                        <option :value="type.id" v-for="type in customer_types" :key="type.id">{{type.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="show_customer">

                                    <label>Customer</label>
                                      <model-select :options="filtered_customers"
                                        v-model="form.customer_id"
                                        :is-disabled="true">
                                        </model-select>
                                </div>
                               </div>
                                <div class="form-group">
                                    <label>Authorized By</label>
                                    <input type="text" class="form-control" v-model="username" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Requested By</label>
                                    <input type="text" class="form-control" v-model="form.requested_by" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Store Man</label>
                                    <input type="text" class="form-control" v-model="form.store_man" disabled>
                              </div>
                            </div>
                            </div>
                        <button type="submit" class="btn btn-info" :disabled="reverse_text"> <i class="fa fa-undo" aria-hidden="true"></i>{{reverse_text ? 'Please Wait' :'Reverse'}} </button>
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
        props: ['edit', 'other_fuel', 'add_fuel','reverse'],
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
                    fuel_category_id:'',
                    status:1,
                    rate:0,
                    credit_account_id:'',
                    where_to_charge:'',
                     account:'',
                    id: ''
                },
                edit_fuel: this.edit,
                reverse_text:false,
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
                stks:{},
                fuel_categories:{},
                accounts:[],
                show_issue:false,
                show_inv:false,


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
            this.creditAccount();
            this.getFuelCategories();
            this.getAccounts();


        },
       watch:{
        'form.fuel_category_id'(){
            if (this.form.fuel_category_id=='stock_issue') {
            this.show_issue = true;
            this.show_inv = false;
         }
         if (this.form.fuel_category_id=='invoice') {
            this.show_issue = false;
            this.show_inv = true;
         }
        },
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
             checkCatgory(){
           return this.form.fuel_category_id =='stock_issue';
            },
          fuel_category(){
          return this.form.fuel_category_id;
        },
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
            creditAccount(){
            axios.get('where-to-charge')
            .then(res => {
                if (res.data.length) {
                 let account = res.data.find(req => req.type =='Fueling');
                 this.form.account = account.account;
                 this.form.credit_account_id  = account.account_id;
                }

            })
            },
          getAccounts(){
         axios.get('accounts')
         .then(res => {
            console.log(this.form.credit_account_id)
            let accounts = res.data.filter(acc => acc.account_link !==this.form.credit_account_id)
            console.log(accounts.length);
            console.log(res.data.length);
            accounts.forEach(a => {
                this.accounts.push({
                    'value': a.account_link,
                    'text': a.account
                })
            })
         })
        }  ,
           getFuelCategories(){
          axios.get('fuel-category')
          .then(res => {
            this.fuel_categories = res.data;
          })
            },
            resetCustomerType(){
            this.form.customer_type_id = '';
            },
            resetCustomer(){
            this.form.customer_id = '';
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
                  this.fuels = res.data.fuels;
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
                       this.stks = res.data.filter(p => p.code == 'LA0012' || p.code == 'LA0018');
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
            //return console.log(this.form);
            this.update();
            },
            update() {
                this.reverse_text = true;
                axios.post(`reverse-fuel`, {'reversal_litres':this.form.litres,'id':this.form.id,'reversal_rate':this.form.rate,'where_to_charge':this.form.where_to_charge,'credit_account_id':this.form.credit_account_id})
                    .then(res => {
                      eventBus.$emit('cancel');
                    })
                    .catch(error => error.response)
            },
            cancel() {
                eventBus.$emit('cancel');
            },
            listen() {
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
                    this.getCustomers();
                    this.getVehicles();
                    this.subProject();
                   },4000)

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
