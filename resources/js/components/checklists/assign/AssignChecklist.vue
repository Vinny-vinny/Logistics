<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Assign Checklist</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="assignChecklist()">
                        <div class="form-group">
                            <label>Vehicle</label>
                            <select name="vehicle_id" v-model="form.vehicle_id" class="form-control" required>
                                <option :value="vehicle.id" v-for="vehicle in vehicles" :key="vehicle.id">{{vehicle.code}}</option>
                            </select>
                            </div>
                        <div class="form-group">
                            <label>Checklist</label>
                            <select name="checklist_id" class="form-control" v-model="form.checklist_id" required @change="getDuration()">
                                <option :value="checklist.id" v-for="checklist in checklists" :key="checklist.id">{{checklist.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <datepicker v-model="form.start_date" required></datepicker>
                        </div>

                        <div class="form-group">
                            <label>Reminder before(No. of days)</label>
                            <input type="number" class="form-control" v-model="form.reminder_before" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit ? 'Update': 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel()">Cancel</button>
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
                    vehicle_id:'',
                    start_date:'',
                    reminder_before:'',
                    checklist_id:'',
                    id:''
                },
                vehicles:{},
                checklists:{},
                expiry_types:{},
                date_type:'',
                show_expiry_duration:false,
                edit_checklist: this.edit,
                counter:0,
            }
        },
        created(){
            this.listen();
            this.getVehicles();
            this.getChecklists();
            this.getExpiryTypes();
        },
        methods:{
            getDuration(){
           this.checklists.forEach(checklist => {
              if (checklist.id === this.form.checklist_id){
                this.expiry_types.forEach(expiry => {
                    if (expiry.id === checklist.expiry_type_id){
                     this.date_type = expiry.name;
                     this.counter = checklist.duration_no;
                     this.show_expiry_duration = true;
                     return;
                    }
                })
              }
           });
            },
            getChecklists(){
                axios.get('checklists')
                    .then(checklist => {
                        this.checklists = checklist.data;
                    })
            },
            getExpiryTypes(){
                axios.get('expiry-types')
                    .then(expiry => {
                        this.expiry_types = expiry.data;
                    })
            },
            getVehicles(){
              axios.get('machines')
                  .then(vehicle => {
                     this.vehicles = vehicle.data
                  })
            },
            assignChecklist(){
                //     if (parseInt(this.form.reminder_before) > parseInt(this.counter)){
                //     return this.$toastr.e(`Duration cannot be greater than ${this.counter} ${this.date_type}`)
                // }
                this.form.start_date = this.convertDate(this.form.start_date);
                this.edit_checklist ? this.update() : this.save();

            },
             convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            save(){
                 delete this.form.id;
                axios.post('assign-checklist',this.form)
                    .then(res => eventBus.$emit('listAssignChecklists',res.data))
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`assign-checklist/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_checklist = false;
                        eventBus.$emit('updateAssignChecklist',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.assign_checklists;
                    this.show_expiry_duration = true;
                    this.counter = this.$store.state.assign_checklists.counter;
                    this.date_type = this.$store.state.assign_checklists.date_type
                }
            },
        },
        components:{
            datepicker
        }
    }
</script>

<style scoped>

</style>
