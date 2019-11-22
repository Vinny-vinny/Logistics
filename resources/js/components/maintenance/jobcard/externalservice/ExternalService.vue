<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_external ? 'Update External Service' : 'New External Service'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveExternalService()">
                        <div class="form-group">
                            <label>Job Card</label>
                            <select name="job_card_id" class="form-control" v-model="form.job_card_id" required @change="vehicleDetails()">
                                <option :value="job.id" v-for="job in jobs" :key="job.id">{{job.card_no}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>External Service Type</label>
                            <select name="service_type" class="form-control" v-model="form.service_type" required @change="getServiceType()">
                                <option value="parts">Parts</option>
                                <option value="labour">labour</option>
                            </select>
                        </div>
                        <div class="form-group" v-if="show_labour">
                            <label>Service Provider</label>
                            <select name="service_provider_id" class="form-control" v-model="form.service_provider_id">
                                <option :value="p.id" v-for="p in service_providers" :key="p.id">{{p.name}}</option>
                            </select>
                        </div>
                       <div class="row">
                           <div class="col-md-6">
                               <label>Vehicle #:</label> {{vehicle}}
                           </div>
                           <div class="col-md-6">
                               <label>Make & Model:</label> {{make}}
                           </div>
                       </div>
                        <br>
                        <div class="form-group" v-if="show_parts">
                            <label>Description</label>
                            <textarea name="description" class="form-control" v-model="form.description"></textarea>
                        </div>
                        <div class="row" v-if="show_labour">
                            <div class="col-md-12">
                                    <table style="width: 100%">
                                        <tr>
                                            <th>Description</th>
                                            <th style="text-align: center">Start Date</th>
                                            <th style="text-align: center">End Date</th>
                                            <th></th>
                                        </tr>
                                        <tr v-for="(l,i) in form.labours">
                                            <td>
                                                <div class="form-group">

                                                    <input type="text" class="form-control description" v-model="l.description">
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <datepicker v-model="l.start_date" class="supplier"></datepicker>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <datepicker v-model="l.end_date" class="supplier"></datepicker>
                                                </div>
                                            </td>
                                            <td>
                                                <i class="fa fa-minus-circle supplier" @click="removeLabour(i)" v-show="i || (!i && form.labours.length > 1)"></i>
                                                <i class="fa fa-plus-circle supplier" @click="addLabour(i)" v-show="i == form.labours.length -1"></i>
                                            </td>
                                        </tr>
                                    </table>
                            </div>
                        </div>
                        <div class="row" v-if="show_parts">
                            <div class="col-md-12">
                                  <table style="width: 100%">
                                      <tr>
                                          <th>Item</th>
                                          <th style="text-align: center">Supplier</th>
                                          <th style="text-align: center">Qty</th>
                                          <th></th>
                                      </tr>
                                      <tr v-for="(l,i) in form.parts">
                                          <td>
                                              <div class="form-group">
                                                  <select class="form-control" v-model="l.item">
                                                      <option :value="p.id" v-for="p in items" :key="p.id">{{p.code}} - {{p.description}}</option>
                                                  </select>
                                              </div>
                                          </td>
                                          <td>
                                              <div class="form-group">
                                                  <select class="form-control supplier" v-model="l.supplier">
                                                      <option :value="s.id" v-for="s in suppliers" :key="s.id">{{s.name}}</option>
                                                  </select>
                                              </div>
                                          </td>
                                          <td>
                                              <div class="form-group">
                                                  <input type="number" class="form-control qty" v-model="l.quantity">
                                              </div>
                                          </td>
                                          <td>
                                              <i class="fa fa-minus-circle add" @click="removePart(i)" v-show="i || (!i && form.parts.length > 1)"></i>
                                              <i class="fa fa-plus-circle add" @click="addPart(i)" v-show="i == form.parts.length -1"></i>
                                          </td>
                                      </tr>
                                  </table>
                              </div>
                        </div>
                         <button type="submit" class="btn btn-primary">{{edit_external ? 'Update' : 'Save'}}</button>
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
        props:['edit'],
        data(){
            return {
                form:{
                    job_card_id:'',
                    description:'',
                    service_type:'',
                    service_provider_id:'',
                    id:'',
                    labours:[{description: '',start_date:'',end_date:''}],
                    parts:[{item: '',supplier:'',quantity:''}]
                },
                edit_external: this.edit,
                service_providers:{},
                suppliers:{},
                jobs:{},
                vehicle:'',
                make:'',
                show_parts:false,
                show_labour:false,
                items:{}
            }
        },
        created(){
            this.listen();
            this.getSuppliers();
            this.getServiceProviders();
            this.getJobs();
            this.getParts();
        },
        methods:{
            addPart(i){
              this.form.parts.push({item:'',supplier:'',quantity:''})
            },
            removePart(i){
              this.form.parts.splice(i,1);
            },
            addLabour(i){
              this.form.labours.push({description:'',start_date:'',end_date:''})
            },
            removeLabour(i){
              this.form.labours.splice(i,1);
            },
            getParts(){
                axios.get('parts')
                    .then(part => {
                        this.items = part.data
                    })
            },
            vehicleDetails(){
              this.jobs.forEach(job => {
                  if (job.id === this.form.job_card_id){
                      this.vehicle = job.machine;
                      this.make = job.make;
                  }
              })
            },
            getServiceType(){
                console.log(this.form.service_type)
               if (this.form.service_type === 'parts'){
                   this.show_parts = true;
                   this.show_labour = false;
               } else{
                   this.show_labour = true;
                   this.show_parts = false;
               }
            },
            getJobs(){
              axios.get('job-card')
                  .then(jobs => {
                      this.jobs = jobs.data;
                  })
            },
            getServiceProviders(){
              axios.get('fuel-supplier')
                  .then(supplier => {
                      this.service_providers = supplier.data
                  })
            },
            getSuppliers(){
              axios.get('service-providers')
                  .then(suppliers => {
                      this.suppliers = suppliers.data;
                  })
            },
            saveExternalService(){
                if (this.form.service_type ==='labour'){
                    if(this.form.service_provider_id ===''){
                        return this.$toastr.e('Service provider is required.')
                    }
                   }
                if (Object.values(this.form.labours[0])[0] !== '' || Object.values(this.form.labours[0])[1] !== '' || Object.values(this.form.labours[0])[2] !== '') {
                    for (let i = 0; i < this.form.labours.length; i++) {
                        if (this.form.labours[i]['description'] === '' || this.form.labours[i]['start_date'] === ''|| this.form.labours[i]['end_date'] === '') {
                            return this.$toastr.e('Please all parts fields are required.');
                        }
                        if(moment(this.form.labours[i]['start_date']).format('YYY-MM-DD') > moment(this.form.labours[i]['end_date']).format('YYY-MM-DD')){
                            return this.$toastr.e('Start date cannot be greater than End date.');
                        }
                    }
                }
                if (this.form.service_type ==='parts'){
                    if(this.form.description ===''){
                        return this.$toastr.e('Description field is required.')
                    }

                }
                if (Object.values(this.form.parts[0])[0] !== '' || Object.values(this.form.parts[0])[1] !== '' || Object.values(this.form.parts[0])[2] !== '') {
                    for (let i = 0; i < this.form.parts.length; i++) {
                        if (this.form.parts[i]['item'] === '' || this.form.parts[i]['supplier'] === ''|| this.form.parts[i]['quantity'] === '') {
                            return this.$toastr.e('Please all labour fields are required.');
                        }
                    }
                }
                this.edit_external ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('external-services',this.form)
                    .then(res =>{
                        console.log(res.data)
                        eventBus.$emit('listExternalServices',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                  axios.patch(`external-services/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_external = false;
                        eventBus.$emit('updateExternalService',res.data);
                    })
                    .catch(error => error.response)
            },
                 cancel(){
                eventBus.$emit('cancel')
            },
            convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.external_services;
                    this.vehicle = this.form.vehicle;
                    this.make = this.form.make;

                    if (this.form.service_type ==='parts'){
                        this.form.parts = JSON.parse(this.form.parts);
                        this.show_parts = true;
                        this.show_labour = false;
                    }else {
                        this.form.labours = JSON.parse(this.form.labours);
                        this.show_labour = true;
                        this.show_parts = false;
                    }
                    console.log(this.form);
                }
            },
        },
        components:{
            datepicker
        }
    }
</script>

<style scoped>
    .add{
        margin-left: 25px;
    }
    .supplier{
        margin-left: 10px;
    }
    .qty{
        margin-left: 20px;
    }
</style>
