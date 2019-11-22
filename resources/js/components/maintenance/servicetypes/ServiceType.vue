<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_service ? 'Update Service Type' : 'New Service Type'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveService()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" required>
                        </div>
                         <div class="form-group">
                             <label>Track By</label>
                             <select name="track_by_id" class="form-control" v-model="form.track_by_id" required @change="serviceAfter()">
                                 <option :value="track.id" v-for="track in tracks" :key="track.id">{{track.name}}</option>
                             </select>
                         </div>
                        <div class="form-group" v-if="show_service_after">
                            <label>Service after every(No. of {{track_name}})</label>
                            <input type="number" class="form-control" v-model="form.service_after" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_service ? 'Update' : 'Save'}}</button>
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
                    track_by_id:'',
                    service_after:'',
                    id:''
                },
                edit_service: this.edit,
                tracks:{},
                track_name:'',
                show_service_after:false
                }
        },
        created(){
          this.listen();
          this.getTracks();
        },
        methods:{
            serviceAfter(){
              this.tracks.forEach(track => {
                  if (track.id === this.form.track_by_id){
                      this.track_name = track.name;
                      this.show_service_after = true;
                      return;
                  }
              })
            },
            getTracks(){
              axios.get('track-by')
                  .then(track =>{
                      this.tracks = track.data
                  })
            },
            saveService(){
                this.edit_service ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('service-types',this.form)
                    .then(res => eventBus.$emit('listServiceTypes',res.data))
                    .catch(error => error.response)
            },
            update(){
                   axios.patch(`service-types/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_service = false;
                        eventBus.$emit('updateServiceType',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.service_types,
                        this.show_service_after = true;
                    this.track_name = this.$store.state.service_types.track_name
                    }
            },
        }
    }
</script>

<style scoped>

</style>
