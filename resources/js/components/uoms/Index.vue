<template>
    <div>
        <!-- Main content -->
        <section class="content">
            <!-- Default box -->
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Unit of Measures</h3>
                    <button class="btn btn-success pull-right" @click="importUoms()" :disabled="importing">{{importing ? 'Importing...' : 'Import from Sage'}}</button>
                </div>
                <div class="box-body">
                    <table class="table table-striped dt">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Code</th>                          
                            <th>Description</th>
                            <th></th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="uom in tableData">
                            <td>{{uom.id}}</td>                         
                            <td>{{uom.code}}</td>
                             <td>{{uom.description}}</td>
                            <td></td>
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
            this.getUoms();
        },
       
        methods:{
            getUoms(){
                axios.get('uom')
                    .then(res => {
                      this.tableData = res.data
                      this.initDatable();
                    })
            },
            importUoms(){
                this.importing = true;
                    axios.get(`import-uom`)
                    .then(res =>{
                        this.importing = false;
                        this.$toastr.s('Uoms successfully imported.');
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
