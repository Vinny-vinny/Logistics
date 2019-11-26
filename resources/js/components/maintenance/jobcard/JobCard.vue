<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_jobcard ? 'Update Jobcard' : 'New Jobcard'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveJobcard()" enctype="multipart/form-data" id="jobcard">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="radio-inline"><input type="radio" name="service_type" value="internal"
                                                                       v-model="form.service_type">Internal</label>
                                    <label class="radio-inline"><input type="radio" name="service_type"
                                                                       value="external" v-model="form.service_type">External</label>
                                </div>
                                <div class="form-group">
                                    <label>Vehicle/Chasis #</label>
                                    <select name="machine_id" class="form-control" v-model="form.machine_id"
                                            @change="getAssetDetails()" required>
                                        <option :value="machine.id" v-for="machine in machines" :key="machine.id">
                                            {{machine.code}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Track By:</label> {{track_name}}

                                </div>
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control" required v-model="form.jobcard_category_id">
                                        <option :value="category.id" v-for="category in job_categories" :key="category.id">{{category.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Job Type</label>
                                    <select class="form-control" required v-model="form.job_type_id">
                                        <option :value="job_type.id" v-for="job_type in job_types" :key="job_type.id">{{job_type.name}} - {{job_type.currency}} {{job_type.hourly_rate}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Customer Type</label>
                                    <select class="form-control" v-model="customer_type" required @change="customerType()">
                                        <option value="Internal">Internal</option>
                                        <option value="External">External</option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="show_customers">
                                    <label>Customer</label>
                                    <select class="form-control" required v-model="form.customer_id">
                                        <option :value="customer.id" v-for="customer in filtered_customers" :key="customer.id">{{customer.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Mechanic</label>
                                    <select class="form-control" required v-model="form.mechanic_id">
                                        <option :value="mechanic.id" v-for="mechanic in mechanics" :key="mechanic.id">{{mechanic.name}}</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Service Type</label>
                                    <select name="service_type_id" class="form-control" v-model="form.service_type_id"
                                            @change="nextReadings()" required>
                                        <option :value="service.id" v-for="service in service_types" :key="service.id">
                                            {{service.name}}
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label>Servicing Date</label>
                                    <datepicker v-model="form.actual_date" required></datepicker>

                                </div>
                                <div class="form-group">
                                    <label>Servicing Time In</label>
                                    <input type="text" v-model="form.time_in" class="form-control time_in" required>

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
                                                required></datepicker>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Make & Model: </label> {{make}}

                                </div>
                                <div class="form-group">
                                    <label>Assigned To: </label> {{driver}}
                                </div>
                                <div class="form-group">
                                    <label>Fuel Balance</label>
                                    <select name="fuel_balance_id" class="form-control" v-model="form.fuel_balance_id"
                                            required>
                                        <option :value="bal.id" v-for="bal in balances" :key="bal.id">{{bal.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="show_track_by">
                                    <label class="pull-left">Previous {{track_name}} Reading:
                                        {{previous_readings}}</label>
                                    <br><br>
                                    <label>Current {{track_name}} Readings</label>

                                    <input type="number" step="0.001" class="form-control"
                                           v-model="form.current_readings" required>
                                    <br>
                                    <label>Actual {{track_name}} Covered: {{actualMileage ? actualMileage : 0}}</label>
                                </div>
                                <div class="form-group" v-if="show_track_by">
                                    <label>Next {{track_name}} Maintenance</label>
                                    <input type="number" step="0.001" class="form-control" v-model="form.next_readings"
                                           required :disabled="!show_next_readings">
                                </div>
                                <div class="form-group" v-if="serviceType">
                                    <label>Department</label>
                                    <select class="form-control" v-model="form.project_id">
                                        <option :value="project.id" v-for="project in projects" :key="project.id">{{project.code}} - {{project.name}}</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Cost Code</label>
                                    <input type="text" class="form-control" v-model="form.cost_code" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Expenses</label>
                                    <table style="width: 100%">
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(item,k) in form.item_cost_qty" :key="k">
                                            <td><select class="form-control i_p" v-model="item.part" @change="exp_id =item.part">
                                                <option selected disabled>Select Part</option>
                                                <option :value="part.id" v-for="part in parts" :key="part.id">
                                                    {{part.code}} - {{part.description}}
                                                </option>
                                            </select>
                                            </td>
                                            <td><input type="number" class="form-control qty" v-model="item.quantity"
                                                       placeholder="Qty"  @keyup="exp_qty = item.quantity">
                                            </td>
                                            <td><input type="number" class="form-control p_in" step="0.001" v-model="item.price_inclusive"
                                                       placeholder="Price Inclusive VAT" disabled></td>
                                            <td><input type="number" step="0.001" class="form-control p_ex" v-model="item.price_exclusive"
                                                       placeholder="Price Exclusive VAT" disabled></td>
                                            <td>
                                                <i class="fa fa-minus-circle remove" @click="removeparts(k)"
                                                   v-show="k || ( !k && form.item_cost_qty.length > 1)"></i>
                                                <i class="fa fa-plus-circle add" @click="addparts(k)"
                                                   v-show="k == form.item_cost_qty.length-1"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Maintenance</label>
                                    <table style="width:100%">
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(m,i) in form.maintenance">
                                            <td><select class="form-control i_p" v-model="m.category"
                                                        placeholder="Category">
                                                <option selected disabled>Select Category</option>
                                                <option :value="cat.id" v-for="cat in categories" :key="cat.id">
                                                    {{cat.name}}
                                                </option>

                                            </select></td>

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
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Repair / Service Required</label>
                                    <table style="width:100%">
                                        <tr>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>
                                            <th></th>

                                        </tr>
                                        <tr v-for="(s,i) in form.service_required">
                                            <td><select class="form-control i_p" v-model="s.category"
                                                        placeholder="Category">
                                                <option selected disabled>Select Category</option>
                                                <option :value="cat.id" v-for="cat in categories" :key="cat.id">
                                                    {{cat.name}}
                                                </option>
                                            </select></td>
                                            <td><select class="form-control s_part" v-model="s.part" @change="part_id =s.part">
                                                <option selected disabled>Select Part</option>
                                                <option :value="part.id" v-for="part in parts" :key="part.id">
                                                    {{part.code}} - {{part.description}}
                                                </option>
                                            </select>
                                            </td>
                                            <td><input type="number" class="form-control s_qty" v-model="s.qty"
                                                       placeholder="Quantity" @keyup="part_qty = s.qty"></td>
                                            <td><input type="number" class="form-control p_in_2" step="0.001" v-model="s.price_inclusive"
                                                       placeholder="Price Inclusive VAT" disabled></td>
                                            <td><input type="number" step="0.001" class="form-control p_ex_2" v-model="s.price_exclusive"
                                                       placeholder="Price Exclusive VAT" disabled></td>
                                            <td><input type="text" class="form-control s_descr" v-model="s.description"
                                                       placeholder="Description"></td>
                                            <td>
                                                <i class="fa fa-minus-circle s_add" @click="removeService(i)"
                                                   v-show="i || (!i && form.service_required.length > 1)"></i>
                                                <i class="fa fa-plus-circle s_add" @click="addService(i)"
                                                   v-show="i == form.service_required.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary" v-if="status ==1">{{edit_jobcard ? 'Update' :
                            'Save'}}
                        </button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
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

    export default {
        props: ['edit'],
        data() {
            return {
                form: {
                    machine_id: '',
                    track_by_id: '',
                    service_type_id: '',
                    jobcard_category_id:'',
                    mechanic_id:'',
                    actual_date: '',
                    completion_date: '',
                    time_in: '',
                    time_out: '',
                    fuel_balance_id: '',
                    next_readings: 0,
                    next_service_date: '',
                    current_readings: '',
                    service_type: 'internal',
                    project_id:'',
                    job_type_id:'',
                    cost_code:'',
                    customer_id:'',
                    id: '',
                    item_cost_qty: [{part: '', quantity: '',price_inclusive:'',price_exclusive:''}],
                    maintenance: [{category: '', description: '', root_cause: ''}],
                    service_required: [{category: '', part: '', qty: '', description: '',price_inclusive:'',price_exclusive:''}]

                },
                checklist: '',
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
                categories: {},
                status: 1,
                projects:{},
                job_categories:{},
                mechanics:{},
                job_types:{},
                customer_type:'',
                customers:{},
                filtered_customers:[],
                show_customers:false,
                part_qty:'',
                part_id:'',
                exp_qty:'',
                exp_id:''
            }
        },
        watch: {
            computeExpenses(){
                for (let p=0; p < this.parts.length; p++){
                    for (let i=0; i < this.form.item_cost_qty.length; i++){
                        if (this.form.item_cost_qty[i]['quantity'] !=='' && this.form.item_cost_qty[i]['part'] !==''){
                            if (this.form.item_cost_qty[i]['part'] === this.parts[p]['id']){
                                this.form.item_cost_qty[i]['price_inclusive'] = ((this.parts[p]['cost'] * 116/100) * this.form.item_cost_qty[i]['quantity']).toFixed(2);
                                this.form.item_cost_qty[i]['price_exclusive'] = (this.parts[p]['cost'] * this.form.item_cost_qty[i]['quantity']).toFixed(2);
                            }
                        }
                    }
                }
            },
            getCost(){
                for (let p=0; p < this.parts.length; p++){
                    for (let i=0; i < this.form.service_required.length; i++){
                        if (this.form.service_required[i]['qty'] !=='' && this.form.service_required[i]['part'] !==''){
                            if (this.form.service_required[i]['part'] === this.parts[p]['id']){
                                this.form.service_required[i]['price_inclusive'] = ((this.parts[p]['cost'] * 116/100) * this.form.service_required[i]['qty']).toFixed(2);
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
                if (moment(this.form.next_service_date).format('YYYY-MM-DD') < moment(Date.now()).format('YYYY-MM-DD')) {
                    this.$refs.nextServiceDate.clearDate();
                    this.form.next_service_date = '';
                    return this.$toastr.e('Sorry, next service date cannot be later than today.');
                }
                if (this.form.actual_date !== '' && this.form.completion_date !== '' && this.form.time_in !== '' || this.form.time_out !== '') {
                    if (moment(this.form.actual_date).format('YYYY-MM-DD') === moment(this.form.completion_date).format('YYYY-MM-DD')) {
                        if (moment(this.form.time_in, 'h:mm A').format('HH:mm') > moment(this.form.time_out, 'h:mm A').format('HH:mm')) {
                            this.form.time_out = '';
                            return this.$toastr.e('Sorry, time in cannot be greater than time out.');
                        }
                    }
                }
            }
        },
        mounted() {
            this.initDate();
        },
        created() {
            this.listen();
            this.getMachines();
            this.getUsers();
            this.getTracks();
             this.getServiceTypes();
            this.getBalances();
            this.getParts();
            this.getCategories();
            this.getProjects();
            this.getJobCategories();
            this.getMechanics();
            this.getJobtypes();
            this.getCustomers();

        },
        filters: {
            moment: function (date) {
                return moment(date);
            }
        },
        computed: {
            partItems(){
                return this.service_required;
            },
            actualMileage() {
                if (this.form.current_readings > this.previous_readings) {
                    return this.form.current_readings - this.previous_readings;
                }
            },
            serviceType() {
                return this.form.service_type === 'internal';
            },
            timeframe() {
                return [this.form.actual_date, this.form.time_in, this.form.completion_date, this.form.time_out, this.form.next_service_date].join();
            },
            getCost(){
               return [this.part_qty, this.part_id,this.form.service_required].join();
            },
            computeExpenses(){
                return [this.exp_qty, this.exp_id,this.form.item_cost_qty].join();
            }

        },
        methods: {

            customerType(){
                this.show_customers = true;
                this.filtered_customers = [];
              if (this.customer_type ==='Internal'){
                  for (let i=0; i<this.customers.length; i++){
                      if (this.customers[i]['type'] === 'Internal'){
                          this.filtered_customers.push(this.customers[i]);
                      }
                  }
              }
              else if (this.customer_type === 'External'){
                  for (let j=0; j<this.customers.length; j++){
                      if (this.customers[j]['type'] === 'External'){
                          this.filtered_customers.push(this.customers[j]);
                      }
                  }
              }
            },
            getCustomers(){
              axios.get('customers')
                  .then(customers => {
                      this.customers = customers.data;
                  })
            },
            getJobtypes(){
              axios.get('job-types')
                  .then(res => {
                      this.job_types = res.data;
                  })
            },
            getMechanics(){
              axios.get('mechanics')
                  .then(res => {
                      this.mechanics = res.data;
                  })
            },
            getJobCategories(){
              axios.get('jobcard-category')
                  .then(category => {
                      this.job_categories = category.data;
                  })
            },
            getProjects(){
              axios.get('projects')
                  .then(res => {
                      this.projects = res.data
                  })
            },
            addService(i) {
                this.form.service_required.push({category: '', part: '', qty: '', description: '',price_inclusive:'',price_exclusive:''});
            },
            removeService(i) {
                this.form.service_required.splice(i, 1);
            },
            close() {
                if (confirm('Do you really want to close?')) {
                    axios.post(`close-jobcard/${this.form.id}`)
                        .then(res => {
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
            getCategories() {
                axios.get('categories')
                    .then(category => {
                        this.categories = category.data
                    })
            },
            getParts() {
                axios.get('parts')
                    .then(res => {
                        this.parts = res.data
                    })
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
            addparts() {
                this.form.item_cost_qty.push({part: '', quantity: '',price_inclusive:'',price_exclusive:''});
            },
            removeparts(i) {
                this.form.item_cost_qty.splice(i, 1);
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
                for (let i=0;i<this.machines.length;i++){
                    if (this.machines[i]['id'] === this.form.machine_id){
                        console.log(`current ${this.machines[i]['current_readings']}  entered  ${this.form.current_readings}`)
                        if (this.machines[i]['current_readings'] > this.form.current_readings){
                            return this.$toastr.e(`Sorry, Current ${this.track_name} readings cannot be less than previous readings.`);
                        }
                    }
                }
                if (parseFloat(this.form.current_readings) > parseFloat(this.form.next_readings)) {
                    return this.$toastr.e(`Sorry, Current ${this.track_name} readings cannot be greater than next readings.`);
                }
                if (Object.values(this.form.item_cost_qty[0])[0] !== '' || Object.values(this.form.item_cost_qty[0])[1] !== '') {
                    for (let i = 0; i < this.form.item_cost_qty.length; i++) {
                        if (this.form.item_cost_qty[i]['part'] === '' || this.form.item_cost_qty[i]['quantity'] === '') {
                            return this.$toastr.e('Please all expenses fields are required.');
                        }
                    }
                }
                if (Object.values(this.form.maintenance[0])[0] !== '' || Object.values(this.form.maintenance[0])[1] !== '' || Object.values(this.form.maintenance[0])[2] !== '') {
                    for (let i = 0; i < this.form.maintenance.length; i++) {
                        if (this.form.maintenance[i]['category'] === '' || this.form.maintenance[i]['description'] === '' || this.form.maintenance[i]['root_cause'] === '') {
                            return this.$toastr.e('Please all Maintenance fields are required.');
                        }
                    }
                }
                if (Object.values(this.form.service_required[0])[0] !== '' || Object.values(this.form.service_required[0])[1] !== '' || Object.values(this.form.service_required[0])[2] !== '' || Object.values(this.form.service_required[0])[3] !== '') {
                    for (let i = 0; i < this.form.service_required.length; i++) {
                        if (this.form.service_required[i]['category'] === '' || this.form.service_required[i]['part'] === '' || this.form.service_required[i]['qty'] === '' || this.form.service_required[i]['description'] === '') {
                            return this.$toastr.e('Please all Repair/Service required fields are required.');
                        }
                    }
                }


                if (this.form.next_service_date === '') {
                    return this.$toastr.e('Next service date is required.');
                }

                this.edit_jobcard ? this.update() : this.save();

            },
            save() {
                this.saveJob();
            },
            saveJob() {
                delete this.form.id;
                this.form.completion_date !== '' ? this.form.completion_date = this.convertDate(this.form.completion_date) : ''
                this.form.next_service_date = this.convertDate(this.form.next_service_date);
                this.form.actual_date = this.convertDate(this.form.actual_date);
                this.form.item_cost_qty = JSON.stringify(this.form.item_cost_qty);
                this.form.maintenance = JSON.stringify(this.form.maintenance);
                this.form.service_required = JSON.stringify(this.form.service_required);

                axios.post('job-card', this.form).then(res => {
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
                this.form.next_service_date = this.convertDate(this.form.next_service_date);
                this.form.actual_date = this.convertDate(this.form.actual_date);
                this.form.completion_date !== null && this.form.completion_date !== '' ? this.form.completion_date = this.convertDate(this.form.completion_date) : '';
                this.form.item_cost_qty = JSON.stringify(this.form.item_cost_qty);
                this.form.maintenance = JSON.stringify(this.form.maintenance);
                this.form.service_required = JSON.stringify(this.form.service_required);
                axios.patch(`job-card/${this.form.id}`, this.form).then(res => {
                    this.$toastr.s('Jobcard updated Successfully.');
                    eventBus.$emit('updateJobcard', res.data)
                })
                    .catch(error => error.response)
            },
            getMachines() {
                axios.get('machines')
                    .then(machines => {
                        this.machines = machines.data;
                    })
            },
            getTracks() {
                axios.get('track-by')
                    .then(tracks => {
                        this.tracks = tracks.data;
                    })
            },

            getServiceTypes() {
                axios.get('service-types')
                    .then(service_types => {
                        this.services = service_types.data;
                    })
            },
            getBalances() {
                axios.get('fuel-balance')
                    .then(balance => {
                        this.balances = balance.data;
                    })
            },
            getUsers() {
                axios.get('users')
                    .then(users => {
                        this.users = users.data;
                    })
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
                    this.form.item_cost_qty = JSON.parse(this.$store.state.job_card.item_cost_qty);
                    this.form.maintenance = JSON.parse(this.$store.state.job_card.maintenance);
                    this.form.service_required = JSON.parse(this.$store.state.job_card.service_required);

                    console.log(this.$store.state.job_card);
                    this.status = this.$store.state.job_card.status;
                    this.customer_type = this.$store.state.job_card.customer_type;
                    this.show_customers = true;
                    this.filtered_customers = [];
                    this.Customers();
                    this.ServiceTypes();
                }
            },
            ServiceTypes(){
                axios.get('service-types')
                    .then(res => {
                        for (let i=0;i<res.data.length;i++){
                            if (res.data[i]['id'] === this.form.service_type_id){
                                this.service_after = res.data[i]['service_after'];
                                this.form.next_readings = parseFloat(this.form.current_readings) + parseFloat(this.service_after);
                            }
                        }
                    });
            },
            Customers(){
                axios.get('customers')
                    .then(res => {
                        this.customers =res.data;
                        if (this.customer_type ==='Internal'){
                            for (let i=0; i<this.customers.length; i++){
                                if (this.customers[i]['type'] === 'Internal'){
                                    this.filtered_customers.push(this.customers[i]);
                                }
                            }
                        }
                        else if (this.customer_type === 'External'){
                            for (let j=0; j<this.customers.length; j++){
                                if (this.customers[j]['type'] === 'External'){
                                    this.filtered_customers.push(this.customers[j]);
                                }
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

<style>
     .i_p{
         margin-bottom: 8px;
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
    .p_in{
     margin-left:10px;
     margin-bottom: 8px;

    }
    .p_ex{
        margin-left:15px;
        margin-bottom: 8px;
    }
    .p_in_2{
        margin-left:8px;
        margin-bottom: 8px;
    }
    .p_ex_2{
        margin-left:10px;
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


    .cool{
        border: #339FFF solid 2px;
        padding: 15px;
    }

</style>
