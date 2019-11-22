<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_fuel ? 'Update Fuel Balance' : 'New Fuel Balance'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveFuel()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_fuel ? 'Update' : 'Save'}}</button>
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
                edit_fuel: this.edit
                }
        },
        created(){
            this.listen();
        },
        methods:{
            saveFuel(){
                this.edit_fuel ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('fuel-balance',this.form)
                    .then(res => eventBus.$emit('listFuelBalance',res.data))
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`fuel-balance/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_fuel = false;
                        eventBus.$emit('updateFuelBalance',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.fuel_balance
                    }
            },
        }
    }
</script>

<style scoped>

</style>
