<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_track ? 'Update Track By' : 'New Track By'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveTrack()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_track ? 'Update' : 'Save'}}</button>
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
                    id:''
                },
                edit_track: this.edit
                }
        },
        created(){
            this.listen();
        },
        methods:{
            saveTrack(){
                this.edit_track ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('track-by',this.form)
                    .then(res => eventBus.$emit('listTracks',res.data))
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`track-by/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_track = false;
                        eventBus.$emit('updateTrack',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.track_by
                    }
            },
        }
    }
</script>

<style scoped>

</style>
