<template>
    <div>
        <service-provider-types v-if="add_provider_type" :edit="editing"></service-provider-types>
        <!-- Main content -->
        <section class="content" v-if="!add_provider_type">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Service Provider Type</h3>
                    <button class="btn btn-primary pull-right" @click="add_provider_type=true">Service Provider Types</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="provider in tableData">
                            <td>{{provider.id}}</td>
                            <td>{{provider.name}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editProviderType(provider)"><i class="fa fa-edit"></i></button>
                                <button class="btn btn-danger btn-sm" @click="deleteProviderType(provider.id)"><i class="fa fa-trash"></i></button>
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

    import ServiceProviderTypes from "./ServiceProviderTypes";
    export default {
        data(){
            return {
                tableData: [],
                add_provider_type: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getProviders();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getProviders(){
                axios.get('service-provider-types')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editProviderType(provider){
                this.$store.dispatch('updateProviderType',provider)
                    .then(() =>{
                        this.editing=true;
                        this.add_provider_type=true;
                    })
            },
            deleteProviderType(id){
                axios.delete(`service-provider-types/${id}`)
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
                eventBus.$on('listProviderTypes',(provider) =>{
                    this.tableData.unshift(provider);
                    this.add_provider_type =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_provider_type = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateProviderType',(provider)=>{
                    this.add_provider_type = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == provider.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(provider);
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
            ServiceProviderTypes
        }
    }
</script>

<style scoped>

</style>
