<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Fuel Reversal Ref# {{form.reversal_ref}}</h3>
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
                                    <input type="number" step="0.001" class="form-control" v-model="form.reversal_litres"
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
                                    <input type="text" class="form-control" v-model="form.reversal_rate">
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
                                            <td style="font-size:16px"><b>Fuel Rate: </b>{{form.reversal_rate | number}}</td>
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
                                   <model-select :options="accountsd"
                                                 v-model="form.credit_account_id"
                                                 :is-disabled="true"
                                                 required>
                                   </model-select>

                                     </div>

                                      <div class="form-group">
                                          <label>Debit Account</label>
                                          <model-select :options="accounts"
                                                        v-model="form.where_to_charge"
                                                        placeholder="Where to charge"
                                                        :is-disabled="true">
                                          </model-select>
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
                    reversal_litres: '',
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
                    reversal_rate:0,
                    credit_account_id:'',
                    where_to_charge:'',
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
                stks:{},
                accounts:[],
                accountsd:[],
                show_issue:false,
                show_inv:false,
                account:'',
                all_accounts:{}

            }
        },
        created() {
            this.getAllDetails();
            this.listen();
            this.assetType();
            this.getParts();
            this.getProjects();
            this.getAccounts();
            this.getAccountsDebit();
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
             if(this.form.reversal_litres < 0 || (isNaN(parseFloat(this.form.reversal_litres)) && !isFinite(this.form.reversal_litres))){
             this.form.reversal_litres = 1;
            }
        },
         'form.vehicle_id'(){
          this.jobs = this.jobcards.filter(f => f.machine_id ==this.form.vehicle_id);

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
            return [this.form.odometer_readings,this.form.reversal_litres].join();
            },
            totalAmount() {
                let total = this.form.reversal_rate * this.form.reversal_litres;
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
            getAccountsDebit(){
                /// let accounts = this.all_accounts.filter(acc => acc.account_link !==this.form.where_to_charge)
                this.all_accounts.forEach(a => {
                    this.accountsd.push({
                        'value': a.account_link,
                        'text': a.account
                    })
                })
            },
            getAllDetails(){
                this.fuels = this.$store.state.all_my_fuels;
                this.customers = this.$store.state.all_my_customers;
                this.customer_types = this.$store.state.all_my_customer_types;
                this.jobcards = this.$store.state.all_my_jobcards;
                this.parts = this.$store.state.all_my_parts;
                this.drivers  = this.$store.state.all_my_users;
                this.vehicles = this.$store.state.all_my_vehicles;
                this.all_accounts =  this.$store.state.all_my_accounts;
                this.all_projects = this.$store.state.all_my_projects;
                this.expenses = this.$store.state.all_my_expenses;
            },

          getAccounts(){
            let accounts = this.all_accounts.filter(acc => acc.account_link !==this.form.credit_account_id)
            accounts.forEach(a => {
                this.accounts.push({
                    'value': a.id,
                    'text': a.account
                })
            })

        }  ,
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
                    'value': p.project_link,
                    'text': p.code
                })
             })
            },
            getProjects(){
                this.all_projects.forEach(p => {
                    this.projects.push({
                        'value': p.project_link,
                        'text': p.name
                    })
                })
            },
            fuelRate(){
                let item =this.stks.find(s => s.id == this.form.fuel_type_id);
                this.form.reversal_rate = item.cost;
                this.fuel_type = item.description;

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
              this.stks = this.parts.filter(p => p.code == 'LA0012' || p.code == 'LA0018');
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
            convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            saveFuel() {
            this.update();
            },
            update() {
                axios.post(`reverse-fuel`, {'reversal_litres':this.form.reversal_litres,'id':this.form.id})
                    .then(res => {
                        console.log(res.data);
                        // this.edit_fuel = false;
                        // eventBus.$emit('updateFuel', res.data);
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
                    this.form.reversal_rate = this.$store.state.fuels.reversal_rate;
                    this.form.previous_odometer = this.$store.state.fuels.previous_odometer;
                    this.show_rate = true;
                    this.show_customer = true;
                    this.username = this.drivers.find(user => user.id === this.form.authorized_by).name;
                    this.form.asset_type ==='other' ? this.other =true : this.company = true;
                    this.subProject();
                    this.customerTypes();
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
