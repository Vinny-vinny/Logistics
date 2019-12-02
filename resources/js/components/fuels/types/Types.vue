<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_type ? 'Update Fuel Type' : 'New Fuel Type'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveFuelype()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Pump Price</label>
                            <input type="number" step="0.001" class="form-control" v-model="form.rate" required>
                        </div>
                        <div class="form-group">
                            <label>Currency</label>
                            <select name="currency" class="form-control" v-model="form.currency">
                                <option value="USD">USD</option>
                                <option value="KSH">KSH</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_type ? 'Update' : 'Save'}}</button>
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
                    rate:'',
                    currency:'',
                    id:''
                },
                edit_type: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveFuelype(){
                this.edit_type ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('fuel-types',this.form)
                    .then(res => eventBus.$emit('listFuelTypes',res.data))
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`fuel-types/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_type = false;
                        eventBus.$emit('updateFuelTypes',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.fuel_types
                }
            },
        }
    }
</script>

<style scoped>

</style>
