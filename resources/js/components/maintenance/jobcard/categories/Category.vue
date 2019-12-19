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
                            <label>Transaction</label>
                            <label>Service Type</label>
                                    <multiselect
                                        v-model="transaction"
                                        :options="transactions"
                                        :multiple="true"
                                        track-by="id"
                                        :custom-label="customLabel"
                                        placeholder="Select Transaction Types"
                                        @input="updateCategories"
                                    >
                                    </multiselect>
                           
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
import Multiselect from 'vue-multiselect';
    import 'vue-multiselect/dist/vue-multiselect.min.css';
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
            }
        },
        created(){
            this.listen();
            this.getTransactions();            
        },
        methods:{
            updateCategories(value){ 
            console.log(value);             
           let transactions = [];
           value.forEach((val) =>  {
               transactions.push(val.id);
           });
            this.form.transaction_id = transactions;
               },
               customLabel (option) {
                return `${option.code} - ${option.description}`;
            },
            getTransactions(){
               axios.get('transactions')
               .then(res => {
                this.transactions = res.data;                             
               })
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
                    let ts = [];                    
                      for(let i=0;i<this.$store.state.jobcard_categories.transaction_type.length;i++){
                       ts.push(this.$store.state.jobcard_categories.transaction_type[i]);
                      }
                      this.transaction =ts;                      
                }
            },
        },
        components:{            
            Multiselect
        }
    }
</script>

<style scoped>

</style>
