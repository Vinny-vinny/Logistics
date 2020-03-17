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
                                    <model-select :options="projects"
                                                    v-model="form.asset_category_id"
                                                    @input="subProject()"
                                                    required>
                                    </model-select>
                                </div>
                                  <div class="form-group">
                                      <label>Vehicle</label>
                                      <model-select :options="subprojects"
                                                    v-model="form.machine_id"
                                      >
                                      </model-select>
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
    import { ModelSelect } from 'vue-search-select';
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
                projects:[],
                subprojects:[]
            }
        },
        created() {
        this.getMachines();
        this.getProjects();
            },
        methods:{
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

            getProjects() {
                axios.get('asset-category')
                    .then(res => {
                        res.data.forEach(p => {
                            this.projects.push({
                                'value': p.project_link,
                                'text': p.name
                            })
                        })
                    })
            },
            getMachines(){
                axios.get('machines')
                    .then(res => {
                        this.machines = res.data
                    })
            },
            generateJob(){
                axios.post('generate-job',this.form)
                    .then(res => {
                       // console.log(res.data)
                        this.$router.push({path:`/jobcard-form/${res.data.id}`});
                        eventBus.$emit('close_form');
                    })
                .catch(err => console.log(err))
            },
            cancel(){
                eventBus.$emit('cancel_job');
            }
        },
  components:{
      ModelSelect
  }
    }
</script>

<style scoped>

</style>
