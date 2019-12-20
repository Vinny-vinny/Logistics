<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_category ? 'Update Category' : 'New Category'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveCategory()">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" class="form-control" v-model="form.name" required>
                        </div>                       
                                  <div class="form-group">
                                    <label>INV TR Code</label>
                                    <select class="form-control" v-model="form.inv_tr_id" @change="filterInvTR()">
                                        <option :value="tr.transaction_id" v-for="tr in inv_transactions"
                                                :key="tr.transaction_id">{{tr.code}}-{{tr.description}}
                                        </option>
                                    </select>
                                </div>                 
                                  <div class="form-group">
                                    <label>Stock TR Code</label> 
                                    <select class="form-control" v-model="form.stk_tr_id" @change="filterStkTR()">
                                        <option :value="stk.transaction_id" v-for="stk in stk_transactions"
                                                :key="stk.transaction_id">{{stk.code}}-{{stk.description}}
                                        </option>
                                    </select>
                                </div>                 
                        <button type="submit" class="btn btn-primary">{{edit_category ? 'Update' : 'Save'}}</button>
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
                    transaction_id:[],
                    id:''
                },
                edit_category: this.edit,
                transactions:[],
                transaction:[],
                inv_transactions:{},
                stk_transactions:{}
            }
        },
        created(){
            this.listen();
            this.getTransactions();            
        },
        methods:{
           
            getTransactions(){
               axios.get('transactions')
               .then(res => {
                this.transactions = res.data;  
                this.inv_transactions = res.data; 
                this.stk_transactions = res.data;                       
               })
            },
             filterInvTR(){               
                this.stk_transactions = this.transactions.filter(t => t.transaction_id !==this.form.inv_tr_id);              
                },
              filterStkTR(){
                this.inv_transactions = this.transactions.filter(t => t.transaction_id !== this.form.stk_tr_id);
              },
            saveCategory(){
                this.edit_category ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('jobcard-category',this.form)
                    .then(res => {                       
                       eventBus.$emit('listJobcardCategories',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`jobcard-category/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_category = false;
                        eventBus.$emit('updateJobcardCategory',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.jobcard_categories                          
                }
            },

        },
    
    }
</script>

<style scoped>

</style>
