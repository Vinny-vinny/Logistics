<template>
    <div>
      
        <!-- Main content -->
        <section class="content" v-if="!show_print">

            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"></h3>
                </div>
                <div class="box-body">
                              <form @submit.prevent="generateJob()">
                                  <div class="form-group">
                                    <label>Project</label>
                                    <select v-model="form.asset_category_id" required class="form-control" @change="subProject()">
                                     <option :value="project.id" v-for="project in projects" :key="project.id">{{project.name}}</option>
                                    </select>
                                </div>
                                  <div class="form-group">
                                      <label>Vehicle</label>
                                      <select class="form-control" v-model="form.machine_id" required>
                                          <option :value="m.id" v-for="m in subprojects" :key="m.id">{{m.code}}</option>
                                      </select>
                                  </div>
                                  <div class="form-group">
                                      <label>Type</label>
                                      <select class="form-control" v-model="form.service_type" required>
                                          <option value="Internal">Internal</option>
                                          <option value="External">External</option>
                                      </select>
                                  </div>
                                  <button class="btn btn-success" type="submit">Generate</button>
                                  <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                              </form>
                    </div>
                </div>
        </section>
    </div>
</template>
<script>
    export default {
        props:['printJob'],
        data(){
            return{
                form:{
                    service_type:'',
                    machine_id:'',
                    asset_category_id:'',
                    maintenance: [{category: '', description: '', root_cause: ''}],
                },
                show_print:false,
                machines:{},
                projects:{},
                subprojects:{}
            }
        },
        created() {
        this.getMachines();
        this.getProjects();
            },
        methods:{
          subProject(){
            this.subprojects = {};
           setTimeout(()=>{
 this.subprojects = this.machines.filter(vehicle => vehicle.asset_category_id == this.form.asset_category_id);
           },500)              

            },
          getProjects() {
               axios.get('asset-category')
              .then(res => {
                  this.projects = res.data;
              })
            },
            getMachines(){
                axios.get('machines')
                    .then(res => {
                        this.machines = res.data
                    })
            },
            generateJob(){
                this.form.maintenance = JSON.stringify(this.form.maintenance);
                axios.post('generate-job',this.form)
                    .then(res => {
                        this.$router.push({path:`/jobcard-form/${res.data.id}`});
                        eventBus.$emit('close_form');
                    })
                .catch(err => console.log(err))
            },
            cancel(){
                eventBus.$emit('cancel_job');
            }
        },

    }
</script>

<style scoped>

</style>
