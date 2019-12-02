<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_requisition ? 'Update Requisition' : 'New Requisition'}}</h3>
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
                        <div class="form-group">
                            <label>Items</label>
                            <table style="width: 100%">
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr v-for="(item,k) in form.inventory_items" :key="k">
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
                                    <td><input type="number" class="form-control p_in" step="0.001" v-model="item.price_inclusive"
                                               placeholder="Price Inclusive VAT" disabled></td>
                                    <td><input type="number" step="0.001" class="form-control p_ex" v-model="item.price_exclusive"
                                               placeholder="Price Exclusive VAT" disabled></td>
                                    <td>
                                        <i class="fa fa-minus-circle remove" @click="removeItem(k)"
                                           v-show="k || ( !k && form.inventory_items.length > 1)"></i>
                                        <i class="fa fa-plus-circle add" @click="addItem(k)"
                                           v-show="k == form.inventory_items.length-1"></i>
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
                    inventory_items: [{part: '', quantity: '',price_inclusive:'',price_exclusive:''}],
                    id:''
                },
                edit_requisition: this.edit,
                projects:{},
                parts:{},
                qty:'',
                part:'',
                users:{}
            }
        },
        created(){
            this.listen();
            this.getProjects();
            this.getParts();
            this.getUsers();
        },
        watch:{
            getExpenses(){
                for (let p=0; p < this.parts.length; p++){
                    for (let i=0; i < this.form.inventory_items.length; i++){
                        if (this.form.inventory_items[i]['quantity'] !=='' && this.form.inventory_items[i]['part'] !==''){
                            if (this.form.inventory_items[i]['part'] === this.parts[p]['id']){
                                this.form.inventory_items[i]['price_inclusive'] = ((this.parts[p]['cost'] * 116/100) * this.form.inventory_items[i]['quantity']).toFixed(2);
                                this.form.inventory_items[i]['price_exclusive'] = (this.parts[p]['cost'] * this.form.inventory_items[i]['quantity']).toFixed(2);
                            }
                        }
                    }
                }
            }
        },
        computed:{
          getExpenses(){
              return [this.part,this.qty,this.form.inventory_items].join();
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
                this.form.inventory_items.splice(i, 1);
            },
            addItem() {
                this.form.inventory_items.push({part: '', quantity: '',price_inclusive:'',price_exclusive:''});
            },
            getProjects(){
              axios.get('projects')
                  .then(project => {
                      this.projects = project.data;
                  })
            },
            saveRequisition(){
                if (Object.values(this.form.inventory_items[0])[0] !== '' || Object.values(this.form.inventory_items[0])[1] !== '') {
                    for (let i = 0; i < this.form.inventory_items.length; i++) {
                        if (this.form.inventory_items[i]['part'] === '' || this.form.inventory_items[i]['quantity'] === '') {
                            return this.$toastr.e('Inventory Item and Quantity fields are required.');
                        }
                    }
                }
                this.form.requested_on = this.convertDate(this.form.requested_on);
                this.form.inventory_items = JSON.stringify(this.form.inventory_items);
                this.edit_requisition ? this.update() : this.save();
            },
            save(){
                delete this.form.id;
                axios.post('requisitions',this.form)
                    .then(res => eventBus.$emit('listRequisitions',res.data))
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
                    this.form.inventory_items = JSON.parse(this.$store.state.requisitions.inventory_items)

                }
            },
        },
        components:{
            datepicker
        }
    }
</script>

<style scoped>

</style>
