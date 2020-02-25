<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_charge ? 'Update Where to Charge' : 'New Where To Charge'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveCharge()">
                        <div class="form-group">
                            <label>Select Credit Account</label>
                           <model-select :options="accounts"
                                        v-model="form.account_id"                                                            
                                        >
                                        </model-select> 
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
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    account_id:'',
                    id:''
                },
                edit_charge: this.edit,
                accounts:[]
            }
        },
        created(){
            this.listen();
            this.getAccounts();
        },
        methods:{
            getAccounts(){
             axios.get('accounts')
             .then(res => {
                res.data.forEach(acc => {
                    this.accounts.push({
                        'value':acc.account_link,
                        'text': acc.account
                    })
                })
             })
            },
            saveCharge(){
                this.edit_charge ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('where-to-charge',this.form)
                    .then(res => {
                        eventBus.$emit('listCharge',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`where-to-charge/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_category = false;
                        eventBus.$emit('updateCharge',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.charges
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
