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
                            <label>Job Card</label>
                            <select  v-model="form.jobcard_id" class="form-control" required :disabled="edit">
                                <option :value="job.id" v-for="job in jobcards" :key="job.id">{{job.card_no}}</option>
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
    import {mapGetters} from "vuex";
   import dateConvert from "../../../mixins/datepicker";
    export default {
        mixins:[dateConvert],
        props:['edit'],
           data(){
            return {
                form:{
                    jobcard_id:'',
                    start_date:'',
                    reminder_before:'',
                    checklist_id:'',
                    id:''
                },
                date_type:'',
                show_expiry_duration:false,
                edit_checklist: this.edit,
                counter:0,
                jobcards:[],
            }
        },
        created(){
            this.listen();
            this.getJobs();
        },
        computed:{
          ...mapGetters({
              vehicles:'all_vehicles',
              expiry_types:'all_expiry_types',
              checklists:'all_checklists',
              jobs:'all_jobs'
          })
        },
        methods:{
            getJobs(){
           for (let i=0; i<this.jobs.length; i++){
               if (this.jobs[i]['category'].toLowerCase() === 'workshop' && this.jobs[i]['checklist_assigned'] ===0){
                   this.jobcards.push(this.jobs[i]);
               }
           }
            },

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
            assignChecklist(){
                this.form.start_date = this.convertDate(this.form.start_date);
                this.edit_checklist ? this.update() : this.save();

            },
            save(){
                 delete this.form.id;
                axios.post('assign-checklist',this.form)
                    .then(res => {
                        this.$store.state.all_my_assign_checklists.unshift(res.data);
                        eventBus.$emit('listAssignChecklists',res.data)
                    })
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
                    this.date_type = this.$store.state.assign_checklists.date_type;
                    this.jobcards = [];
                    this.getJobsEdited();
                }
            },
            getJobsEdited(){
            for (let i=0; i<this.jobs.length; i++){
                if (this.jobs[i]['category'].toLowerCase() === 'workshop'){
                    this.jobcards.push(this.jobs[i]);
                    if (this.jobs[i]['id'] === this.form.jobcard_id){
                        //this.jobcards.splice(res.data[i],1);
                    }
                }
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
