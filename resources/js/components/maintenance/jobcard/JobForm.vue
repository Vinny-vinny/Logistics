<template>
    <div>
       <job-card-form v-if="show_print" :job="job_details"></job-card-form>
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
    import {mapGetters} from "vuex";
    import JobCardForm from "./JobCardForm";
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
                projects:[],
                subprojects:[],
                job_details:{}
            }
        },
        created() {
        this.listen();
        this.getProjects();
            },
        computed:{
          ...mapGetters({
              all_projects:'all_projects',
              machines:'all_vehicles'
          }),
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
             this.all_projects.forEach(p => {
            this.projects.push({
                'value': p.project_link,
                'text': p.name
            })
        })
       },

            generateJob(){
                axios.post('generate-job',this.form)
                    .then(res => {
                       this.job_details = res.data;
                       this.show_print = true;
                    })
                .catch(err => console.log(err))
            },
            cancel(){
                eventBus.$emit('cancel_job');
            },
            listen(){
                eventBus.$on('updatePrint',(response) =>{
                    eventBus.$emit('updateJobcard',response);
                    console.log('walla');
                    this.show_print = false;

                })
            }
        },
  components:{
      ModelSelect,
      JobCardForm

  }
    }
</script>

<style scoped>

</style>
