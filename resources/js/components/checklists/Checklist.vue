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
                            <label class="radio-inline"><input type="radio" name="check_list" value="0"
                                                               v-model="check_list">Type Checklist manually</label>
                            <label class="radio-inline"><input type="radio" name="check_list"
                                                               value="1" v-model="check_list">Upload File</label>
                        </div>
                        <div class="form-group" v-if="check_list==1">
                            <label>Upload Checklist File(s)</label>
                            <div class="large-12 medium-12 small-12 filezone">
                                <input type="file" id="files" ref="files" multiple @change="handleFiles()"/>
                                <p>
                                    Drop your files here <br>or click to search
                                </p>
                            </div>
                            <div v-for="(file, k) in form.files" class="file-listing">
                                <img class="preview" :ref="'preview'+parseInt(k)"/>
                                {{ file.name}}
                                <div class="remove-container">
                                    <a class="remove" v-on:click="removeFile(k)"><i class="fa fa-trash"></i> Remove</a>
                                </div>
                            </div>
                            <br>
                            <div v-for="(file, key) in updated_files" class="file-listing" v-if="edit">
                                 <img :src="'storage/files/'+file.path" :alt="file.name">
                                 {{ file.filename}}
                                <div class="remove-container">
                                    <a class="success" v-on:click="downloadFile(file)"><i class="fa fa-download"></i> Download</a>
                                    <a class="success" v-on:click="deleteFile(file)" style="margin-left: 10px;"><i class="fa fa-trash"></i> Remove</a>
                                </div>
                            </div>
                        </div>
                        <div class="form-group" v-if="check_list==0">
                            <label>Enter Checklist</label>
                            <table style="width: 100%">
                                <tr>
                                    <th> </th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr v-for="(checklist,k) in form.checklists">
                                    <td><input type="text" class="form-control action_name" placeholder="Action Name"
                                               v-model="checklist.name"></td>
                                    <td><input type="text" class="form-control description" placeholder="Description"
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
                    files:[],
                    checklists: [{name: '', description: ''}],
                    id: ''
                },
                edit_checklist: this.edit,
                expiry_types: {},
                duration_type: '',
                show_duration: false,
                check_list: 0,
                updated_files:[]

            }
        },
        created() {
            this.listen();
            this.getExpiryTypes();
        },
        methods: {
            //download File   function
            downloadFile(file) {
                let path = site_url + 'storage/files/' + file.path;
                axios.get(path, { responseType: 'blob' })
                    .then(({ data }) => {
                        const blob = new Blob([data], { type: file.mime })
                        let link = document.createElement('a')
                        link.href = window.URL.createObjectURL(blob)
                        link.download = file.filename
                        link.click()
                    }).catch(error => console.error(error))

            },
            handleFiles() {
                let uploadedFiles = this.$refs.files.files;
                for (var i = 0; i < uploadedFiles.length; i++) {
                    this.form.files.push(uploadedFiles[i]);
                }
                this.getImagePreviews();
            },
            getImagePreviews() {
                for (let i = 0; i < this.form.files.length; i++) {
                    if (/\.(jpe?g|png|gif)$/i.test(this.form.files[i].name)) {
                        let reader = new FileReader();
                        reader.addEventListener("load", function () {
                            this.$refs['preview' + parseInt(i)][0].src = reader.result;
                        }.bind(this), false);
                        reader.readAsDataURL(this.form.files[i]);
                    } else {
                        this.$nextTick(function () {
                            this.$refs['preview' + parseInt(i)][0].src = '/img/generic.png';
                        });
                    }
                }
            },
            removeFile(key) {
                this.form.files.splice(key, 1);
                this.getImagePreviews();
            },
            deleteFile(file){
                      axios.post(`remove-checklist-file`,{id:file.id})
                    .then(res => {
                       for (let i=0;i<this.updated_files.length;i++){
                           if (this.updated_files[i]['id'] === file.id){
                              this.updated_files.splice(i,1);
                           }
                       }
                    })
            },
            removeChecklist(i) {
                this.form.checklists.splice(i, 1);
            },
            addChecklist() {
                this.form.checklists.push({name: '', description: ''})
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
                if (this.check_list === 0) {
                    if (Object.values(this.form.checklists[0])[0] === '' || Object.values(this.form.checklists[0])[0] === '') {
                        return this.$toastr.e('Checklist field are required.')
                    }
                    for (let i = 0; i < this.form.checklists.length; i++) {
                        if ((this.form.checklists[i]['name'] === null || this.form.checklists[i]['name'] === '') || (this.form.checklists[i]['description'] === null || this.form.checklists[i]['description'] === '')) {
                            return this.$toastr.e('All checklist fields are required');
                        }
                    }
                }
                this.edit_checklist ? this.update() : this.save();
            },
            save() {
                const config = {
                    headers: {'Content-Type': 'multipart/form-data'}
                }
                for (let i = 0; i < this.form.files.length; i++) {
                    if (this.form.files[i].id) {
                        continue;
                    }
                    let formData = new FormData();
                    formData.append('checklist_file', this.form.files[i]);
                    axios.post(`add-checklist-file`, formData, config)
                        .then(data => {
                            this.form.files[i].id = data['data']['id'];
                            if (this.form.files[i].id == this.form.files[this.form.files.length - 1].id) {
                                this.savePartial();
                            }
                            this.form.files.splice(i, 1, this.form.files[i]);
                        })
                }
                if (!this.form.files.length) {
                    this.savePartial();
                }
            },
            savePartial(){
                this.form.checklists = JSON.stringify(this.form.checklists);
                delete this.form.id;
                axios.post('checklists',this.form)
                    .then(res => eventBus.$emit('listChecklists', res.data))
                    .catch(error => error.response)
            },
            update() {
                const config = {
                    headers: {'Content-Type': 'multipart/form-data'}
                }
                for (let i = 0; i < this.form.files.length; i++) {
                    if (this.form.files[i].id) {
                        continue;
                    }
                    let formData = new FormData();
                    formData.append('checklist_file', this.form.files[i]);
                    axios.post(`update-checklist-file/${this.form.id}`, formData, config)
                        .then(data => {
                            this.form.files[i].id = data['data']['id'];
                            if (this.form.files[i].id == this.form.files[this.form.files.length - 1].id) {
                             this.updatePartial();
                            }
                            this.form.files.splice(i, 1, this.form.files[i]);
                        })
                }
                if (!this.form.files.length) {
                    this.updatePartial();
                }
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
                    this.updated_files = this.$store.state.checklists.files;
                    console.log(this.form.files.length > 0)
                    if (this.form.files.length > 0){
                        this.check_list = 1;
                    } else {
                        this.check_list = 0;
                    }
                    this.form.files = [];
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
