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
                                   <model-select :options="inv_transactions"
                                        v-model="form.invoice_id"
                                        @input="filterInvTR()"                           
                                        >
                                   </model-select>
                                </div>                 
                                  <div class="form-group">
                                    <label>Stock TR Code</label> 
                                    <model-select :options="stk_transactions"
                                        v-model="form.stock_issue_id"
                                        @input="filterStkTR()"                           
                                        >
                                   </model-select>                                   
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
import { ModelSelect } from 'vue-search-select'; 
    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    name:'',                   
                    invoice_id:'',
                    stock_issue_id:'',
                    id:''
                },
                edit_category: this.edit,                         
                stk_transactions:[],
                inv_transactions:[],
                transactions:{}
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
                res.data.forEach(t => {
                    this.inv_transactions.push({
                        'value':t.transaction_id,
                        'text': `${t.code}-${t.description}`
                    })
                })
                 res.data.forEach(t => {
                    this.stk_transactions.push({
                        'value':t.transaction_id,
                        'text': `${t.code}-${t.description}`
                    })
                })

               })
            },
             filterInvTR(){
                    let transactions = this.transactions.filter(t => t.transaction_id !==this.form.invoice_id);
                    this.stk_transactions = [];                     
                      transactions.forEach(t => {                 
                      this.stk_transactions.push({
                        'value': t.transaction_id,
                        'text': `${t.code}-${t.description}`
                      })  
                   
                })
                      
                },
              filterStkTR(){                     
                 let transactions = this.transactions.filter(t => t.transaction_id !== this.form.stock_issue_id);
                 this.inv_transactions = []; 
                    transactions.forEach(t => {
                    this.inv_transactions.push({
                        'value': t.transaction_id,
                        'text': `${t.code}-${t.description}`
                    })
                })
                
              },
            saveCategory(){
                this.edit_category ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('fuel-category',this.form)
                    .then(res => {                       
                       eventBus.$emit('listFuelCategories',res.data)
                    })
                    .catch(error => error.response)
            },
            update(){
                axios.patch(`fuel-category/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_category = false;
                        eventBus.$emit('updateFuelCategory',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.fuel_categories ;
                    setTimeout(()=>{
                    this.filterInvTR();
                    this.filterStkTR();
                    },3000)                         
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
