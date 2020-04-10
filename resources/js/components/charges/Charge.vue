<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_charge ? 'Update Charges' : 'New Charge'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveCharge()">
                        <div class="form-group">
                            <label>Select Item</label>
                            <model-select :options="parts"
                                          v-model="form.stock_link"
                            >
                            </model-select>
                        </div>
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" v-model="form.name" required>
                                <option value="Labour Charge">Labour Charge</option>
                                <option value="Standing Charge">Standing Charge</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_charge ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</template>

<script>
    import { ModelSelect } from 'vue-search-select';
    import {mapGetters} from 'vuex';
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    stock_link:'',
                    name:'',
                    id:''
                },
                edit_charge: this.edit,
                parts:[]
            }
        },
        created(){
            this.listen();
            this.getItems();
        },
        computed:{
         ...mapGetters({
             all_parts:'all_parts'
         })
        },
        methods:{
            getItems(){
            let items = this.all_parts.filter(p => p.service_item==1);
            items.forEach(p => {
                this.parts.push({
                    'value':p.stock_link,
                    'text': p.description
                })
            })
            },
            saveCharge(){
                this.edit_charge ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('charges',this.form)
                    .then(res => {
                        this.$store.state.all_my_charges.unshift(res.data);
                        eventBus.$emit('listCharges',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`charges/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_category = false;
                        eventBus.$emit('updateCharges',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.all_charges
                    console.log(this.form)
                }
            },
        },
        components:{
            ModelSelect
        }
    }
</script>

<style scoped>

</style>
