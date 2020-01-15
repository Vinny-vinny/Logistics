<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_machine ? 'Update Machine' : 'New Machine'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveMachine()" id="asset">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Code</label>
                                    <input type="text" class="form-control" v-model="form.code" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <input type="text" class="form-control" v-model="form.description" required>
                                </div>
                                <div class="form-group">
                                    <label>Track By</label>
                                    <select name="track_by_id" id="track_by_id" v-model="form.track_by_id"
                                            class="form-control" @change="trackValue()" required>
                                        <option :value="track.id" v-for="track in tracks" :key="track.id">
                                            {{track.name}}
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="show_service">
                                    <label>Service Type</label>
                                    <multiselect
                                        v-model="value"
                                        :options="service_types"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabel"
                                        placeholder="Select services"
                                        @input="updateServices"
                                    >
                                    </multiselect>
                                    </div>
                                <div class="form-group">
                                    <label>License Plate</label>
                                    <input type="text" class="form-control" v-model="form.plate_no" required>
                                </div>
                                <div class="form-group">
                                    <label>Insurance Policy</label>
                                    <select name="status" v-model="form.status" class="form-control" required
                                            @change="enableUpload()">
                                        <option value="0">Not Insured</option>
                                        <option value="1">Insured</option>
                                    </select>
                                </div>
                                <div class="form-group" v-if="show_file">
                                    <label>Insurance scanned copy</label>
                                    <input type="file" class="form-control" @change="onFileChange">
                                </div>
                                <div class="row" v-if="show_file">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <datepicker v-model="form.start_date"></datepicker>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Expiry Date</label>
                                            <datepicker v-model="form.expiry_date" ref="myDatepicker"></datepicker>
                                        </div>
                                    </div>
                                </div>

                                        <div class="form-group" v-if="selected_next_maintenance && show_next_readings">
                                            <label>Next {{track_type}} due for service</label>
                                            <input type="number" step="0.001" class="form-control" v-model="form.next_readings" disabled>
                                        </div>
                                        <div class="form-group" v-if="selected_next_maintenance">
                                            <label>Remind before (No. of {{track_type}})</label>
                                            <input type="number" class="form-control" v-model="form.reminder_before"
                                                   required>
                                        </div>

                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Make</label>
                                    <input type="text" class="form-control" v-model="form.make" required>
                                </div>
                                <div class="form-group">
                                    <label>Year Of Make</label>
                                    <input type="number" class="form-control" v-model="form.year_of_make" required>
                                </div>
                                <div class="form-group">
                                    <label>Year of Registration</label>
                                    <input type="number" class="form-control" v-model="form.year_of_reg" required>
                                </div>
                                <div class="form-group">
                                    <label>Engine No</label>
                                    <input type="text" class="form-control" v-model="form.engine_no" required>
                                </div>
                                <div class="form-group">
                                    <label>Chasis No</label>
                                    <input type="text" class="form-control" v-model="form.chasis_no" required>
                                </div>
                                <div class="form-group">
                                    <label>Warranty Expiry</label>
                                    <datepicker v-model="form.warranty"></datepicker>
                                </div>
                                <div class="form-group">
                                    <label>Assign To</label>                                  
                                     <model-select :options="users"
                                        v-model="form.assign_to"                      
                                        required>
                                        </model-select>  
                                 </div>
                                <div class="form-group">
                                    <label>Fuel Type</label>
                                    <select v-model="form.fuel_type_id"
                                            class="form-control" required>
                                        <option :value="type.id" v-for="type in fuel_types" :key="type.id">{{type.name}}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_machine ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import datepicker from 'vuejs-datepicker';
    import Multiselect from 'vue-multiselect';    
    import { ModelSelect } from 'vue-search-select'; 
    export default {
        props: ['edit'],
        data() {
            return {
                form: {
                    code: '',
                    description: '',
                    make: '',
                    track_by_id: '',
                    year_of_make: '',
                    year_of_reg: '',
                    engine_no: '',
                    chasis_no: '',
                    plate_no:'',
                    assign_to: '',
                    next_readings: '',
                    warranty: '',
                    status: '',
                    insurance_file: '',
                    start_date: '',
                    expiry_date: '',
                    reminder_before:'',
                    service_type_id:[],
                    fuel_type_id:'',
                    id: ''
                },
                value:[],
                selected: [1],
                selected_next_maintenance: false,
                track_type: '',
                edit_machine: this.edit,
                tracks: {},
                users: [],
                show_file: false,
                service_types:[],
                services:[],
                show_service:false,
                fuel_types:{},
                show_next_readings:false
            }
        },

            created() {
            this.listen();
            this.getTracks();
            this.getUsers();
            this.getServiceTypes();
            this.getFuelTypes();
        },
        watch:{
            insurance(){
               if (this.form.start_date !=='' && this.form.expiry_date !==''){
                   if (this.convertDate(this.form.start_date) > this.convertDate(this.form.expiry_date)){
                      this.$refs.myDatepicker.clearDate();
                      this.$toastr.e('Start date cannot be greater than Expiry date')
                   };
               }
            },
            readings(){
                //     if (this.form.next_readings !=='' && this.form.reminder_before !==''){
                //     if (parseInt(this.form.next_readings) < parseInt(this.form.reminder_before)){
                //         this.form.reminder_before = '';
                //         return this.$toastr.e('Reminder before readings cannot be greater next readings');
                //     }
                // };
            },
        },
    computed:{
            insurance(){
                 return [this.form.start_date,this.form.expiry_date].join();
            },
        readings(){
            return [this.form.next_readings,this.form.reminder_before].join();
        }
    },
        methods: {
            getFuelTypes(){
              axios.get('fuel-types')
                  .then(fuel => {
                      this.fuel_types = fuel.data
                  })
            },
            customLabel (option) {
                return `${option.name} - ${option.service_after}`
            },
            updateServices(value){
                console.log(value)
           let services = [];
           value.forEach((val) =>  {
               services.push(val.id);
           });
            this.form.service_type_id = services;
               },

            getServiceTypes(){
                axios.get('service-types')
                    .then(res => {
                       this.services = res.data
                    })
            },
            onFileChange(e) {
                let files = e.target.files || e.dataTransfer.files;
                if (!files.length)
                    return;
                this.createImage(files[0]);
            },
            createImage(file) {
                let reader = new FileReader();
                let vm = this;
                reader.onload = (e) => {
                    vm.form.insurance_file = e.target.result;
                };
                reader.readAsDataURL(file);
            },
            enableUpload() {
               this.form.status == 1 ? this.show_file = true : this.show_file = false;
            },
            saveMachine() {
                if (this.form.status ==1){
                    if ((this.form.start_date ===null || this.form.start_date ==='') || (this.form.expiry_date ===null || this.form.expiry_date ==='') || (this.form.insurance_file ==='' || this.form.insurance_file ===null)){
                        return this.$toastr.e('Ensure You have entered Start date, Expiry date and Insurance file.');
                    }
                }
               this.edit_machine ? this.update() : this.save();
            },
            save() {
                let form = document.getElementById('asset');
                let formData = new FormData(form);
                formData.append('insurance_file', this.form.insurance_file);
                formData.append('start_date', (this.form.start_date !=='' && this.form.start_date !==null) ? this.convertDate(this.form.start_date) :'');
                formData.append('expiry_date',(this.form.expiry_date !=='' && this.form.expiry_date !==null) ? this.convertDate(this.form.expiry_date) :'');
                formData.append('code', this.form.code);
                formData.append('description', this.form.description);
                formData.append('make', this.form.make);
                formData.append('track_by_id', this.form.track_by_id);
                formData.append('year_of_make', this.form.year_of_make);
                formData.append('year_of_reg', this.form.year_of_reg);
                formData.append('engine_no', this.form.engine_no);
                formData.append('chasis_no', this.form.chasis_no);
                formData.append('plate_no', this.form.plate_no);
                formData.append('assign_to', this.form.assign_to);
                formData.append('fuel_type_id', this.form.fuel_type_id);
              //  formData.append('next_readings', this.form.next_readings);
                formData.append('reminder_before', this.form.reminder_before);
                formData.append('warranty', this.form.warranty !=='' ? this.convertDate(this.form.warranty) : '');
                formData.append('service_type_id', (this.form.service_type_id !=='' && this.form.service_type_id !==null) ? JSON.stringify(this.form.service_type_id) :'');
                formData.append('status', this.form.status);
                const config = {
                    headers: {'Content-Type': 'multipart/form-data'}
                }
                 delete this.form.id;
                axios.post('machines', formData,config)
                    .then(res =>{
                       this.$toastr.s('Asset created Successfully.');
                       eventBus.$emit('listMachines', res.data)
                    })
                    .catch(error => error.response)
            },
            update() {
                let form = document.getElementById('asset');
                let formData = new FormData(form);
                formData.append('insurance_file', this.form.insurance_file);
                formData.append('start_date', (this.form.start_date !=='' && this.form.start_date !==null) ? this.convertDate(this.form.start_date) :'');
                formData.append('expiry_date',(this.form.expiry_date !=='' && this.form.expiry_date !==null) ? this.convertDate(this.form.expiry_date) :'');
                formData.append('code', this.form.code);
                formData.append('description', this.form.description);
                formData.append('make', this.form.make);
                formData.append('track_by_id', this.form.track_by_id);
                formData.append('year_of_make', this.form.year_of_make);
                formData.append('year_of_reg', this.form.year_of_reg);
                formData.append('engine_no', this.form.engine_no);
                formData.append('chasis_no', this.form.chasis_no);
                formData.append('plate_no', this.form.plate_no);
                formData.append('assign_to', this.form.assign_to);
                formData.append('fuel_type_id', this.form.fuel_type_id);
                formData.append('reminder_before', this.form.reminder_before);
              //  formData.append('next_readings', this.form.next_readings);
                formData.append('warranty', (this.form.warranty !=='' && this.form.warranty !==null) ? this.convertDate(this.form.warranty) :'');
                formData.append('service_type_id', (this.form.service_type_id !=='' && this.form.service_type_id !=='undefined') ? JSON.stringify(this.form.service_type_id) :'');
                formData.append('status', this.form.status);
                formData.append('_method', 'PUT');
                const config = {
                    headers: {'Content-Type': 'multipart/form-data'}
                }
                axios.post(`machines/${this.form.id}`, formData,config)
                    .then(res => {
                        //console.log(res.data);
                       this.edit_machine = false;
                        this.$toastr.s('Asset updated Successfully.');
                       eventBus.$emit('updateMachine', res.data);
                    })
                    .catch(error => error.response)
            },
            convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            trackValue() {
                this.service_types = [];
                this.show_service = true;
                this.tracks.forEach(track => {
                    if (track.id === this.form.track_by_id) {
                        this.track_type = track.name;
                        this.selected_next_maintenance = true;
                      for (let i=0;i<this.services.length;i++){
                          if (this.services[i]['track_by_id'] === track.id){
                          //this.form.next_readings = this.services[i]['service_after'];
                              this.service_types.push(this.services[i]);
                          }
                      }
                    }
                })
            },
            cancel() {
                eventBus.$emit('cancel')
            },
            getTracks() {
                axios.get('track-by')
                    .then(tracks => {
                        this.tracks = tracks.data;
                    })
            },
            getUsers() {
                axios.get('users')
                    .then(users => {
                        users.data.forEach(u =>{
                            this.users.push({
                                'value': u.id,
                                'text': u.name
                            })
                        })                        
                    });
            },
            listen() {
                if (this.edit) {
                    this.form = this.$store.state.machine,
                    this.$store.state.machine.status === 1 ? this.show_file = true :'';
                    this.selected_next_maintenance = true;
                    this.track_type = this.$store.state.machine.track_name;
                    this.value = this.$store.state.machine.service_types;
                    this.show_service = true;
                    console.log(this.$store.state.machine);
                    if (this.form.status ==1){
                        this.show_file = true;
                    }

                   axios.get('machines')
                    .then(res => {
                        let machine = res.data.find(m => m.id === this.form.id);
                        if (machine.next_readings >=0 && machine.next_readings > machine.current_readings){
                         this.show_next_readings = true;
                         this.form.next_readings = machine.next_readings;
                        }
                    })
                    axios.get('service-types')
                        .then(res => {
                            for (let i=0;i<res.data.length;i++){
                                   if (res.data[i]['track_by_id'] === this.$store.state.machine.track_by_id){
                                  this.service_types.push(res.data[i]);
                                }
                            }
                        })
                }
            }
        },
        components:{
            datepicker,
            Multiselect,
            ModelSelect
        }
    }
</script>

<style scoped>

</style>
