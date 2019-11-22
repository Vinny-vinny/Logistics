<template>
    <div>
        <category v-if="add_category" :edit="editing"></category>
        <!-- Main content -->
        <section class="content" v-if="!add_category">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Categories</h3>
                    <button class="btn btn-primary pull-right" @click="add_category=true">Add Category</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th style="display: none">Name</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="category in tableData">
                            <td>{{category.id}}</td>
                            <td>{{category.name}}</td>
                            <td style="display: none">{{category.name}}</td>
                            <td>
                                <button class="btn btn-success btn-sm" @click="editCategory(category)"><i class="fa fa-edit"></i></button>
<!--                                <button class="btn btn-danger btn-sm" @click="deleteCategory(category.id)"><i class="fa fa-trash"></i></button>-->
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

    import Category from "./Category";
    export default {
        data(){
            return {
                tableData: [],
                add_category: false,
                editing: false
            }
        },
        created(){
            this.listen();
            this.getCategories();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getCategories(){
                axios.get('categories')
                    .then(res => this.tableData = res.data)
                    .catch(error => Exception.handle(error))
            },
            editCategory(category){
                this.$store.dispatch('updateCategory',category)
                    .then(() =>{
                        this.editing=true;
                        this.add_category=true;
                    })
            },
            deleteCategory(id){
                axios.delete(`categories/${id}`)
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
                eventBus.$on('listCategory',(category) =>{
                    this.tableData.unshift(category);
                    this.add_category =false;
                    this.initDatable();
                });
                eventBus.$on('cancel',()=>{
                    this.add_category = false;
                    this.editing = false;
                    this.initDatable();
                });
                eventBus.$on('updateCategory',(category)=>{
                    this.add_category = false;
                    this.editing = false;
                    for (let i=0;i<this.tableData.length;i++){
                        if (this.tableData[i].id == category.id){
                            this.tableData.splice(i,1);
                        }
                    }
                    this.tableData.unshift(category);
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
            Category
        }
    }
</script>

<style scoped>

</style>
