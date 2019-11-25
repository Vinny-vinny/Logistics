<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Projects</h3>
                    <button class="btn btn-success pull-right" @click="importProjects()" :disabled="importing">{{importing ? 'Importing...' : 'Import from Sage'}}</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Description</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="project in tableData">
                            <td>{{project.id}}</td>
                            <td>{{project.code}}</td>
                            <td>{{project.name}}</td>
                            <td>{{project.description}}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>
<script>

    export default {
        data(){
            return {
                tableData: [],
               importing:false,


            }
        },
        created(){
            this.getProjects();
        },
        mounted(){
            this.initDatable();
        },
        methods:{
            getProjects(){
                axios.get('projects')
                    .then(res => {
                      this.tableData = res.data
                    })
            },
            importProjects(){
                this.importing = true;
                    axios.get(`import-projects`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Projects successfully imported.');
                        this.$router.go();
                    })
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

    }
</script>

<style scoped>

</style>
