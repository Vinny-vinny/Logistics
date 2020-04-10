<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_job_type ? 'Update Job Type' : 'New Job Type'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveJobType()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" required>
                        </div>
                        <div class="form-group">
                            <label>Hourly rate</label>
                            <input type="number" step="0.001" class="form-control" v-model="form.hourly_rate" required>
                        </div>
                        <div class="form-group">
                            <label>Currency</label>
                            <select class="form-control" v-model="form.currency" required>
                                <option value="KSH">KSH</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">{{edit_job_type ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    name:'',
                    description:'',
                    hourly_rate:'',
                    currency:'',
                    id:''
                },
                edit_job_type: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveJobType(){
                this.edit_job_type ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('job-types',this.form)
                    .then(res => {
                       this.$store.state.all_my_job_types.push(res.data);
                       eventBus.$emit('listJobTypes',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`job-types/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_job_type = false;
                        eventBus.$emit('updateJobType',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.job_type
                }
            },
        }
    }
</script>

<style scoped>

</style>
