<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_supplier ? 'Update Supplier' : 'New Supplier'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveSupplier()">
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control" v-model="form.code" required>
                        </div>
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Phone No</label>
                            <input type="text" class="form-control" v-model="form.phone_no" required>
                        </div>
                        <div class="form-group">
                            <label>Fuel Type</label>
                            <select name="fuel_type_id" class="form-control" v-model="form.fuel_type_id" required>
                                <option :value="type.id" v-for="type in fuel_types" :key="type.id">{{type.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Price</label>
                            <input type="number" step="0.001" class="form-control" v-model="form.price" required>
                        </div>
                        <div class="form-group">
                            <label>Currency</label>
                            <select name="currency" class="form-control" required v-model="form.currency">
                                <option value="KSH">KSH</option>
                                <option value="USD">USD</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_supplier ? 'Update' : 'Save'}}</button>
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
                    name:'',
                    phone_no:'',
                    price:'',
                    fuel_type_id:'',
                    currency:'',
                    id:''
                },
                edit_supplier: this.edit,
                fuel_types:{}
            }
        },
        created(){
            this.listen();
            this.getFuelTypes();
        },
        methods:{
            getFuelTypes(){
              axios.get('fuel-types')
                  .then(fuel => {
                      this.fuel_types = fuel.data;
                  })
            },
            saveSupplier(){
                this.edit_supplier ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                   axios.post('fuel-supplier',this.form)
                    .then(res => eventBus.$emit('listFuelSuppliers',res.data))
                    .catch(error => error.response)
            },
            update(){
                     axios.patch(`fuel-supplier/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_track = false;
                        eventBus.$emit('updateFuelSupplier',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.fuel_supplier
                }
            },
        }
    }
</script>

<style scoped>

</style>
