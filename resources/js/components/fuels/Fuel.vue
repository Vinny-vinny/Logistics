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
                                <div class="form-group" v-if="company">
                                    <label>Vehicle</label>
                                    <select name="vehicle_id" class="form-control" v-model="form.vehicle_id"
                                            @change="getFuelType()">
                                        <option :value="vehicle.id" v-for="vehicle in vehicles" :key="vehicle.id">
                                          {{vehicle.code}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="other">
                                    <label>Vehicle</label>
                                    <input type="text" v-model="form.vehicle_name" class="form-control" required>
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
                                <div class="form-group" v-if="other">
                                    <label>Fuel Type</label>
                                    <select name="fuel_type_id" v-model="form.fuel_type_id" class="form-control">
                                        <option :value="fuel.id" v-for="fuel in fuel_types" :key="fuel.id">
                                            {{fuel.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="radio-inline"><input type="radio" name="supplier_type"
                                                                       value="supplier"
                                                                       v-model="form.supplier_type">Supplier</label>
                                    <label class="radio-inline"><input type="radio" name="supplier_type"
                                                                       value="cash_purchase" v-model="form.supplier_type">Cash
                                        Purchase</label>
                                </div>
                            </div>
                            <div class="col-md-6">

                                <div class="form-group" style="margin-left:100px;">
                                    <table width="100%">
                                        <tr>
                                            <td style="font-size:18px"><b>Fuel Type: </b>{{fuel_type}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:18px"><b>Previous Odometer Readings: </b>{{previous_odometer | number}}
                                            </td>
                                          </tr>
                                        <tr>
                                            <td style="font-size:18px"><b>Fuel Rate: </b>{{form.rate | number}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:18px"><b>Total Fuel Cost: </b>{{totalAmount | number}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:18px"><b>Total Expense: </b>{{(totalExpense + genExpenses()) | number}}</td>
                                        </tr>
                                        <tr>
                                            <td style="font-size:18px"><b>Grand Total: </b>{{grandTotal}}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="form-group">
                                    <label>Expenses</label>
                                    <select name="expense_id" class="form-control" v-model="form.expense_id" @change="genExpenses()">
                                        <option :value="expense.id" v-for="expense in expenses" :key="expense.id">
                                            {{expense.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="company">
                                    <label>Driver</label>
                                    <select name="driver_id" class="form-control" v-model="form.driver_id">
                                        <option :value="driver.id" v-for="driver in drivers" :key="driver.id">
                                            {{driver.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="other">
                                    <label>Driver</label>
                                    <input type="text" class="form-control" v-model="form.driver_name">
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="supplier">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Supplier</label>
                                            <select name="supplier_id" class="form-control" v-model="form.supplier_id"
                                                    @change="getRate()">
                                                <option :value="supplier.id" v-for="supplier in suppliers"
                                                        :key="supplier.id">
                                                    {{supplier.name}}
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Invoice No</label>
                                            <input type="text" class="form-control" v-model="form.invoice_no">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row" v-if="cashPurchase">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Supplier</label>
                                            <input type="text" class="form-control" v-model="form.supplier_name">
                                        </div>
                                        <div class="form-group">
                                            <label>Currency</label>
                                            <select name="currency" class="form-control" v-model="form.currency">
                                                <option value="USD">USD</option>
                                                <option value="KSH">KSH</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Rate</label>
                                            <input type="number" step="0.001" v-model="form.rate" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label>Cash Sale No</label>
                                            <input type="text" class="form-control" v-model="form.cash_sale_no">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">

                                <div class="form-group">
                                    <label>Misc expenses</label>
                                    <table style="width:100%">
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(expense,k) in form.other_expenses" :key="k">
                                            <td><select class="form-control pt" v-model="expense.name">
                                                <option selected disabled>Select Part</option>
                                                <option :value="part.id" v-for="part in parts" :key="part.id">
                                                    {{part.code}} - {{part.description}}
                                                </option>
                                            </select></td>
                                            <td><input type="number" v-model="expense.qty" class="form-control qt"
                                                       placeholder="Qty"></td>
                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeExpense(k)"
                                                   v-show="k || ( !k && form.other_expenses.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addExpense(k)"
                                                   v-show="k === form.other_expenses.length-1"></i>
                                            </td>
                                        </tr>
                                    </table>
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
                    supplier_id: '',
                    invoice_no: '',
                    expense_id: '',
                    fuel_type_id: '',
                    vehicle_name: '',
                    driver_name: '',
                    driver_id: '',
                    currency: '',
                    cash_sale_no: '',
                    odometer_readings: '',
                    rate:0,
                    supplier_name: '',
                    other_expenses: [{name: '', qty: ''}],
                    supplier_type: 'supplier',
                    id: ''
                },
                edit_fuel: this.edit,
                other_fuel_asset: this.other_fuel,
                suppliers: {},
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
                pa:{}
            }
        },
        created() {
            this.listen();
            this.getSuppliers();
            this.getVehicles();
            this.getDrivers();
            this.getExpenses();
            this.getFuelTypes();
            this.assetType();
            this.getParts();

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
                let total = 0;
                if (this.form.supplier_type ==='supplier') {
                    if (this.form.rate !== 0 && this.form.litres !== '') {
                        total += parseFloat(this.form.rate) * parseFloat(this.form.litres);
                    }
                }
                else {
                    if (this.form.rate !=='' && this.form.litres !==''){
                        total+= parseFloat(this.form.rate) * parseFloat(this.form.litres);
                    }
                }
                return this.total = total;
            },
            totalExpense(){
                let total=0;
                if (Object.values(this.form.other_expenses[0])[0] !== '' || Object.values(this.form.other_expenses[0])[1] !== '') {
                    for (let i = 0; i < this.form.other_expenses.length; i++) {
                         if (this.form.other_expenses[i]['name'] !== '' && this.form.other_expenses[i]['qty'] !== '') {
                             for (let j =0;j<this.parts.length;j++){
                                 if (this.parts[j]['id'] === this.form.other_expenses[i]['name']){
                                   total += parseInt(this.form.other_expenses[i]['qty']) * parseFloat(this.parts[j]['cost']);
                                 }
                             }
                         }
                    }
                 }
                return total;
            },
            supplier() {
                return this.form.supplier_type === 'supplier';
            },
            cashPurchase() {
                return this.form.supplier_type === 'cash_purchase';
            },
            grandTotal(){
                let total = 0;
                if (this.totalAmount > 0 || this.totalExpense > 0 || this.genExpenses() > 0){
                    total += parseFloat(this.totalAmount) + parseFloat(this.totalExpense) + parseFloat(this.genExpenses());
                }
                return total;
            }
        },
        methods: {
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
            addExpense() {
                this.form.other_expenses.push({name: '', qty: ''});
            },
            removeExpense(i) {
                this.form.other_expenses.splice(i, 1);
            },
            getFuelType() {
                this.vehicles.forEach(vehicle => {
                    if (vehicle.id === this.form.vehicle_id) {
                        this.fuel_types.forEach(fuel => {
                            if (fuel.id === vehicle.fuel_type_id) {
                                this.fuel_type = fuel.name;
                                this.form.fuel_type_id = fuel.id;
                                this.show_fuel_type = true;
                                this.previous_odometer = vehicle.odometer_readings;
                            }
                        })
                    }
                })
            },
            getRate() {
                this.suppliers.forEach(supplier => {
                    if (supplier.id === this.form.supplier_id) {
                        this.form.rate = supplier.price;
                        this.show_rate = true;
                        return;
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
            getSuppliers() {
                axios.get('fuel-supplier')
                    .then(supplier => {
                        this.suppliers = supplier.data;
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
                if (Object.values(this.form.other_expenses[0])[0] !== '' || Object.values(this.form.other_expenses[0])[1] !== '') {
                    for (let i = 0; i < this.form.other_expenses.length; i++) {
                        if (this.form.other_expenses[i]['name'] === '' || this.form.other_expenses[i]['qty'] === '') {
                            return this.$toastr.e('Please all expenses fields are required.');
                        }
                    }
                }
                if (this.form.vehicle_id === '' && this.form.vehicle_name === '') {
                    return this.$toastr.e('Please Select a Vehicle.');
                }
                for (let i = 0; i < this.vehicles.length; i++) {
                    if (this.vehicles[i]['id'] === this.form.vehicle_id) {
                        if (this.vehicles[i]['odometer_readings'] > this.form.odometer_readings) {
                            return false;
                        }
                    }
                }
                if (this.form.driver_name === '' && this.form.driver_id === '') {
                    return this.$toastr.e('Please select a Driver');
                }
                if (this.form.supplier_type ==='cash_purchase'){
                    if (this.form.rate ==='' || this.form.currency ==='' || this.form.supplier_name ==='' || this.form.cash_sale_no ===''){
                        return this.$toastr.e('All fields for Cash Sale are required');
                    }
                } else if(this.form.supplier_type ==='supplier'){
                    if(this.form.supplier_id ==='' || this.form.invoice_no ===''){
                        return  this.$toastr.e('Supplier and Invoice no fields are required.');
                    }
                }
                if (this.other && this.form.fuel_type_id ===''){
                    return this.$toastr.e('Select Fuel Type.');
                }
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
                eventBus.$emit('cancel')
            },
            listen() {
                if (this.edit) {
                    this.form = this.$store.state.fuels;
                    this.form.other_expenses = JSON.parse(this.$store.state.fuels.other_expenses);
                    this.show_fuel_type = true;
                    this.fuel_type = this.$store.state.fuels.fuel_type;
                    this.form.rate = this.$store.state.fuels.price;
                    this.previous_odometer = this.$store.state.fuels.previous_odometer;
                    this.show_rate = true;

                }
            },
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
