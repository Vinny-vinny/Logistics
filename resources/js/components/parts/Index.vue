<template>
    <div>
        <parts v-if="add_part" :edit="editing"></parts>
        <!-- Main content -->
        <section class="content" v-if="!add_part">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Parts</h3>
                    <button class="btn btn-primary pull-right" @click="add_part=true">Add Part</button>
                    <button class="btn btn-success pull-right mr" @click="show_warehouse=true">{{importing ? 'Importing...':'Import from Sage'}}</button>
                    <div class="box-body" v-if="show_warehouse">
                        <form @submit.prevent="importParts()">
                             <div class="form-group">
                                 <label>Select Warehouse</label>
                                 <select name="wh" class="form-control" required v-model="warehouse">
                                     <option :value="wh.Code" v-for="wh in warehouses" :key="wh.Code">{{wh.Name}}</option>
                                 </select>
                             </div>
                            <button type="submit" class="btn btn-primary btn-block">Submit</button>
                        </form>
                </div>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Cost</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="part in tableData">
                            <td>{{part.id}}</td>
                            <td>{{part.code}}</td>
                            <td>{{part.description}}</td>
                            <td>{{part.cost}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editPart(part)"><i class="fa fa-edit"></i></button>
<!--<button class="btn btn-danger btn-sm" @click="deletePart(part.id)"><i class="fa fa-trash"></i></button>-->
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
    import Parts from "./Parts";
    export default {
        data(){
            return {
                tableData: [],
                add_part: false,
                editing: false,
                importing:false,
                warehouses:{},
                warehouse:'',
                show_warehouse:false
            }
        },
        created(){
            this.listen();
            this.getParts();
            this.getWarehouses();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getWarehouses(){
              axios.get('warehouse')
                  .then(res => {
                     this.warehouses = res.data
                  })
            },
            importParts(){
                this.importing = true;
                this.show_warehouse = false;
                axios.get(`import-parts/${this.warehouse}`)
                    .then(res =>{
                       this.importing = false;
                       this.$toastr.s('Parts successfully imported.');
                        this.$router.go();
                    })
            },
            getParts(){
                axios.get('parts')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editPart(part){
                this.$store.dispatch('updatePart',part)
                    .then(() =>{
                        this.editing=true;
                        this.add_part=true;
                    })
            },
            deletePart(id){
                axios.delete(`parts/${id}`)
                    .then(res => {
                        for (let i=0;i<this.tableData.length;i++){
                            if (this.tableData[i].id == res.data){
                                this.tableData.splice(i,1);
                            }
                        }
                    })
                    .catch(error => Exception.handle(error))
            },
            listen(){
                eventBus.$on('listParts',(part) =>{
                    this.tableData.unshift(part);
                    this.add_part =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_part = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateParts',(part)=>{
                    this.add_part = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == part.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(part);
                    this.initDatable();
                });
            },
            initDatable(){
                setTimeout(()=>{
                    $('.dt').DataTable({
                        "pagingType": "full_numbers",
                        "lengthMenu": [
                            [10, 25, 50, -1],
                            [10, 25, 50, "All"]
                        ],
                        order: [[ 0, 'asc' ], [ 3, 'desc' ]],
                        responsive: true,
                        destroy: true,
                        retrieve:true,
                        autoFill: true,
                        colReorder: true,

                    });
                },1000)
            },
        },
        components:{
            Parts
        }
    }
</script>

<style scoped>

</style>
