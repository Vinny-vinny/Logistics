<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_tool ? 'Update Checklist Tools' : 'New Checklist Tool'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveTrack()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_tool ? 'Update' : 'Save'}}</button>
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
                edit_tool: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveTrack(){
                this.edit_tool ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('checklist-tool',this.form)
                    .then(res => eventBus.$emit('listTools',res.data))
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`checklist-tool/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_tool = false;
                        eventBus.$emit('updateTool',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.checklist_tool
                }
            },
        }
    }
</script>

<style scoped>

</style>
