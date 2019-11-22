<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_provider_type ? 'Update Service Provider Type' : 'New Service Provider Type'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveProvider()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_provider_type ? 'Update' : 'Save'}}</button>
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
                edit_provider_type: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveProvider(){
                this.edit_provider_type ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('service-provider-types',this.form)
                    .then(res => eventBus.$emit('listProviderTypes',res.data))
                    .catch(error => error.response)
            },
            update(){
                     axios.patch(`service-provider-types/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_provider_type = false;
                        eventBus.$emit('updateProviderType',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.provider_type
                    }
            },
        }
    }
</script>

<style scoped>

</style>
