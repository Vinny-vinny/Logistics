<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reversal Ref# {{form.reversal_ref}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveJobcard()" enctype="multipart/form-data" id="jobcard">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="radio-inline"><input type="radio" name="service_type" value="Internal"
                                                                       v-model="form.service_type">Internal</label>
                                    <label class="radio-inline"><input type="radio" name="service_type"
                                                                       value="External" v-model="form.service_type">External</label>
                                </div>
                                 <div class="form-group">
                                     <span class="reset_btn pull-right" @click="resetCustomerType">reset</span>
                                    <label>Customer Type</label>
                                    <select class="form-control" v-model="form.customer_type_id"
                                            @change="customerType()">
                                        <option :value="type.id" v-for="type in customer_types" :key="type.id">
                                            {{type.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="show_customers">
                                     <span class="reset_btn pull-right" @click="resetCustomer">reset</span>
                                    <label>Customer</label>
                                    <model-select :options="filtered_customers"
                                        v-model="form.customer_id"
                                        @input="getJobDetails()"
                                        >
                                        </model-select>
                                </div>
                                    <div class="form-group">
                                    <label>Project</label>
                                    <model-select :options="projects"
                                        v-model="form.asset_category_id"
                                        @input="subProject()"
                                        required>
                                        </model-select>
                                </div>
                                <div class="form-group">
                                    <span class="reset_btn pull-right" @click="resetVehicle">reset</span>
                                    <label>Vehicle</label>
                                    <model-select :options="subprojects"
                                        v-model="form.machine_id"
                                        @input="getAssetDetails()"
                                        >
                                        </model-select>
                                </div>
                                <div class="form-group" v-if="this.form.machine_id">
                                    <label>Track By:</label> {{track_name}}
                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control" required v-model="form.jobcard_category_id">
                                        <option :value="category.id" v-for="category in job_categories"
                                                :key="category.id">{{category.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="this.form.machine_id">
                                    <label>Job Type</label>
                                    <select class="form-control"  v-model="form.job_type_id">
                                        <option :value="job_type.id" v-for="job_type in job_types" :key="job_type.id">
                                            {{job_type.name}} - {{job_type.currency}} {{job_type.hourly_rate}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Mechanic</label>
                                        <model-select :options="mechanics"
                                        v-model="form.mechanic_id"
                                        >
                                        </model-select>
                                </div>
                                <div class="form-group">
                                    <label>Hours Spent</label>
                                    <input type="number" class="form-control" step="0.001" v-model="form.hours_spent">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Type</label>
                                    <select name="service_type_id" class="form-control" v-model="form.service_type_id"
                                            @change="nextReadings()">
                                        <option :value="service.id" v-for="service in service_types" :key="service.id">
                                            {{service.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cost Code</label>
                                    <input type="text" class="form-control" v-model="form.cost_code">
                                </div>
                                <div class="form-group">
                                    <label>Servicing Date</label>
                                    <datepicker v-model="form.actual_date"></datepicker>

                                </div>
                                <div class="form-group">
                                    <label>Servicing Time In</label>
                                    <input type="text" v-model="form.time_in" class="form-control time_in">

                                </div>
                                <div class="form-group">
                                    <label>Servicing Completion Date</label>
                                    <datepicker v-model="form.completion_date" ref="completionDate"
                                    ></datepicker>
                                </div>
                                <div class="form-group">
                                    <label>Servicing Time Out</label>
                                    <input type="text" v-model="form.time_out" class="form-control time_out"
                                           ref="timeOut"
                                    >
                                </div>
                                <div class="form-group">
                                    <label>Next Service Date</label>
                                    <datepicker v-model="form.next_service_date" ref="nextServiceDate"
                                    ></datepicker>
                                </div>
                            </div>
                            <div class="col-md-4" v-if="this.form.machine_id">
                                <div class="form-group">
                                    <label>Make & Model: </label> {{make}}
                                </div>
                                <div class="form-group">
                                    <label>Assigned To: </label> {{driver}}
                                </div>

                                <div class="form-group" v-if="show_track_by">
                                    <label class="pull-left">Previous {{track_name}} Reading:
                                        {{previous_readings}}</label>
                                    <br><br>
                                    <label>Current {{track_name}} Readings</label>

                                    <input type="number" step="0.001" class="form-control"
                                           v-model="form.current_readings">
                                    <br>
                                    <label>Actual {{track_name}} Covered: {{actualMileage ? actualMileage : 0}}</label>
                                </div>
                                <div class="form-group" v-if="show_track_by">
                                    <label>Next {{track_name}} Maintenance</label>
                                    <input type="number" step="0.001" class="form-control" v-model="form.next_readings"
                                            :disabled="!show_next_readings">
                                </div>

                                <div class="form-group" v-if="this.form.machine_id">
                                    <label>Lobour Cost</label>
                                    <input type="text" class="form-control" v-model="form.labour_cost" disabled>
                                </div>
                                 <div class="form-group">
                                <label>Standing fee charged</label>
                                <input type="number" step="0.001" v-model="form.standing_fee" class="form-control" required>
                            </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                     <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Maintenance</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th>Category</th>
                                            <th>Root Cause</th>
                                            <th>Description</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(m,i) in form.maintenance">
                                             <td>
                                             <model-select :options="categories"
                                            v-model="m.category"
                                            class="i_p_2"
                                            >
                                            </model-select>
                                            </td>

                                            <td><input type="text" class="form-control cost" v-model="m.description"
                                                       placeholder="Description"></td>
                                            <td><input type="text" class="form-control cause" v-model="m.root_cause"
                                                       placeholder="Root cause"></td>
                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeItem(i)"
                                                   v-show="i || (!i && form.maintenance.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addItem(i)"
                                                   v-show="i == form.maintenance.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </fieldset>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-11">
                                     <div class="form-group">
                                     <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Inventory Items</label></legend>
                                   <table style="width: 100%">
                                <tr>
                                    <th>Item</th>
                                    <th>Uom</th>
                                    <th>Qty</th>
                                    <th>Avail. Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Total Price(with VAT)</th>

                                </tr>
                                <tr v-for="(item,k) in form.inventory_items_reversal" :key="k">
                                    <td>
                                         <model-select :options="items"
                                        v-model="item.part"
                                        @input="part =item.part"
                                        class="i_p"
                                        required>
                                        </model-select>
                                    </td>
                                        <td>
                                      <input type="text" class="form-control qty" v-model="item.uom">
                                    </td>
                                    <td><input type="number" class="form-control qty" v-model="item.quantity"
                                               placeholder="Qty"  @keyup="qty = item.quantity">
                                    </td>
                                    <td><input type="text" class="form-control qty" v-model="item.qty_available"
                                               disabled>
                                    </td>
                                    <td><input type="number" class="form-control p_in" step="0.001" v-model="item.unit_price"
                                               placeholder="Unit Price" @keyup="unit_price = costing(item.part)"></td>
                                    <td><input type="number" class="form-control p_in_2" step="0.001" v-model="item.total_price"
                                               placeholder="Total Price" disabled></td>
                                    <td><input type="number" class="form-control p_ex_2" step="0.001" v-model="item.total_price_inclusive"
                                               placeholder="Total Price Inclusive VAT" disabled></td>
                                 </tr>
                            </table>
                                </fieldset>
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
    import {mapGetters} from "vuex";
    export default {
        props: ['reverse'],
        data() {
            return {
                form: {
                    machine_id: '',
                    track_by_id: '',
                    service_type_id: '',
                    jobcard_category_id: '',
                    mechanic_id: '',
                    actual_date: '',
                    completion_date: '',
                    time_in: '',
                    time_out: '',
                    next_readings: 0,
                    next_service_date: '',
                    current_readings: '',
                    customer_type_id:'',
                    standing_fee:1000,
                    service_type: 'Internal',
                    asset_category_id: '',
                    job_type_id: '',
                    cost_code: '',
                    customer_id: '',
                    requisition_id: '',
                    labour_cost: 0,
                    hours_spent:'',
                    id: '',
                    maintenance: [{category: '', description: '', root_cause: ''}],
                      inventory_items_external: [{part: '', uom:'',quantity: '',unit_price:'',total_price:'',total_price_inclusive:'',qty_available:''}],

                },
                checklist: '',
                inventory_items_reversal:[],
                edit_jobcard: this.edit,
                balances: {},
                internal: false,
                external: false,
                track_name: '',
                show_track_by: false,
                make: '',
                driver: '',
                previous_readings: 0,
                actual_readings: 0,
                service_after: 0,
                next_computed_readings: 0,
                show_next_readings: true,
                categories: [],
                status: 1,
                projects: [],
                mechanics: [],
                customer_type: '',
                filtered_customers: [],
                show_customers: false,
                part_qty: '',
                part_id: '',
                exp_qty: '',
                exp_id: '',
                show_inventory: false,
                filtered_items_internal: [],
                filtered_items_external: [],
                disable_rq: false,
                filtered_rq: '',
                rqs:[],
                subprojects:[],
                transactions:{},
                stk_items:[],
                show_rqs:false,
                items:[]
            }
        },
        watch: {
            labours(){
            if (this.form.hours_spent > 0 && this.form.job_type_id) {
            let total =0;
            if (this.form.job_type_id) {
              let cost = this.job_types.find(type => type.id == this.form.job_type_id).hourly_rate;
             total += this.form.hours_spent * cost;
            }
           this.form.labour_cost =  total.toFixed(2);

            }
            },
            getCost() {
                for (let p = 0; p < this.parts.length; p++) {
                    for (let i = 0; i < this.form.service_required.length; i++) {
                        if (this.form.service_required[i]['qty'] !== '' && this.form.service_required[i]['part'] !== '') {
                            if (this.form.service_required[i]['part'] === this.parts[p]['id']) {
                                this.form.service_required[i]['price_inclusive'] = ((this.parts[p]['cost'] * 116 / 100) * this.form.service_required[i]['qty']).toFixed(2);
                                this.form.service_required[i]['price_exclusive'] = (this.parts[p]['cost'] * this.form.service_required[i]['qty']).toFixed(2);
                            }
                        }
                    }
                }
            },
            'form.current_readings'() {
                this.form.next_readings = parseFloat(this.form.current_readings) + parseFloat(this.service_after);
                this.show_next_readings = false;
            },
            timeframe() {
                if (this.form.actual_date !== '' && this.form.completion_date !== '') {
                    if (moment(this.form.actual_date).format('YYYY-MM-DD') > moment(this.form.completion_date).format('YYYY-MM-DD')) {
                        this.$refs.completionDate.clearDate();
                        this.form.completion_date = '';
                        return this.$toastr.e('Sorry, actual date cannot be greater than completion date.');
                    }
                }
                if (this.form.next_service_date !== '' && this.form.completion_date !== '' && this.form.completion_date !== null) {
                    if (moment(this.form.next_service_date).format('YYYY-MM-DD') < moment(this.form.completion_date).format('YYYY-MM-DD')) {
                        this.$refs.nextServiceDate.clearDate();
                        this.form.next_service_date = '';
                        return this.$toastr.e('Sorry, Next service date cannot be less than completion date.');
                    }
                }
                if (this.form.actual_date !== '' && this.form.completion_date !== '' && this.form.time_in !== '' && this.form.time_out !== '') {
                    if (moment(this.form.actual_date).format('YYYY-MM-DD') === moment(this.form.completion_date).format('YYYY-MM-DD')) {
                        if (moment(this.form.time_in, 'h:mm A').format('HH:mm') > moment(this.form.time_out, 'h:mm A').format('HH:mm')) {
                            this.form.time_out = '';
                            return this.$toastr.e('Sorry, time in cannot be greater than time out.');
                        }
                    }
                    //Labour calculation = hrs* job type
                    let initialdate = moment(this.form.actual_date).format('YYYY-MM-DD');
                    let enddate = moment(this.form.completion_date).format('YYYY-MM-DD');
                    let start_time = moment(this.form.time_in, "HH:mm:ss").format("hh:mm")
                    let end_time = moment(this.form.time_out, "HH:mm:ss").format("hh:mm");

                    let datetimeA = moment(initialdate + " " + start_time);
                    let datetimeB = moment(enddate + " " + end_time);
                    let time_in_minutes = datetimeB.diff(datetimeA, 'minutes');

                    if (this.form.job_type_id !== '') {
                    let total =0;
                    if (this.form.job_type_id) {
                      let cost = this.job_types.find(type => type.id == this.form.job_type_id).hourly_rate;
                     total += time_in_minutes / 60 * cost;
                    }
                    this.form.labour_cost =  total;

                    }

                }
            }
        },
        created() {
            this.getCategories();
            this.getMechanics();
            this.getProjects();
            this.getParts();
            this.filteredRqs();
            this.listen();
        },
        filters: {
            moment: function (date) {
                return moment(date);
            }
        },

        computed: {
            ...mapGetters({
                customers:'all_customers',
                requisitions:'all_reqs',
                parts:'all_parts',
                machines:'all_vehicles',
                job_categories:'all_job_categories',
                all_categories:'all_categories',
                customer_types:'all_customer_types',
                service_types:'all_service_types',
                tracks:'all_tracks',
                users:'all_users',
                job_types:'all_job_types',
                all_projects:'all_projects',
                all_mechanics:'all_mechanics'
            }),
            labours(){
            return [this.form.hours_spent,this.form.job_type_id].join();
            },
            partItems() {
                return this.service_required;
            },
            actualMileage() {
                if (this.form.current_readings > this.previous_readings) {
                    return this.form.current_readings - this.previous_readings;
                }
            },
            serviceType() {
                return this.form.service_type === 'Internal';
            },
            timeframe() {
                return [this.form.actual_date, this.form.time_in, this.form.completion_date, this.form.time_out, this.form.next_service_date, this.form.job_type_id].join();
            },
            getCost() {
                return [this.part_qty, this.part_id, this.form.service_required].join();
            },

        },
        methods: {
              selectedGroup(){
                let items=[];
                 items = this.parts.filter(item => item.item_group == this.form.group_name);
                      this.items = [];
                if (Object.values(this.form.inventory_items_external[0])[0] !== '') {
                     for (let i = 0; i < this.form.inventory_items_external.length; i++) {
                        this.parts.forEach(p => {
                            if (p.id == this.form.inventory_items_external[i]['part']) {
                                items.push(p);
                            }
                        })

                    }
                }

            items.forEach(p => {
                this.items.push({
                    'value': p.id,
                    'text': p.item_group +'-'+p.description
                })
            })

            },
               getParts(){
                    this.parts.forEach( p =>{
                        this.items.push({
                            'value': p.id,
                            'text': p.item_group +'-'+p.description
                        })
                    })

            },

            getJobDetails(){
              this.show_rqs = false;
             if(this.form.customer_id){
                this.form.asset_category_id='';
               let req_details = this.requisitions.find(req => req.customer_id ==this.form.customer_id);
               if(req_details) {
                this.form.asset_category_id = req_details.project_id;
                this.form.machine_id = req_details.subproject_id;

                this.subProject();
                this.getAssetDetails();
                this.rqs = this.requisitions.filter(req => req.customer_id ==this.form.customer_id)
                this.show_rqs = true;
               }

             }
            },
            resetReqs(){
            this.form.requisition_id = '';
            },
            resetCustomerType(){
            this.form.customer_type_id = '';
            },
            resetCustomer(){
            this.form.customer_id = '';
            },
            resetCostCode(){
            this.form.cost_code = '';
            },
            resetVehicle(){
            this.form.machine_id = '';
            },
             subProject(){
            let subp = this.machines.filter(vehicle => vehicle.asset_category_id == this.form.asset_category_id);
            this .subprojects = [];
             subp.forEach(p => {
                this.subprojects.push({
                    'value': p.project_link,
                    'text': p.code
                })
             })
            },

            filteredRqs() {
                for (let i = 0; i < this.requisitions.length; i++) {
                    if (this.requisitions[i]['used'] == 0 || this.requisitions[i]['used'] == null && this.requisitions[i]['type'] !==null) {
                        this.rqs.push(this.requisitions[i]);
                    }
                }

            },
         customerType() {
                this.filtered_customers = [];
                this.show_customers = true;
                if (this.form.customer_type_id) {
                let customers = this.customers.filter(c => c.customer_type_id == this.form.customer_type_id);
                 customers.forEach(cus => {
                    this.filtered_customers.push({
                        'value': cus.id,
                        'text': cus.name
                    })
                 });
                }
            },
            getMechanics() {
                this.all_mechanics.forEach(m => {
                    this.mechanics.push({
                        'value': m.id,
                        'text': m.name
                    })
                })
            },
            getProjects() {
                this.all_projects.forEach(p => {
                    this.projects.push({
                        'value': p.project_link,
                        'text': p.name
                    })
                })
            },
            addService(i) {
                this.form.service_required.push({
                    category: '',
                    part: '',
                    qty: '',
                    description: '',
                    price_inclusive: '',
                    price_exclusive: ''
                });
            },
            removeService(i) {
                this.form.service_required.splice(i, 1);
            },
            close() {
                if (confirm('Do you really want to close?')) {
                    axios.post(`close-jobcard/${this.form.id}`)
                        .then(res => {
                          //  console.log(res.data);
                            this.$toastr.s(`Jobcard ${this.$store.state.job_card.card_no} was successfully closed.`)
                            eventBus.$emit('cancel');
                        })
                }
            },
            addItem(i) {
                this.form.maintenance.push({category: '', description: '', root_cause: ''});
            },
            removeItem(i) {
                this.form.maintenance.splice(i, 1);
            },
             removeItemIn(i) {
                this.inventory_items_internal.splice(i, 1);
            },
             removeItemEx(i) {
                this.inventory_items_external.splice(i, 1);
            },
            getCategories() {
                this.all_categories.forEach(c => {
                    this.categories.push({
                        'value': c.id,
                        'text': c.name
                    })
                })
            },
              nextReadings() {
                this.service_types.forEach(service => {
                    if (service.id === this.form.service_type_id) {
                        this.service_after = service.service_after;
                    }
                })

            },
            initDate() {
                var vm = this;
                $('.time_in').timepicker({'scrollDefault': 'now'}).on('changeTime', function () {
                    vm.form.time_in = $('.time_in').val();
                })
                $('.time_out').timepicker({'scrollDefault': 'now'}).on('changeTime', function () {
                    vm.form.time_out = $('.time_out').val();
                })
            },

            getAssetDetails() {
              this.machines.forEach(machine => {
                    if (machine.id === this.form.machine_id) {
                        this.make = machine.make;
                        this.previous_readings = machine.current_readings;
                        this.form.track_by_id = machine.track_by_id;
                        this.tracks.forEach(track => {
                            if (track.id === machine.track_by_id) {
                                this.track_name = track.name;
                                this.show_track_by = true;
                            }
                        });
                        this.users.forEach(user => {
                            if (user.id === machine.assign_to) {
                                this.driver = user.name;
                                return;
                            }
                        })
                        this.service_types = machine.service_types
                    }
                    return;
                })
            },
            saveJobcard() {
                this.inventory_items_reversal = [];
                if (Object.values(this.inventory_items_external[0])[0] !== '' || Object.values(this.inventory_items_external[0])[2] !== '' || Object.values(this.inventory_items_external[0])[3] !== '') {
                    for (let i = 0; i < this.inventory_items_external.length; i++) {
                        if (this.inventory_items_external[i]['quantity'] === '') {
                            return this.$toastr.e('Inventory Item Quantity fields are required.');
                        }

                          this.inventory_items_reversal.push({
                             'part':this.inventory_items_external[i]['part'],
                             'uom': this.inventory_items_external[i]['uom'],
                             'quantity': this.inventory_items_external[i]['quantity'],
                             'unit_price': this.inventory_items_external[i]['unit_price'],
                             'total_price': this.inventory_items_external[i]['total_price'],
                             'total_price_inclusive': this.inventory_items_external[i]['total_price_inclusive'],
                             'qty_available': this.inventory_items_external[i]['qty_available'],
                            });
                    }

                }
                 this.inventory_items_reversal = this.inventory_items_reversal.filter(req => req.part !==null)
//              return console.log(this.inventory_items_reversal);

                this.update();

            },
            convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            update() {
                axios.post(`reverse-job-card`, {'inventory_items_reversal':this.inventory_items_reversal,'id':this.form.id}).then(res => {
                 console.log(res.data);
                    // this.$toastr.s('Jobcard updated Successfully.');
                    // eventBus.$emit('updateJobcard', res.data)
                })
                    .catch(error => error.response)
            },
            cancel() {
                eventBus.$emit('cancel')
            },
            listen() {
                    this.form = this.$store.state.job_card;
                    this.form.service_type = this.$store.state.job_card.service_type;
                    this.show_track_by = true;
                    this.track_name = this.$store.state.job_card.track_name;
                    this.previous_readings = this.$store.state.job_card.previous_readings;
                    this.make = this.$store.state.job_card.make;
                    this.driver = this.$store.state.job_card.driver;
                   // this.service_types = this.form.service_types;

                    this.status = this.$store.state.job_card.status;
                    this.show_customers = true;

                this.ServiceTypes();
                this.subProject();
                this.customerType();

                if (this.form.requisition_id) {
                    this.disable_rq = true;
                    this.show_inventory = true;
                    this.editedRequisitions();
                }

            },
            editedRequisitions() {
                this.rqs = [];
                let req = this.requisitions.find(rq => rq.id ==this.form.requisition_id);
                this.filtered_rq ='External';
                this.inventory_items_external = req.inventory_items_external;
            },
            ServiceTypes() {
                for (let i = 0; i < this.service_types.length; i++) {
                    if (this.service_types[i]['id'] === this.form.service_type_id) {
                        this.service_after = this.service_types[i]['service_after'];
                        this.form.next_readings = parseFloat(this.form.current_readings) + parseFloat(this.service_after);
                    }
                }
            },

        },
        components: {
            datepicker,
            ModelSelect
        }
    }
</script>

<style>
    .i_p {
        margin-bottom: 8px;
    }
    .i_p_2{
       margin-bottom: 8px;
       width: 300px !important;
    }
    .qty, .cost {
        margin-left: 5px;
        margin-bottom: 8px;
    }

    .cost {
        margin-left: 8px;
        margin-bottom: 8px;
    }

    .remove, .add {
        margin-left: 20px;
        margin-bottom: 8px;
    }

    .cause {
        margin-left: 15px;
        margin-bottom: 8px;
    }

    .s_add {
        margin-left: 15px;
        margin-bottom: 8px;
    }

    .s_descr {
        margin-left: 12px;
        margin-bottom: 8px;
    }

    .s_qty {
        margin-left: 5px;
        margin-bottom: 8px;
    }

    .s_part {
        margin-left: 2px;
        margin-bottom: 8px;
    }

    .p_in {
        margin-left: 10px;
        margin-bottom: 8px;

    }

    .p_ex {
        margin-left: 15px;
        margin-bottom: 8px;
    }

    .p_in_2 {
        margin-left: 8px;
        margin-bottom: 8px;
    }

    .p_ex_2 {
        margin-left: 10px;
        margin-bottom: 8px;
    }

    .rq_pi {
        margin-left: 15px;
        margin-bottom: 8px;
    }

    .rq_pe {
        margin-left: 20px;
        margin-bottom: 8px;
    }


    .vdp-datepicker input {
        border-radius: 0;
        box-shadow: none;
        border-color: #d2d6de;
        display: block;
        width: 100%;
        height: 34px;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        color: #555;
        background-color: #fff;
        background-image: none;
        border: 1px solid #ccc;
        border-radius: 4px;
        -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075);
        -webkit-transition: border-color ease-in-out .15s, -webkit-box-shadow ease-in-out .15s;
        -o-transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
        transition: border-color ease-in-out .15s, box-shadow ease-in-out .15s;
    }


    .cool {
        border: #339FFF solid 2px;
        padding: 15px;
    }
    .the-legend {
    border-style: none;
    border-width: 0;
    font-size: 14px;
    line-height: 20px;
    margin-bottom: 0;
    width: auto;
    padding: 0 10px;
    border: 1px solid #e0e0e0;
}
.the-fieldset {
    border: 1px solid #e0e0e0;
    padding: 10px;
}
.fyr{
    font-weight:800
}
.fy{
    display:flex;
}
.bf{
    width:100%
}

</style>
