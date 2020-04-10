<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_mechanic ? 'Update Mechanic' : 'New Mechanic'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveMechanic()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" v-model="form.email" required>
                        </div>
                        <div class="form-group">
                            <label>Phone</label>
                            <input type="text" class="form-control" v-model="form.phone" required>
                        </div>
                        <div class="form-group">
                            <label>Address</label>
                            <input type="text" class="form-control" v-model="form.address" required>
                        </div>

                        <button type="submit" class="btn btn-primary">{{edit_mechanic ? 'Update' : 'Save'}}</button>
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
                    phone:'',
                    email:'',
                    address:'',
                    id:''
                },
                edit_mechanic: this.edit
            }
        },
        created(){
            this.listen();
        },
        methods:{
            saveMechanic(){
                this.edit_mechanic ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('mechanics',this.form)
                    .then(res => {
                        this.$store.state.all_my_mechanics.unshift(res.data);
                        eventBus.$emit('listMechanics',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`mechanics/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_mechanic = false;
                        eventBus.$emit('updateMechanic',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.mechanics
                }
            },
        }
    }
</script>

<style scoped>

</style>
