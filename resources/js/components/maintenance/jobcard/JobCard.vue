<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_jobcard ?'Update Jobcard: #'+form.card_no: 'New Jobcard'}}</h3>
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
                                <div class="form-group" v-if="show_rqs">
                                    <label>Requisitions</label>
                                      <span class="reset_btn pull-right" @click="resetReqs">reset</span>
                                    <select class="form-control" v-model="form.requisition_id" @change="getDetails()"
                                            :disabled="disable_rq">
                                        <option :value="rq.id" v-for="rq in rqs" :key="rq.id">
                                            {{rq.req_no}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="show_inventory && filtered_rq=='Internal'">
                                 <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Inventory Items</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th align="right">Part</th>
                                            <th align="right">Quantity</th>
                                            <th align="right">Unit Cost</th>
                                            <th align="right">Total Cost</th>
                                            <th align="right">Total Cost Inclusive VAT</th>

                                        </tr>
                                        <tr v-for="(m,i) in filtered_items_internal">
                                            <td>
                                            <model-select :options="stk_items"
                                            v-model="m.part"
                                            class="i_p"
                                            >
                                            </model-select>
                                            </td>
                                            <td><input type="number" class="form-control cost" v-model="m.quantity"
                                                       placeholder="Quantity" disabled></td>
                                            <td>
                                                <input type="number" class="form-control rq_pi"
                                                       v-model="m.unit_cost"
                                                       placeholder="Unit Cost" disabled></td>

                                            <td>
                                                <input type="number" class="form-control rq_pi"
                                                       v-model="m.total_cost"
                                                       placeholder="Total Cost" disabled></td>
                                            <td>
                                                <input type="number" class="form-control rq_pe"
                                                       v-model="m.total_cost_inclusive"
                                                       placeholder="Total Cost Inclusive VAT" disabled></td>


                                        </tr>
                                    </table>
                                </fieldset>
                                </div>
                                <div class="form-group" v-if="show_inventory && filtered_rq=='External'">
                                     <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Inventory Items</label></legend>
                                    <table style="width:100%">
                                        <tr>
                                            <th align="right">Part</th>
                                            <th align="right">Quantity</th>
                                            <th align="right">Unit Price</th>
                                            <th align="right">Total Price</th>
                                            <th align="right">Total Price Inclusive VAT</th>

                                        </tr>
                                        <tr v-for="(m,i) in filtered_items_external">
                                            <td>
                                            <model-select :options="stk_items"
                                            v-model="m.part"
                                            class="i_p"
                                            >
                                            </model-select>

                                            </td>
                                            <td><input type="number" class="form-control cost" v-model="m.quantity"
                                                       placeholder="Quantity" disabled></td>
                                            <td>
                                                <input type="number" class="form-control rq_pi"
                                                       v-model="m.unit_price"
                                                       placeholder="Unit Price" disabled></td>

                                            <td>
                                                <input type="number" class="form-control rq_pi"
                                                       v-model="m.total_price"
                                                       placeholder="Total Price" disabled></td>
                                            <td>
                                                <input type="number" class="form-control rq_pe"
                                                       v-model="m.total_price_inclusive"
                                                       placeholder="Total Price Inclusive VAT" disabled></td>


                                        </tr>
                                    </table>
                                </fieldset>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" v-if="status ==1">{{edit_jobcard ? 'Update' :
                            'Save'}}
                        </button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                        <button type="button" class="btn btn-info" @click="sendInvoice()" v-if="edit && form.requisition_id && form.invoiced==0" :disabled="invoice_text">{{invoice_text ? 'Please Wait' :'Send Invoice'}}</button>
                        <button type="button" class="btn btn-warning" @click="close" v-if="edit_jobcard && status==1">
                            Close Jobcard
                        </button>
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
        props: ['edit'],
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
                },
                checklist: '',
                invoice_text:false,
                edit_jobcard: this.edit,
                machines: {},
                tracks: {},
                service_types: {},
                services: {},
                balances: {},
                internal: false,
                external: false,
                track_name: '',
                show_track_by: false,
                make: '',
                driver: '',
                users: {},
                previous_readings: 0,
                actual_readings: 0,
                service_after: 0,
                next_computed_readings: 0,
                show_next_readings: true,
                parts: {},
                categories: [],
                status: 1,
                projects: [],
                job_categories: {},
                mechanics: [],
                job_types: {},
                customer_type: '',
                customers: {},
                filtered_customers: [],
                show_customers: false,
                part_qty: '',
                part_id: '',
                exp_qty: '',
                exp_id: '',
                requisitions: {},
                show_inventory: false,
                filtered_items_internal: [],
                filtered_items_external: [],
                disable_rq: false,
                filtered_rq: '',
                customer_types: {},
                rqs:[],
                subprojects:[],
                transactions:{},
                stk_items:[],
                show_rqs:false

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
                           // this.form.labour_cost =  total;


                    }

                }
            }
        },
        created() {
                this.getAllDetails();
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
            sendInvoice(){
             this.invoice_text = true;
            axios.post(`invoice-job-card`,this.form)
            .then(res => {
                console.log(res.data)
              eventBus.$emit('cancel')
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
            this.subprojects = [];
            let subp = this.machines.filter(vehicle => vehicle.asset_category_id == this.form.asset_category_id);
                subp.forEach(p => {
                this.subprojects.push({
                    'value': p.project_link,
                    'text': p.description
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
            getDetails() {
                this.show_inventory = true;
                this.filtered_items_external = [];
                   this.filtered_rq ='External';
                   this.filtered_items_external = this.rqs[0]['inventory_items_external'];


            },
            getAllDetails(){
                this.customers = this.$store.state.all_my_customers;
                this.requisitions = this.$store.state.all_my_reqs;
                this.parts = this.$store.state.all_my_parts;
                this.machines = this.$store.state.all_my_vehicles;
                this.job_categories = this.$store.state.all_my_job_categories;
                this.customer_types = this.$store.state.all_my_customer_types;
                this.services = this.$store.state.all_my_service_types;
                this.tracks = this.$store.state.all_my_tracks;
                this.users = this.$store.state.all_my_users;
                this.job_types = this.$store.state.all_my_job_types;
            },
            getParts() {
                        this.parts.forEach(p => {
                            this.stk_items.push({
                                'value': p.id,
                                'text': p.code +'-'+p.description
                            })
                        })
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
            getCategories() {
                this.$store.state.all_my_categories.forEach(c => {
                    this.categories.push({
                        'value': c.id,
                        'text': c.name
                    })
                })
            },
            getMechanics() {
                this.$store.state.all_my_mechanics.forEach(m => {
                    this.mechanics.push({
                        'value': m.id,
                        'text': m.name
                    })
                })
            },
            getProjects() {
                this.$store.state.all_my_projects.forEach(p => {
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
                            this.$toastr.s(`Jobcard was successfully closed.`);
                            setTimeout(()=>{
                             eventBus.$emit('cancel');
                            },1000)

                        })
                }
            },
            addItem(i) {
                this.form.maintenance.push({category: '', description: '', root_cause: ''});
            },
            removeItem(i) {
                this.form.maintenance.splice(i, 1);
            },

            nextReadings() {
                this.services.forEach(service => {
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
                        let machine = this.machines.find(m => m.project_link ===this.form.machine_id);
                        this.make = machine.make;
                        this.previous_readings = machine.current_readings;
                        this.form.track_by_id = machine.track_by_id;
                        this.track_name  = this.tracks.find(t => t.id === machine.track_by_id).name;
                        this.show_track_by = true;
                        this.driver = this.users.find(u => u.id === machine.assign_to).name;
                        this.service_types = machine.service_types;
            },
            saveJobcard() {
                for (let i = 0; i < this.machines.length; i++) {
                    if (this.machines[i]['id'] === this.form.machine_id) {
                        console.log(`current ${this.machines[i]['current_readings']}  entered  ${this.form.current_readings}`)
                        if (this.machines[i]['current_readings'] > this.form.current_readings) {
                            return this.$toastr.e(`Sorry, Current ${this.track_name} readings cannot be less than previous readings.`);
                        }
                    }
                }
                if (parseFloat(this.form.current_readings) > parseFloat(this.form.next_readings)) {
                    return this.$toastr.e(`Sorry, Current ${this.track_name} readings cannot be greater than next readings.`);
                }

                if (Object.values(this.form.maintenance[0])[0] !== '' || Object.values(this.form.maintenance[0])[1] !== '' || Object.values(this.form.maintenance[0])[2] !== '') {
                    for (let i = 0; i < this.form.maintenance.length; i++) {
                        if (this.form.maintenance[i]['category'] === '' || this.form.maintenance[i]['description'] === '' || this.form.maintenance[i]['root_cause'] === '') {
                            return this.$toastr.e('Please all Maintenance fields are required.');
                        }
                    }
                }


                if (this.form.service_type =='External' && this.form.customer_id =='') {
                    return this.$toastr.e('Please Customer field is mandatory.');
                }
               if (this.form.service_type =='Internal' && this.form.asset_category_id =='') {
                return this.$toastr.e('Please select project first.');
               }

                this.edit_jobcard ? this.update() : this.save();

            },
            save() {
                this.saveJob();
            },
            saveJob() {
                delete this.form.id;
               this.form.completion_date !== '' ? this.form.completion_date = this.convertDate(this.form.completion_date) : ''
                this.form.next_service_date !== '' ? this.form.next_service_date = this.convertDate(this.form.next_service_date) : ''
                this.form.actual_date !== '' ? this.form.actual_date = this.convertDate(this.form.actual_date) : ''
                axios.post('job-card', this.form).then(res => {
                     console.log('cooll')
                    this.$toastr.s('Jobcard created Successfully.');
                    eventBus.$emit('listJobcards', res.data)
                })
                    .catch(error => error.response)
            },
            convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            update() {
                this.form.next_service_date !== null && this.form.next_service_date !== '' ? this.form.next_service_date = this.convertDate(this.form.next_service_date) : '';
                  this.form.actual_date !== null && this.form.actual_date !== '' ? this.form.actual_date = this.convertDate(this.form.actual_date) : '';
                    this.form.completion_date !== null && this.form.completion_date !== '' ? this.form.completion_date = this.convertDate(this.form.completion_date) : '';

                axios.patch(`job-card/${this.form.id}`, this.form).then(res => {
                    this.$toastr.s('Jobcard updated Successfully.');
                    eventBus.$emit('updateJobcard', res.data)
                   // console.log(res.data)
                })
                    .catch(error => error.response)
            },
            cancel() {
                eventBus.$emit('cancel')
            },
            listen() {
                if (this.edit) {
                    this.form = this.$store.state.job_card;
                    this.form.service_type = this.$store.state.job_card.service_type;
                    this.show_track_by = true;
                    this.track_name = this.$store.state.job_card.track_name;
                    this.previous_readings = this.$store.state.job_card.previous_readings;
                    this.make = this.$store.state.job_card.make;
                    this.driver = this.$store.state.job_card.driver;
                    this.service_types = this.form.service_types;
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

                }
            },

              editedRequisitions() {
               this.rqs = [];
               let req = this.requisitions.find(rq => rq.id ==this.form.requisition_id);
                 this.filtered_rq ='External';
                 this.filtered_items_external =req.inventory_items_external;
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
