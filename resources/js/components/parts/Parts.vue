<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_part ? 'Update Part' : 'New Part'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="savePart()">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" v-model="form.code" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" required>
                        </div>
                        <div class="form-group">
                            <label>Cost</label>
                            <input type="number" step="0.001" class="form-control" v-model="form.cost" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_part ? 'Update' : 'Save'}}</button>
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
                    code:'',
                    description:'',
                    cost:'',
                    id:''
                },
                edit_part: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            savePart(){
                this.edit_part ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('parts',this.form)
                    .then(res => eventBus.$emit('listParts',res.data))
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`parts/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_part = false;
                        eventBus.$emit('updateParts',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.parts
                }
            },
        }
    }
</script>

<style scoped>

</style>
