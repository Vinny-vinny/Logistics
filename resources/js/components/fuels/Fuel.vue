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
                                    <label>Vehicle</label>
                                    <select name="vehicle_id" class="form-control" v-model="form.vehicle_id"
                                            @change="getFuelType()" required>
                                        <option :value="vehicle.id" v-for="vehicle in vehicles" :key="vehicle.id">
                                          {{vehicle.code}}
                                        </option>
                                    </select>
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
                                    <select name="fuel_type_id" v-model="form.fuel_type_id" class="form-control" required>
                                        <option :value="fuel.id" v-for="fuel in fuel_types" :key="fuel.id">
                                            {{fuel.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
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

                                <div class="form-group" style="margin-left:100px;">
                                    <table width="100%">
                                        <tr>
                                            <td style="font-size:16px"><b>Fuel Type: </b>{{fuel_type}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:16px"><b>Previous Odometer Readings: </b>{{previous_odometer | number}}
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

                                <div class="form-group">
                                    <label>Customer Type</label>
                                    <select class="form-control" v-model="form.customer_type_id" required @change="customerTypes()">
                                        <option :value="type.id" v-for="type in customer_types" :key="type.id">{{type.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="show_customer">
                                    <label>Customers</label>
                                    <select class="form-control" v-model="form.customer_id" required>
                                        <option :value="customer.id" v-for="customer in filtered_customers" :key="customer.id">{{customer.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Authorized By</label>
                                    <input type="text" class="form-control" :value="username()" disabled>
                                </div>
                                <div class="form-group">
                                    <label>Invoice No</label>
                                    <input type="text" class="form-control" v-model="form.invoice_no">
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
    export default {
        props: ['edit', 'other_fuel', 'add_fuel'],
        data() {
            return {
                form: {
                    litres: '',
                    vehicle_id: '',
                    fuel_on: '',
                    customer_id: '',
                    invoice_no: '',
                    expense_id: '',
                    fuel_type_id: '',
                    authorized_by: '',
                    requested_by:'',
                    odometer_readings: '',
                    customer_type_id:'',
                    job_card_id:'',
                    asset_type:'',
                    store_man:'',
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
                previous_odometer: 0,
                parts: {},
                total_expenses:0,
                pa:{},
                filtered_customers:[],
                customer_types:{},
                jobcards:{},
                show_customer:false,
                fuels:{},
                jobs:{}
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

        },
       watch:{
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
           username(){
             return  User.name();
           },
            getRate(){
           for (let i=0; i < this.fuel_types.length; i++){
               if (this.fuel_types[i]['id'] === this.form.fuel_type_id){
                  this.form.rate = this.fuel_types[i]['rate'];
               }
           }
            },
            customerTypes(){
                this.filtered_customers = [];
                    this.show_customer = true;
                    console.log(this.form.customer_type_id)
                    for (let i = 0; i < this.customers.length; i++) {
                        if (this.customers[i]['customer_type_id'] === this.form.customer_type_id) {
                            this.filtered_customers.push(this.customers[i]);
                        }
                    }

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
                        this.fuel_types.forEach(fuel => {
                            if (fuel.id === vehicle.fuel_type_id) {
                                this.fuel_type = fuel.name;
                                this.form.rate = fuel.rate
                                this.form.fuel_type_id = fuel.id;
                                this.show_fuel_type = true;
                                this.previous_odometer = vehicle.odometer_readings;
                            }
                        })
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
                if (this.form.odometer_readings < this.previous_odometer){
                    return this.$toastr.e('Current Odometer Readings cannot be less than Previous Readings.');
                }
                this.other_fuel_asset ? this.form.asset_type = 'other' : this.form.asset_type = 'company';

              this.edit_fuel ? this.update() : this.save();
            },
            save() {
                delete this.form.id;
                this.form.fuel_on = this.convertDate(this.form.fuel_on);
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
                    this.previous_odometer = this.$store.state.fuels.previous_odometer;
                    this.show_rate = true;
                    this.show_customer = true;
                    this.editedCustomers();
                    this.form.asset_type ==='other' ? this.other =true : this.company = true;


                }
            },
            editedCustomers(){
                axios.get('customers')
                    .then(customers => {
                        this.filtered_customers = [];
                        this.show_customer = true
                        for (let i =0; i < customers.data.length; i++){
                            if (customers.data[i]['customer_type_id'] === this.form.customer_type_id){
                                this.filtered_customers.push(customers.data[i]);
                            }
                        }
                    })
            }
        },
        components: {
            datepicker
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
