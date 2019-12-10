<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_requisition ? 'Update Requisition: #'+form.req_no : 'New Requisition'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveRequisition()">
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" class="form-control" v-model="form.description" required>
                        </div>
                        <div class="form-group">
                            <label>Requested On</label>
                            <datepicker v-model="form.requested_on" required></datepicker>
                        </div>
                        <div class="form-group">
                            <label>Req Type</label>
                            <select class="form-control" v-model="form.type" required>
                                <option value="Internal">Internal</option>
                                <option value="External">External</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Requested By</label>
                            <select class="form-control" v-model="form.requested_by" required>
                                <option :value="user.id" v-for="user in users" :key="user.id">{{user.name}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Department</label>
                            <select class="form-control" v-model="form.project_id" required>
                                <option :value="project.id" v-for="project in projects" :key="project.id">{{project.code}}</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Where to charge</label>
                            <input type="text" class="form-control" v-model="form.where_to_charge" required>
                        </div>
                        <div class="form-group" v-if="form.type==='Internal'">
                            <label>Items</label>
                            <table style="width: 100%">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr v-for="(item,k) in form.inventory_items_internal" :key="k">
                                    <td><select class="form-control i_p" v-model="item.part" @change="part =item.part">
                                        <option selected disabled>Select Item</option>
                                        <option :value="part.id" v-for="part in parts" :key="part.id">
                                            {{part.code}} - {{part.description}}
                                        </option>
                                    </select>
                                    </td>
                                    <td><input type="number" class="form-control qty" v-model="item.quantity"
                                               placeholder="Qty"  @keyup="qty = item.quantity">
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
                                        <i class="fa fa-plus-circle add_2" @click="addItem(k)"
                                           v-show="k == form.inventory_items_internal.length-1"></i>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="form-group" v-if="form.type==='External'">
                            <label>Items</label>
                            <table style="width: 100%">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr v-for="(item,k) in form.inventory_items_external" :key="k">
                                    <td><select class="form-control i_p" v-model="item.part" @change="part =item.part">
                                        <option selected disabled>Select Item</option>
                                        <option :value="part.id" v-for="part in parts" :key="part.id">
                                            {{part.code}} - {{part.description}}
                                        </option>
                                    </select>
                                    </td>
                                    <td><input type="number" class="form-control qty" v-model="item.quantity"
                                               placeholder="Qty"  @keyup="qty = item.quantity">
                                    </td>
                                    <td><input type="number" class="form-control p_in" step="0.001" v-model="item.unit_price"
                                               placeholder="Unit Price" @keyup="unit_price = item.unit_price"></td>
                                    <td><input type="number" class="form-control p_in_2" step="0.001" v-model="item.total_price"
                                               placeholder="Total Price" disabled></td>
                                    <td><input type="number" class="form-control p_ex_2" step="0.001" v-model="item.total_price_inclusive"
                                               placeholder="Total Price Inclusive VAT" disabled></td>
                                    <td>
                                        <i class="fa fa-minus-circle remove_2" @click="removeItemExternal(k)"
                                           v-show="k || ( !k && form.inventory_items_external.length > 1)"></i>
                                        <i class="fa fa-plus-circle add_2" @click="addItemExternal(k)"
                                           v-show="k == form.inventory_items_external.length-1"></i>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_requisition ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
    import datepicker from 'vuejs-datepicker';

    export default {
        props:['edit'],
        data(){
            return {
                form:{
                    project_id:'',
                    where_to_charge:'',
                    description:'',
                    requested_on:'',
                    requested_by:'',
                    type:'Internal',
                    inventory_items_internal: [{part: '', quantity: '',unit_cost:'',total_cost:'',total_cost_inclusive:''}],
                    inventory_items_external: [{part: '', quantity: '',unit_price:'',total_price:'',total_price_inclusive:''}],
                    id:''
                },
                edit_requisition: this.edit,
                projects:{},
                parts:{},
                qty:'',
                part:'',
                unit_price:'',
                users:{}
            }
        },
        created(){
            this.listen();
            this.getProjects();
            this.getParts();
            this.getUsers();
            console.log(this.form.type)
        },
        watch:{
            getExpenses(){
                for (let p=0; p < this.parts.length; p++){
                    for (let i=0; i < this.form.inventory_items_internal.length; i++){
                        if (this.form.inventory_items_internal[i]['quantity'] !=='' && this.form.inventory_items_internal[i]['part'] !==''){
                            if (this.form.inventory_items_internal[i]['part'] === this.parts[p]['id']){
                                this.form.inventory_items_internal[i]['unit_cost'] = this.parts[p]['cost'];
                                this.form.inventory_items_internal[i]['total_cost_inclusive'] = ((this.parts[p]['cost'] * 116/100) * this.form.inventory_items_internal[i]['quantity']).toFixed(2);
                                this.form.inventory_items_internal[i]['total_cost'] = (this.parts[p]['cost'] * this.form.inventory_items_internal[i]['quantity']).toFixed(2);
                            }
                        }
                    }
                }
            },
            getExternal(){
                for (let p=0; p < this.parts.length; p++){
                    for (let i=0; i < this.form.inventory_items_external.length; i++){
                        if (this.form.inventory_items_external[i]['quantity'] !=='' && this.form.inventory_items_external[i]['part'] !=='' && this.form.inventory_items_external[i]['unit_price'] !==''){
                            if (this.form.inventory_items_external[i]['part'] === this.parts[p]['id']){
                                this.form.inventory_items_external[i]['total_price_inclusive'] = ((this.form.inventory_items_external[i]['unit_price']* 116/100) * this.form.inventory_items_external[i]['quantity']).toFixed(2);
                                this.form.inventory_items_external[i]['total_price'] = (this.form.inventory_items_external[i]['unit_price'] * this.form.inventory_items_external[i]['quantity']).toFixed(2);
                            }
                        }
                    }
                }
            }
        },

        computed:{
          getExpenses(){
              return [this.part,this.qty,this.form.inventory_items_internal].join();
          },
            getExternal(){
                return [this.part,this.qty,this.unit_price,this.form.inventory_items_external].join();
            }
        },

        methods:{
            getUsers(){
              axios.get('users')
                  .then(users => {
                      this.users = users.data;
                  })
            },
            getParts(){
              axios.get('parts')
                  .then(parts => {
                      this.parts = parts.data
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
                this.form.inventory_items_internal.push({part: '', quantity: '',unit_cost:'',total_cost:'',total_cost_inclusive:''});
            },
            removeItemExternal(i) {
                this.form.inventory_items_internal.splice(i, 1);
            },
            addItemExternal() {
                this.form.inventory_items_external.push({part: '', quantity: '',unit_price:'',total_price:'',total_price_inclusive:''});
            },
            getProjects(){
              axios.get('projects')
                  .then(project => {
                      this.projects = project.data;
                  })
            },
            saveRequisition(){
                if (Object.values(this.form.inventory_items_internal[0])[0] !== '' || Object.values(this.form.inventory_items_internal[0])[1] !== '') {
                    for (let i = 0; i < this.form.inventory_items_internal.length; i++) {
                        if (this.form.inventory_items_internal[i]['part'] === '' || this.form.inventory_items_internal[i]['quantity'] === '') {
                            return this.$toastr.e('Inventory Item and Quantity fields are required.');
                        }
                    }
                }
                if (Object.values(this.form.inventory_items_external[0])[0] !== '' || Object.values(this.form.inventory_items_external[0])[1] !== ''|| Object.values(this.form.inventory_items_external[0])[2] !== '') {
                    for (let i = 0; i < this.form.inventory_items_external.length; i++) {
                        if (this.form.inventory_items_external[i]['part'] === '' || this.form.inventory_items_external[i]['quantity'] === '' || this.form.inventory_items_external[i]['unit_price'] === '') {
                            return this.$toastr.e('Inventory Item , Quantity and Price fields are required.');
                        }
                    }
                }
                this.form.requested_on = this.convertDate(this.form.requested_on);


                this.edit_requisition ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('requisitions',this.form)
                    .then(res =>{
                      eventBus.$emit('listReqs',res.data)
                    })
                    .catch(error => error.response)
            },
                update(){
                axios.patch(`requisitions/${this.form.id}`,this.form)
                    .then(res => {
                        this.edit_requisition = false;
                         eventBus.$emit('updateRequisition',res.data);
                    })
                    .catch(error => error.response)
            },
            cancel(){
                eventBus.$emit('cancel')
            },
            listen(){
                if (this.edit){
                    this.form = this.$store.state.requisitions;
                    if (this.form.type === 'Internal'){
                        this.form.inventory_items_internal = JSON.parse(this.form.inventory_items_internal);
                    }
                    else if (this.form.type === 'External'){
                        this.form.inventory_items_external = JSON.parse(this.form.inventory_items_external);
                    }
                else {
                        this.form.inventory_items_internal= [{part: '', quantity: '',unit_cost:'',total_cost:'',total_cost_inclusive:''}];
                        this.form.inventory_items_external= [{part: '', quantity: '',unit_price:'',total_price:'',total_price_inclusive:''}];
                    }
                }

            },
        },
        components:{
            datepicker
        }
    }
</script>

<style scoped>
.remove_2, .add_2{
    margin-left: 25px;
}
    .p_ex_2{
        margin-left: 20px;
    }
    .p_in_2{
        margin-left: 15px;
    }
</style>
