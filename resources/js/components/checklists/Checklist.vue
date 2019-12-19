<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">{{edit_checklist ? 'Update Checklist' : 'New Checklist'}}</h3>
                </div>
                <div class="box-body">
                    <form @submit.prevent="saveChecklist()" id="checklist">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text" v-model="form.name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Expiry Type</label>
                            <select name="expiry_type_id" class="form-control" v-model="form.expiry_type_id" required
                                    @change="getDuration()">
                                <option :value="expiry.id" v-for="expiry in expiry_types" :key="expiry.id">
                                    {{expiry.name}}
                                </option>
                            </select>
                        </div>
                        <div class="form-group" v-if="show_duration">
                            <label>Enter Number of {{duration_type}}</label>
                            <input type="number" class="form-control" v-model="form.duration_no" required>
                        </div>

                        <div class="form-group">
                            <label>Enter Checklist</label>
                            <table style="width: 100%">
                                <tr>
                                    <th> </th>
                                    <th></th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr v-for="(checklist,k) in form.checklists">
                                    <td>
                                        <select class="form-control action_name" v-model="checklist.tool">
                                            <option :value="tool.id" v-for="tool in tools" :key="tool.id">{{tool.name}}</option>
                                        </select>
                                    </td>
                                    <td><input type="text" class="form-control description" placeholder="Action Name"
                                               v-model="checklist.name"></td>
                                    <td><input type="text" class="form-control description_2" placeholder="Description"
                                               v-model="checklist.description"></td>
                                    <td>
                                        <i class="fa fa-minus-circle add" @click="removeChecklist(k)"
                                           v-show="k || (!k && form.checklists.length > 1)"></i>
                                        <i class="fa fa-plus-circle add" @click="addChecklist(k)"
                                           v-show="k == form.checklists.length-1"></i>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary">{{edit_checklist ? 'Update' : 'Save'}}</button>
                        <button type="button" class="btn btn-outline-danger" @click="cancel">Cancel</button>
                    </form>
                </div>
            </div>
        </section>
    </div>
</template>

<script>

    export default {
        props: ['edit'],
        data() {
            return {
                form: {
                    expiry_type_id: '',
                    duration_no: '',
                    name: '',
                    checklists: [{tool:'tool',name: '', description: ''}],
                    id: ''
                },
                edit_checklist: this.edit,
                expiry_types: {},
                duration_type: '',
                show_duration: false,
                tools:{}

            }
        },
        created() {
            this.listen();
            this.getExpiryTypes();
            this.getTools();
        },
        methods: {
            getTools(){
                axios.get('checklist-tool')
                .then(res => {
                    this.tools =res.data
                })
            },
            removeChecklist(i) {
                this.form.checklists.splice(i, 1);
            },
            addChecklist() {
                this.form.checklists.push({tool:'',name: '', description: ''})
            },
            getDuration() {
                this.expiry_types.forEach(type => {
                    if (type.id === this.form.expiry_type_id) {
                        this.duration_type = type.name;
                        this.show_duration = true;
                        return;
                    }
                })
            },
            getExpiryTypes() {
                axios.get('expiry-types')
                    .then(expiry => {
                        this.expiry_types = expiry.data;
                    })
            },
                saveChecklist() {
                    if (Object.values(this.form.checklists[0])[0] === '' || Object.values(this.form.checklists[0])[1] === '' || Object.values(this.form.checklists[0])[2] === '') {
                        return this.$toastr.e('Checklist field are required.')
                    }
                    for (let i = 0; i < this.form.checklists.length; i++) {
                        if ((this.form.checklists[i]['tool'] === null || this.form.checklists[i]['tool'] === '')||(this.form.checklists[i]['name'] === null || this.form.checklists[i]['name'] === '') || (this.form.checklists[i]['description'] === null || this.form.checklists[i]['description'] === '')) {
                            return this.$toastr.e('All checklist fields are required');
                        }
                    }
                this.edit_checklist ? this.update() : this.save();
            },
            save() {
                this.form.checklists = JSON.stringify(this.form.checklists);
                delete this.form.id;
                axios.post('checklists',this.form)
                    .then(res => eventBus.$emit('listChecklists', res.data))
                    .catch(error => error.response)
            },
            update() {
                this.form.checklists = JSON.stringify(this.form.checklists);
                axios.patch(`checklists/${this.form.id}`, this.form)
                    .then(res => {
                        this.edit_checklist = false;
                        eventBus.$emit('updateChecklist', res.data);
                    })
                    .catch(error => error.response)
            },
            updatePartial(){
                this.form.checklists = JSON.stringify(this.form.checklists);
                axios.patch(`checklists/${this.form.id}`, this.form)
                    .then(res => {
                           this.edit_checklist = false;
                           eventBus.$emit('updateChecklist', res.data);
                    })
                    .catch(error => error.response)
            },
            convertDate(str) {
                var date = new Date(str),
                    mnth = ("0" + (date.getMonth() + 1)).slice(-2),
                    day = ("0" + date.getDate()).slice(-2);
                return [date.getFullYear(), mnth, day].join("-");
            },
            cancel() {
                eventBus.$emit('cancel')
            },
            listen() {
                if (this.edit) {
                    this.form = this.$store.state.checklists;
                    this.show_duration = true;
                    this.duration_type = this.$store.state.checklists.duration_type;
                    this.form.checklists = JSON.parse(this.$store.state.checklists.checklists);
                }
            },
        }
    }
</script>

<style scoped>
    .add {
        margin-left: 20px;
        font-size: 18px;
    }

    .description {
        margin-left: 10px;
        margin-bottom: 10px;

    }
    .description_2{
        margin-left: 20px;
        margin-bottom: 10px;
    }
    .action_name {
        margin-bottom: 10px;
    }
    input[type="file"] {
        opacity: 0;
        width: 100%;
        height: 200px;
        position: absolute;
        cursor: pointer;
    }

    .filezone {
        outline: 2px dashed grey;
        outline-offset: -10px;
        background: #ccc;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px;
        position: relative;
        cursor: pointer;
    }

    .filezone:hover {
        background: #c0c0c0;
    }

    .filezone p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 50px 50px 50px;
    }

    div.file-listing img {
        max-width: 90%;
    }
    div.file-listing {
        margin: auto;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    div.file-listing img {
        height: 100px;
    }

    div.success-container {
        text-align: center;
        color: green;
    }

    div.remove-container {
        text-align: center;
    }

    div.remove-container a {
        color: red;
        cursor: pointer;
    }
</style>
