<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Reverse Requisition</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveRequisition()">
                        <div class="row">
                            <div class="col-md-6">
                            <div class="form-group">
                            <label>External Reference</label>
                            <input type="text" class="form-control" v-model="form.external_reference" disabled>
                        </div>
                          <div class="form-group">
                            <label>Requisition Type</label>
                            <select class="form-control select2" v-model="form.type" disabled>
                                <option value="Internal">Stock Issue</option>
                                <option value="External">Invoice Customer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" disabled>
                        </div>
                         <div class="form-group">
                            <label>Project</label>
                            <model-select :options="projects"
                            v-model="form.project_id"
                            placeholder="select Project"
                            @input="subProject()"
                            :is-disabled="true">
                            </model-select>
                        </div>
                               <div class="form-group">
                                    <label>Sub Project</label>
                                    <model-select :options="subprojects"
                                            v-model="form.subproject_id">
                                    </model-select>
                                </div>
                           <div class="form-group">
                            <label>Stk Group</label>
                            <model-select :options="stk_groups"
                            v-model="form.group_name"
                            @input="selectedGroup()"
                            placeholder="select category"
                            :is-disabled="true">
                            </model-select>

                        </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                            <label>Requested On</label>
                            <datepicker v-model="form.requested_on" disabled></datepicker>
                        </div>
                        <div class="form-group">
                            <label>Person Collecting</label>
                            <input type="text" class="form-control" v-model="form.person_collecting" disabled>
                        </div>
                        <div class="form-group">
                            <label>Requested By</label>
                            <input type="text" v-model="username" class="form-control" disabled>
                        </div>

                                 <fieldset class="the-fieldset" v-if="show_issue">
                               <legend class="the-legend"><label class="fyr">Where To Charge</label></legend>
                                  <div>
                               <div class="form-group">
                           <label>Credit Account</label>
                           <model-select :options="accounts"
                            v-model="form.where_to_charge"
                             :is-disabled="true"
                            placeholder="Where to charge"
                            >
                            </model-select>
                               </div>

                         <div class="form-group">
                            <label>Debit Account</label>
                             <model-select :options="accountsd"
                              v-model="form.credit_account_id"
                             >
                             </model-select>

                        </div>
                       </div>
                   </fieldset>


                        </div>
                        </div>
                       <div class="form-group" v-show="show_customer">
                           <label>Customers</label>
                           <model-select :options="customers"
                                        v-model="form.customer_id"
                                        :is-disabled="true"
                                        >
                          </model-select>
                       </div>

                        <div class="form-group" v-if="form.type==='Internal'">
                                 <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Inventory Items</label></legend>
                            <table style="width: 100%">
                                <tr>
                                    <th>Item</th>
                                    <th>Uom</th>
                                    <th>Qty</th>
                                    <th>Avail. Qty</th>
                                    <th>Unit Cost</th>
                                    <th>Total Cost</th>
                                    <th>Total Cost(with VAT)</th>
                                    <th></th>
                                </tr>
                                <tr v-for="(item,k) in form.inventory_items_internal" :key="k">
                                      <td>
                                        <model-select :options="items"
                                        v-model="item.part"
                                        @input="part =item.part"
                                        class="i_p"
                                        :is-disabled="true"
                                        >
                                        </model-select>
                                    </td>
                                       <td>
                                       <input type="text" class="form-control qty" v-model="item.uom" disabled>
                                    </td>

                                    <td><input type="text" class="form-control qty" v-model="item.quantity"
                                               placeholder="Qty" @keyup="qty = item.quantity">
                                    </td>

                                    <td><input type="text" class="form-control qty" v-model="item.qty_available"
                                               disabled>
                                    </td>
                                    <td><input type="number" class="form-control p_in" step="0.001" v-model="item.unit_cost"
                                               placeholder="Unit Cost" disabled></td>
                                    <td><input type="number" class="form-control p_in_2" step="0.001" v-model="item.total_cost"
                                               placeholder="Total Cost" disabled></td>
                                    <td><input type="number" class="form-control p_ex_2" step="0.001" v-model="item.total_cost_inclusive"
                                               placeholder="Total Cost Inclusive VAT" disabled></td>

                                    <td>
                                        <i class="fa fa-minus-circle remove_2" @click="removeItem(k)"
                                           v-show="k || ( !k && form.inventory_items_internal.length > 1)"></i>
                                       <!--  <i class="fa fa-plus-circle add_2" @click="addItem(k)"
                                           v-show="k == form.inventory_items_internal.length-1"></i> -->
                                    </td>
                                </tr>
                            </table>
                         </fieldset>
                        </div>
                        <div class="form-group" v-if="form.type==='External'">
                             <fieldset class="the-fieldset">
                               <legend class="the-legend"><label class="fyr">Inventory Items</label></legend>
                            <table style="width: 100%">
                                <tr>
                                    <th>Item</th>
                                    <th>Uom</th>
                                    <th>Qty</th>
                                    <th>Avail. Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total Price</th>
                                    <th>Total Price(with VAT)</th>
                                    <th></th>
                                </tr>
                                <tr v-for="(item,k) in form.inventory_items_external" :key="k">
                                    <td>
                                         <model-select :options="items"
                                        v-model="item.part"
                                        @input="part =item.part"
                                        class="i_p"
                                        required>
                                        </model-select>
                                    </td>
                                        <td>
                                      <input type="text" class="form-control qty" v-model="item.uom" disabled>
                                    </td>
                                    <td><input type="number" class="form-control qty" v-model="item.quantity"
                                               placeholder="Qty"  @keyup="qty = item.quantity">
                                    </td>
                                    <td><input type="text" class="form-control qty" v-model="item.qty_available"
                                               disabled>
                                    </td>
                                    <td><input type="number" class="form-control p_in" step="0.001" v-model="item.unit_price"
                                               placeholder="Unit Price" @keyup="unit_price = costing(item.part)"></td>
                                    <td><input type="number" class="form-control p_in_2" step="0.001" v-model="item.total_price"
                                               placeholder="Total Price" disabled></td>
                                    <td><input type="number" class="form-control p_ex_2" step="0.001" v-model="item.total_price_inclusive"
                                               placeholder="Total Price Inclusive VAT" disabled></td>
                                       <td>
                                        <i class="fa fa-minus-circle remove_2" @click="removeItemExternal(k)"
                                           v-show="k"></i>
                                    </td>
                                </tr>
                            </table>
                        </fieldset>
                        </div>
                        <button type="submit" class="btn btn-danger" :disabled="show_text">{{show_text ? 'Please wait' : 'Reverse'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>

                    </form>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';
     import { ModelSelect } from 'vue-search-select';
    export default {
        props:['reverse'],
        data(){
            return {
                 form:{
                    project_id:'',
                    where_to_charge:'',
                    description:'',
                    requested_on:'',
                    requested_by:'',
                    external_reference:'',
                    person_collecting:'',
                    type:'Internal',
                    customer_id:'',
                    account:'',
                    group_name:'',
                    credit_account_id:'',
                    subproject_id:'',
                    inventory_items_internal: [{part: '', uom:'',quantity: '',unit_cost:'',total_cost:'',total_cost_inclusive:'',qty_available:''}],
                    inventory_items_external: [{part: '', uom:'',quantity: '',unit_price:'',total_price:'',total_price_inclusive:'',qty_available:''}],
                    id:''
                },
                reverse_req: this.reverse,
                inventory_items_reversal:[],
                show_text:false,
                projects:[],
                parts:{},
                qty:'',
                part:'',
                unit_price:'',
                users:{},
                username:User.name(),
                stk_groups:[],
                items:[],
                initial_unit_cost:'',
                stks:{},
                accounts:[],
                uoms:[],
                customers:[],
                pricelists:{},
                all_customers:{},
                units:{},
                filtered_uoms:{},
                show_issue:true,
                show_customer:false,
                external_reqs:[],
                internal_reqs:[],
                requisition:{},
                subprojects:[],
                accountsd:[]
            }
        },
        created(){
            this.getAllDetails();
            this.listen();
            this.getProjects();
            this.getGroups();
            this.creditAccount();
            this.getAccounts();
            this.getCustomers();
            this.getAccountsDebit();
           },
        watch:{
            'form.project_id'(){
             setTimeout(()=>{
             this.subProject();
             },3000)
            },
            'form.type'(){
             if (this.form.type =='External') {
             this.show_customer = true;
             this.show_issue = false;
             }
             else if (this.form.type =='Internal') {
             this.show_customer = false;
             this.show_issue = true;
             }

            },
            qty(){

            if (isNaN(parseFloat(this.qty)) && !isFinite(this.qty) && this.qty < 0) {
              //console.log('qqtyy....')
              this.qty = 1;
            }

            },
            getExpenses(){
            let customer;
            let item_array = [];
            let uoms;
            this.uoms = [];
                if (this.form.customer_id) {
                   customer =this.all_customers.find(q =>q.id ==this.form.customer_id)
                   }

                for (let p=0; p < this.parts.length; p++){
                    for (let i=0; i < this.form.inventory_items_internal.length; i++){
                           if (this.form.inventory_items_internal[i]['part'] === this.parts[p]['id']){
                            //Uom alGo goes here
                                uoms = this.units.filter(u =>u.id_units == this.parts[p]['uom_stock_id'] || u.id_units == this.parts[p]['uom_purchase_id'] || u.id_units == this.parts[p]['uom_sellunit_id'])

                        if (uoms.length) {
                          this.form.inventory_items_internal[i]['uom'] = uoms[0]['description']
                       }
                        this.form.inventory_items_internal[i]['qty_available'] = this.parts[p]['qty_on_hand']
                        if (this.form.inventory_items_internal[i]['quantity'] !=='' && this.form.inventory_items_internal[i]['part'] !==''){
                            if(this.form.inventory_items_internal[i]['quantity'] < 0 || (isNaN(parseFloat(this.form.inventory_items_internal[i]['quantity'])) && !isFinite(this.form.inventory_items_internal[i]['quantity']))){
                               this.form.inventory_items_internal[i]['quantity'] = 1;
                            }
                          if (this.form.inventory_items_internal[i]['quantity'] > this.parts[p]['qty_on_hand']) {
                            this.form.inventory_items_internal[i]['quantity'] ='';
                            return this.$toastr.e('Sorry, requested Qty cannot be greater than available Qty.');
                          }
                                if(!customer){
                                    this.form.inventory_items_internal[i]['unit_cost'] = this.parts[p]['cost'];
                                this.form.inventory_items_internal[i]['total_cost_inclusive'] = ((this.parts[p]['cost'] * 116/100) * this.form.inventory_items_internal[i]['quantity']).toFixed(2);
                                this.form.inventory_items_internal[i]['total_cost'] = (this.parts[p]['cost'] * this.form.inventory_items_internal[i]['quantity']).toFixed(2);
                              }
                                item_array.push({
                                    'id' : this.parts[p]['id'],
                                    'cost' : this.parts[p]['cost'],
                                    'qty': this.form.inventory_items_internal[i]['quantity']
                                })
                            }
                        }
                    }
                }

                if (item_array.length && customer) {
                     item_array.forEach(item => {
                     for(let q=0;q<this.form.inventory_items_internal.length;q++){
                      this.pricelists.forEach(m => {
                       if(m.id ==item.id && this.form.inventory_items_internal[q]['part']==item.id){
                      this.form.inventory_items_internal[q]['unit_cost'] = m.exclusive_price;
                      this.form.inventory_items_internal[q]['total_cost_inclusive'] = (m.inclusive_price * item.qty).toFixed(2);
                      this.form.inventory_items_internal[q]['total_cost'] = (m.exclusive_price * item.qty).toFixed(2);
                       }
                       });
                        }

                    })
                }

            },
            getExternal(){

            let customer;
            let item_array = [];
            let uoms;

                if (this.form.customer_id) {
                   customer =this.all_customers.find(q =>q.id ==this.form.customer_id)
                   }

                for (let p=0; p < this.parts.length; p++){
                    for (let i=0; i < this.form.inventory_items_external.length; i++){
                            if (this.form.inventory_items_external[i]['part'] === this.parts[p]['id']){

                                //uoms algo
                                 // console.log(`stock_id== ${this.parts[p]['uom_stock_id']} purchase_id== ${this.parts[p]['uom_purchase_id']} selling_id== ${this.parts[p]['uom_sellunit_id']}`)
                                uoms = this.units.filter(u =>u.id_units == this.parts[p]['uom_stock_id'] || u.id_units == this.parts[p]['uom_purchase_id'] || u.id_units == this.parts[p]['uom_sellunit_id'])

                        if (uoms.length) {
                          this.form.inventory_items_external[i]['uom'] = uoms[0]['description']
                        }

                        this.form.inventory_items_external[i]['qty_available'] = this.parts[p]['qty_on_hand']
                        if (this.form.inventory_items_external[i]['quantity'] !=='' && this.form.inventory_items_external[i]['part'] !==''){
                            if(this.form.inventory_items_external[i]['quantity'] < 0 || (isNaN(parseFloat(this.form.inventory_items_external[i]['quantity'])) && !isFinite(this.form.inventory_items_external[i]['quantity']))){
                               this.form.inventory_items_external[i]['quantity'] = 1;
                            }
                          if (this.form.inventory_items_external[i]['quantity'] > this.parts[p]['qty_on_hand']) {
                            this.form.inventory_items_external[i]['quantity'] ='';
                            return this.$toastr.e('Sorry, requested Qty cannot be greater than available Qty.');
                          }

                              if(!customer){
                               this.form.inventory_items_external[i]['unit_price'] = this.parts[p]['cost']
                                this.form.inventory_items_external[i]['total_price_inclusive'] = ((this.form.inventory_items_external[i]['unit_price']* 116/100) * this.form.inventory_items_external[i]['quantity']).toFixed(2);
                                this.form.inventory_items_external[i]['total_price'] = (this.form.inventory_items_external[i]['unit_price'] * this.form.inventory_items_external[i]['quantity']).toFixed(2);
                            }
                            item_array.push({
                                    'id' : this.parts[p]['id'],
                                    'cost' : this.parts[p]['cost'],
                                    'qty': this.form.inventory_items_external[i]['quantity']
                                })



                        }
                    }
                }
            }

                 if (item_array.length && customer) {
                     item_array.forEach(item => {
                     for(let q=0;q<this.form.inventory_items_external.length;q++){
                      this.pricelists.forEach(m => {
                       if(m.id ==item.id && this.form.inventory_items_external[q]['part']==item.id){
                      this.form.inventory_items_external[q]['unit_price'] = m.exclusive_price;
                      this.form.inventory_items_external[q]['total_price_inclusive'] = (m.inclusive_price * item.qty).toFixed(2);
                      this.form.inventory_items_external[q]['total_price'] = (m.exclusive_price * item.qty).toFixed(2);;
                       }
                       });
                        }

                    })
                }


            }
        },
        computed:{
        internalType(){
        return this.form.type =='Internal';
        }   ,
          getExpenses(){
            return [this.part,this.qty,this.form.inventory_items_internal,this.form.group_name,this.form.customer_id].join();
          },
            getExternal(){
             return [this.part,this.qty,this.unit_price,this.form.inventory_items_external,this.form.group_name,this.form.customer_id].join();
            }
        },

        methods:{
            getAllDetails(){
                this.all_customers = this.$store.state.all_my_customers;
                this.pricelists = this.$store.state.all_my_pricelists;
                this.units = this.$store.state.all_my_uoms;
                this.users = this.$store.state.all_my_users;
                this.parts = this.$store.state.all_my_parts;
                this.vehicles = this.$store.state.all_my_vehicles;

            },

            subProject(){
                this.subprojects =[];
                let subp = this.vehicles.filter(vehicle => vehicle.asset_category_id == this.form.project_id);
                subp.forEach(p => {
                    this.subprojects.push({
                        'value': p.project_link,
                        'text': p.description
                    })
                })
            },
          getRequistion(){
         axios.get(`requisitions/${this.form.id}`)
         .then(res => {
             this.external_reqs = res.data.inventory_items_external;
             this.internal_reqs = res.data.inventory_items_internal;
             //console.log(res.data.inventory_items_internal)
         })
          },
            creditAccount(){
                  if (this.$store.state.all_my_charges.length) {
                 let account = this.$store.state.all_my_charges.find(req => req.type =='Requisition');
               // console.log(account)
                 //this.form.account = this.$store.state.all_my_accounts.;

                 this.form.credit_account_id  = account.account_id;
                }

            },
            resetAccount(){
            this.form.where_to_charge = '';
            },
            resetCustomer(){
            this.form.customer_id = '';
            },
            getPriceLists(){
            axios.get('price-list')
            .then(res => {
                this.pricelists = res.data;
            })
            },
            getCustomers(){
                this.all_customers.forEach(c => {
                    this.customers.push({
                        'value': c.id,
                        'text': c.name
                    })
                })

            },

        getAccounts(){
           let accounts = this.$store.state.all_my_accounts.filter(acc => acc.account_link !==this.form.credit_account_id)
            accounts.forEach(a => {
                this.accounts.push({
                    'value': a.account_link,
                    'text': a.account
                })
            })

        },
            getAccountsDebit(){
                let accounts = this.$store.state.all_my_accounts.filter(acc => acc.account_link !==this.form.where_to_charge)
                accounts.forEach(a => {
                    this.accountsd.push({
                        'value': a.account_link,
                        'text': a.account
                    })
                })

            },
            costing(cost){
            return cost;
            },
            checkQty(n){
            return !isNaN(parseFloat(n)) && isFinite(n);
            },
            selectedGroup(){
                     if (this.form.type =='External' && !this.form.customer_id) {
                    this.form.group_name = '';
                return this.$toastr.e('Please Select customer first');
                }

                let items=[];
                 items = this.parts.filter(item => item.item_group == this.form.group_name);

             if (this.form.type =='Internal') {
                  this.items = [];
                if (Object.values(this.form.inventory_items_internal[0])[0] !== '') {
                     for (let i = 0; i < this.form.inventory_items_internal.length; i++) {
                        this.parts.forEach(p => {
                            if (p.id == this.form.inventory_items_internal[i]['part']) {
                                items.push(p);
                            }
                        })

                    }
                }
             }

                if (this.form.type =='External') {
                      this.items = [];
                if (Object.values(this.form.inventory_items_external[0])[0] !== '') {
                     for (let i = 0; i < this.form.inventory_items_external.length; i++) {
                        this.parts.forEach(p => {
                            if (p.id == this.form.inventory_items_external[i]['part']) {
                                items.push(p);
                            }
                        })

                    }
                }
             }

            items.forEach(p => {
                this.items.push({
                    'value': p.id,
                    'text': p.item_group +'-'+p.description
                })
            })

            },
            getGroups(){
                axios.get('stk-groups')
                .then(res => {
                    res.data.forEach(stk => {
                        this.stk_groups.push({
                            'value': stk.name,
                            'text': stk.name +'-'+stk.description
                        })
                    })
                })
            },

            convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            removeItem(i) {
                this.form.inventory_items_internal.splice(i, 1);
            },
            addItem() {
                this.form.inventory_items_internal.push({part: '',uom:'', quantity: '',unit_cost:'',total_cost:'',total_cost_inclusive:'',qty_available:''});

            },
            removeItemExternal(i) {
             this.form.inventory_items_internal.splice(i, 1);
            },
            addItemExternal() {
                this.form.inventory_items_external.push({part: '',uom:'', quantity: '',unit_price:'',total_price:'',total_price_inclusive:'',qty_available:''});
            },
            getProjects(){
                    this.$store.state.all_my_projects.forEach(p => {
                     this.projects.push({
                        'value': p.project_link,
                        'text': p.name
                     })
                    })

            },
            saveRequisition(){
          this.inventory_items_reversal = [];
                if (Object.values(this.form.inventory_items_internal[0])[0] !== '' || Object.values(this.form.inventory_items_internal[0])[2] !== '') {
                    for (let i = 0; i < this.form.inventory_items_internal.length; i++) {
                        if (this.form.inventory_items_internal[i]['quantity'] === '') {
                            return this.$toastr.e('Inventory Item Quantity fields are required.');
                        }

                             this.inventory_items_reversal.push({
                             'part':this.form.inventory_items_internal[i]['part'],
                             'uom': this.form.inventory_items_internal[i]['uom'],
                             'quantity': this.form.inventory_items_internal[i]['quantity'],
                             'unit_price': this.form.inventory_items_internal[i]['unit_cost'],
                             'total_price': this.form.inventory_items_internal[i]['total_cost'],
                             'total_price_inclusive': this.form.inventory_items_internal[i]['total_cost_inclusive'],
                             'qty_available': this.form.inventory_items_internal[i]['qty_available'],
                            });


                    }
                }

                if (Object.values(this.form.inventory_items_external[0])[0] !== '' || Object.values(this.form.inventory_items_external[0])[2] !== '' || Object.values(this.form.inventory_items_external[0])[3] !== '') {
                    for (let i = 0; i < this.form.inventory_items_external.length; i++) {
                        if (this.form.inventory_items_external[i]['quantity'] === '') {
                            return this.$toastr.e('Inventory Item Quantity fields are required.');
                        }
                          this.inventory_items_reversal.push({
                             'part':this.form.inventory_items_external[i]['part'],
                             'uom': this.form.inventory_items_external[i]['uom'],
                             'quantity': this.form.inventory_items_external[i]['quantity'],
                             'unit_price': this.form.inventory_items_external[i]['unit_price'],
                             'total_price': this.form.inventory_items_external[i]['total_price'],
                             'total_price_inclusive': this.form.inventory_items_external[i]['total_price_inclusive'],
                             'qty_available': this.form.inventory_items_external[i]['qty_available'],
                            });
                    }

                }
                 this.inventory_items_reversal = this.inventory_items_reversal.filter(req => req.part !==null)
                 //return console.log(this.form);

                this.update();
            },
            save(){

                delete this.form.id;
                this.form.requested_by = User.id();
                axios.post('requisitions',this.form)
                    .then(res =>{
                      eventBus.$emit('listReqs',res.data)
                    })
                    .catch(error => error.response)
            },
                update(){
                  this.show_text = true;
                axios.post(`rev-reqs`,{'inventory_items_reversal':this.inventory_items_reversal,'id':this.form.id,'credit_account_id':this.form.credit_account_id})
                    .then(res => {
                         this.show_reversal = false;
                         this.reverse_req = false;
                        this.edit_requisition = false;
                         eventBus.$emit('updateRequisition',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                 this.form = this.$store.state.requisitions;
                this.subProject();
                this.selectedGroup();
                },
        },
        components:{
            datepicker,
            ModelSelect
        }
    }
</script>

<style>
.remove_2, .add_2{
    margin-left: 25px;
}
    .p_ex_2{
        margin-left: 20px;
    }
    .p_in_2{
        margin-left: 15px;
    }
   .the-legend {
    border-style: none;
    border-width: 0;
    font-size: 14px;
    line-height: 20px;
    margin-bottom: 0;
    width: auto;
    padding: 0 10px;
    border: 1px solid #e0e0e0;
}
.the-fieldset {
    border: 1px solid #e0e0e0;
    padding: 10px;
}
.fyr{
    font-weight:800
}
.fy{
    display:flex;
}
.bf{
    width:100%
}
.i_p{
    width: 250px !important;
   }

   .reset_btn{
    -webkit-appearance: button;
    -webkit-writing-mode: horizontal-tb !important;
    text-rendering: auto;
    color: buttontext;
    letter-spacing: normal;
    word-spacing: normal;
    text-transform: none;
    text-indent: 0px;
    text-shadow: none;
    display: inline-block;
    text-align: center;
    align-items: flex-start;
    cursor: default;
    background-color: buttonface;
    box-sizing: border-box;
    margin: 0em;
    font: 400 13.3333px Arial;
    padding: 1px 6px;
    border-width: 2px;
    border-style: outset;
    border-color: buttonface;
    border-image: initial;
    margin-top: 10px;
   }
   .hide_this{
    display:none;
   }
</style>
